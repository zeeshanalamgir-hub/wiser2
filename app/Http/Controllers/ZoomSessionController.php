<?php

namespace App\Http\Controllers;

use App\Events\VideoCallSignal;
use App\Exceptions\ZoomApiException;
use App\Models\Booking;
use App\Models\ZoomRecording;
use App\Models\ZoomSession;
use App\Services\ZoomService;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZoomSessionController extends Controller
{
    public function __construct(protected ZoomService $zoomService)
    {
    }

    /**
     * Ring the other participant so they can accept/decline before anyone joins.
     */
    public function ring(Request $request, Booking $booking)
    {
        $this->authorizeParticipant($booking);

        $recipientId = $this->resolveRecipient($request, $booking);
        $session = $this->activeSession($booking);

        // A ring is a new call attempt. Reuse the active session only if a call
        // is actually in progress on it (rejoin); anything else is a leftover
        // from an abandoned attempt, which would otherwise make the wrong user
        // the host of this call.
        if ($session && $session->call_status !== ZoomSession::CALL_ACCEPTED) {
            $session->update(['status' => 'ended', 'ended_at' => $session->ended_at ?? now()]);
            $session = null;
        }

        if (!$session) {
            $session = ZoomSession::create([
                'booking_id' => $booking->id,
                'host_user_id' => Auth::id(),
                'session_name' => 'wiserlance-booking-' . $booking->id . '-' . Str::random(8),
                'status' => 'active',
                'call_status' => ZoomSession::CALL_RINGING,
            ]);
        }

        event(new VideoCallSignal(
            $booking->id,
            'ring',
            $recipientId,
            $this->sender(),
            ['answer_url' => $this->workspaceUrl($booking, $recipientId)],
        ));

        return response()->json(['success' => true]);
    }

    /**
     * The callee's browser received the ring and is showing it. Only after this
     * does the caller's UI say "Ringing" — an offline callee never sends it.
     */
    public function ringing(Booking $booking)
    {
        $this->authorizeParticipant($booking);

        $session = $this->activeSession($booking);

        if (!$session || $session->call_status !== ZoomSession::CALL_RINGING || $session->host_user_id == Auth::id()) {
            return response()->json(['success' => false], 409);
        }

        event(new VideoCallSignal($booking->id, 'ringing', (int) $session->host_user_id, $this->sender()));

        return response()->json(['success' => true]);
    }

    /**
     * Decline an incoming ring (callee only).
     */
    public function decline(Request $request, Booking $booking)
    {
        $this->authorizeParticipant($booking);

        $session = $this->activeSession($booking);

        if ($session) {
            $session->update(['status' => 'ended', 'call_status' => ZoomSession::CALL_DECLINED]);
        }

        event(new VideoCallSignal(
            $booking->id,
            'declined',
            $this->resolveRecipient($request, $booking),
            $this->sender(),
        ));
        $this->notifyOtherTabs($booking);

        return response()->json(['success' => true]);
    }

    /**
     * Cancel an outgoing ring before the callee responds (caller only).
     */
    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeParticipant($booking);

        $session = $this->activeSession($booking);

        if ($session) {
            $session->update(['status' => 'ended', 'call_status' => ZoomSession::CALL_CANCELLED]);
        }

        event(new VideoCallSignal(
            $booking->id,
            'cancelled',
            $this->resolveRecipient($request, $booking),
            $this->sender(),
        ));

        return response()->json(['success' => true]);
    }

    /**
     * The caller's ring went unanswered (no answer in time, or the callee never
     * acknowledged it because they're offline). Caller only, and only while the
     * call is still ringing — if the callee answered at the same moment, the
     * accept wins and the caller's 'accepted' signal is on its way.
     */
    public function missed(Booking $booking)
    {
        $this->authorizeParticipant($booking);

        $session = $this->activeSession($booking);

        if (!$session || $session->host_user_id != Auth::id() || $session->call_status !== ZoomSession::CALL_RINGING) {
            return response()->json(['success' => false], 409);
        }

        $session->update([
            'status' => 'ended',
            'call_status' => ZoomSession::CALL_MISSED,
            'ended_at' => now(),
        ]);

        $recipientId = $this->otherParticipant($booking);
        event(new VideoCallSignal($booking->id, 'missed', $recipientId, $this->sender()));
        $this->recordMissedCall($booking, $recipientId);

        return response()->json(['success' => true]);
    }

    /**
     * Start (or rejoin) the video call for a booking's workspace chat.
     */
    public function start(Request $request, Booking $booking)
    {
        $this->authorizeParticipant($booking);

        // Every call begins with ring(), which creates the session. No active
        // session here means the call was already cancelled/missed/ended — e.g.
        // answering a stale ring — so don't silently start a call nobody's in.
        $session = $this->activeSession($booking);

        if (!$session) {
            return response()->json(['message' => 'This call is no longer available.'], 409);
        }

        $role = $session->host_user_id == Auth::id() ? 1 : 0;

        // A non-host joining means the ring was just accepted; let the caller know.
        if ($role === 0) {
            if ($session->call_status === ZoomSession::CALL_RINGING) {
                $session->update(['call_status' => ZoomSession::CALL_ACCEPTED]);
            }

            event(new VideoCallSignal(
                $booking->id,
                'accepted',
                $session->host_user_id,
                $this->sender(),
            ));
            $this->notifyOtherTabs($booking);
        }

        return response()->json([
            'session_id' => $session->id,
            'session_name' => $session->session_name,
            'role' => $role,
            'signature' => $this->zoomService->generateSignature($session->session_name, $role),
            'user_name' => trim(Auth::user()->firstname . ' ' . Auth::user()->lastname),
            // Only the Video SDK host (role 1) can start cloud recording.
            'recording_enabled' => $role === 1 && (bool) config('zoom.cloud_recording.enabled'),
            'recording_auto_start' => (bool) config('zoom.cloud_recording.auto_start'),
        ]);
    }

    /**
     * Called by the browser once it has actually connected to the Zoom session
     * (and again when the host's cloud recording starts or can't start), so the
     * server knows Zoom's session UUID for fetching the recording later — even
     * if the recording webhook never arrives.
     */
    public function sync(Request $request, ZoomSession $session)
    {
        $this->authorizeParticipant($session->booking);

        $data = $request->validate([
            'zoom_session_id' => ['nullable', 'string', 'max:255'],
            'recording' => ['nullable', 'in:started,unavailable,failed'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        if (!empty($data['zoom_session_id'])) {
            $session->zoom_session_uuid = $data['zoom_session_id'];
        }

        // The webhook is the source of truth for anything past "recording"; the
        // browser only ever moves a session INTO recording, never out of a later state.
        if (($data['recording'] ?? null) === 'started' && $session->recording_status === null) {
            $session->recording_status = ZoomSession::RECORDING_RECORDING;
        }

        if (in_array($data['recording'] ?? null, ['unavailable', 'failed'], true)) {
            Log::warning('Zoom cloud recording could not be started', [
                'zoom_session_id' => $session->id,
                'booking_id' => $session->booking_id,
                'result' => $data['recording'],
                // e.g. Zoom errorCode 7200 = cloud recording disabled on the account.
                'reason' => $data['reason'] ?? null,
            ]);
        }

        $session->save();

        return response()->json(['success' => true]);
    }

    /**
     * End a session. Either participant may end it — the call is over for
     * both sides regardless of who was the original caller/host, so the
     * other participant is notified over the same channel used for ring.
     */
    public function end(ZoomSession $session)
    {
        $this->authorizeParticipant($session->booking);

        if ($session->status !== 'ended') {
            $session->update([
                'status' => 'ended',
                'call_status' => ZoomSession::CALL_ENDED,
                'ended_at' => $session->ended_at ?? now(),
                // Ending the call doesn't mean the recording is ready — Zoom still
                // has to process it; the webhook / reconcile command finish this.
                'recording_status' => $session->recording_status === ZoomSession::RECORDING_RECORDING
                    ? ZoomSession::RECORDING_PROCESSING
                    : $session->recording_status,
            ]);

            event(new VideoCallSignal(
                $session->booking_id,
                'ended',
                $this->otherParticipant($session->booking),
                ['id' => Auth::id(), 'name' => trim(Auth::user()->firstname . ' ' . Auth::user()->lastname)],
            ));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Download a call recording from Zoom Cloud — booking participants only.
     * Streamed straight through to the browser; nothing is stored on our server.
     */
    public function downloadRecording(ZoomRecording $recording)
    {
        $booking = $recording->booking ?? $recording->zoomSession?->booking;
        abort_unless($booking, 404);
        $this->authorizeParticipant($booking);
        abort_unless($recording->download_url, 404);

        try {
            $zoomResponse = $this->zoomService->openRecordingStream($recording->download_url);
        } catch (ZoomApiException $e) {
            Log::error('Unable to download Zoom recording', [
                'recording_id' => $recording->id,
                'error' => $e->getMessage(),
            ]);
            // 404 from Zoom = deleted from Zoom Cloud (e.g. by its retention policy).
            return back()->withNotify([[
                'error',
                $e->status === 404 ? 'This recording is no longer available on Zoom.' : 'Unable to download the recording right now. Please try again.',
            ]]);
        }

        $extension = strtolower($recording->file_extension ?: $recording->file_type ?: 'mp4');
        $filename = 'call-recording-' . $booking->id . '-'
            . ($recording->recording_start?->format('Y-m-d-His') ?? $recording->id) . '.' . $extension;
        $body = $zoomResponse->toPsrResponse()->getBody();

        $headers = ['Content-Type' => $zoomResponse->header('Content-Type') ?: 'application/octet-stream'];
        if ($zoomResponse->header('Content-Length')) {
            $headers['Content-Length'] = $zoomResponse->header('Content-Length');
        }

        return response()->streamDownload(function () use ($body) {
            @set_time_limit(0);
            while (!$body->eof()) {
                echo $body->read(1024 * 1024);
                flush();
            }
            $body->close();
        }, $filename, $headers);
    }

    protected function activeSession(Booking $booking): ?ZoomSession
    {
        return ZoomSession::where('booking_id', $booking->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();
    }

    protected function sender(): array
    {
        return ['id' => Auth::id(), 'name' => trim(Auth::user()->firstname . ' ' . Auth::user()->lastname)];
    }

    /**
     * Tell the current user's OTHER open tabs that this ring was answered or
     * declined here, so they stop ringing too (every tab receives the ring).
     */
    protected function notifyOtherTabs(Booking $booking): void
    {
        event(new VideoCallSignal($booking->id, 'handled', (int) Auth::id(), $this->sender()));
    }

    /**
     * The workspace chat page for this booking, from the given user's side —
     * where the call UI lives. Used so an incoming call received on any other
     * page can take the user there to answer.
     */
    protected function workspaceUrl(Booking $booking, int $userId): ?string
    {
        if ($userId == $booking->seller_id && ($booking->job_id || $booking->service_id)) {
            return route('messages.seller', encrypt($booking->job_id ?: $booking->service_id));
        }

        if ($userId == $booking->buyer_id && $booking->job_id) {
            return route('messages.buyer', encrypt($booking->job_id));
        }

        return null;
    }

    /**
     * Leave a "missed video call" message in the booking's workspace chat, so the
     * callee sees it even if they were offline when the call came in.
     */
    protected function recordMissedCall(Booking $booking, int $recipientId): void
    {
        try {
            $message = Chatify::newMessage([
                'from_id' => Auth::id(),
                'to_id' => $recipientId,
                'job_id' => $booking->job_id ?: $booking->service_id,
                'body' => 'Missed video call',
                'attachment' => null,
            ]);

            Chatify::push('private-chatify.' . $recipientId, 'messaging', [
                'from_id' => Auth::id(),
                'to_id' => $recipientId,
                'job_id' => $booking->job_id ?: $booking->service_id,
                'message' => Chatify::messageCard(Chatify::parseMessage($message), true),
                'count' => 0,
            ]);
        } catch (\Throwable $e) {
            // The call itself is already handled; a failed chat note shouldn't break it.
            Log::error('Unable to record missed video call message', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function otherParticipant(Booking $booking): int
    {
        return (int) ($booking->buyer_id == Auth::id() ? $booking->seller_id : $booking->buyer_id);
    }

    /**
     * The chat UI already knows exactly who the "recipient" of this conversation is
     * (rendered as data-id). Prefer that value from the request when present and
     * valid, since it's what the messaging feature itself relies on; fall back to
     * deriving it from the booking's buyer/seller only if it wasn't supplied.
     */
    protected function resolveRecipient(Request $request, Booking $booking): int
    {
        $toUserId = $request->input('to_user_id');
        $derived = $this->otherParticipant($booking);

        $valid = $toUserId !== null
            && (int) $toUserId !== Auth::id()
            && in_array((int) $toUserId, [(int) $booking->buyer_id, (int) $booking->seller_id], true);

        $resolved = $valid ? (int) $toUserId : $derived;

        Log::info('VideoCallSignal recipient resolution', [
            'booking_id' => $booking->id,
            'auth_id' => Auth::id(),
            'booking_buyer_id' => $booking->buyer_id,
            'booking_seller_id' => $booking->seller_id,
            'request_to_user_id' => $toUserId,
            'used_client_supplied' => $valid,
            'derived_other_participant' => $derived,
            'resolved_recipient' => $resolved,
        ]);

        return $resolved;
    }

    protected function authorizeParticipant(Booking $booking)
    {
        if (!in_array(Auth::id(), [$booking->buyer_id, $booking->seller_id])) {
            abort(403);
        }
    }
}
