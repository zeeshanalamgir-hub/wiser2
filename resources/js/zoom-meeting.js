import ZoomVideo, { VideoQuality } from '@zoom/videosdk';
import { startRingtone, stopRingtone, unlockRingtone } from './call-ringtone';
import { startCallAttention, stopCallAttention, requestCallNotificationPermission } from './call-attention';

const client = ZoomVideo.createClient();
let initialized = false;
let stream = null;
// Whether we've joined the session's audio channel (startAudio succeeded) —
// separate from muted/unmuted, which only applies once joined.
let audioStarted = false;
let currentSessionId = null;
let isSharingScreen = false;
let ringTimeout = null;
// SDK userId of whoever is currently sharing their screen, or null if nobody
// is. Tracked app-wide (not just isSharingScreen, which only covers "am I
// the one sharing") so we can disable the button for the OTHER side and show
// "X is already sharing" instead of letting both sides fight over it.
let sharingUserId = null;

const RING_TIMEOUT_MS = 45000;
// If the callee's browser hasn't confirmed it's ringing within this window,
// they aren't online (or have no page open that can ring) — stop and record a
// missed call instead of pretending it's ringing.
const RING_ACK_TIMEOUT_MS = 10000;
const NOTICE_MS = 4000;
let ringAckTimeout = null;
let noticeTimer = null;
// The ring in progress on each side, or null. Signals for any other booking
// are ignored, so a late/stray signal can't hide or start the wrong call UI.
let outgoingCall = null; // { bookingId, toUserId, name }
let incomingCall = null; // { bookingId, fromUserId, name }
// How long to wait for the start request + client.join() before showing the
// error/Retry state. Media permissions are NOT counted — the session is already
// connected by then, and a permission prompt can legitimately take a while.
const CONNECT_TIMEOUT_MS = 30000;

// Connection lifecycle of THE call on this page (the SDK client supports one
// session at a time): idle | connecting | connected | reconnecting | failed.
// Every SDK event handler and async step checks this, so a late event from an
// abandoned attempt (e.g. 'Closed' after we gave up on a slow join) can't tear
// down or overwrite the UI of the current one.
let callState = 'idle';
// Incremented every time a join starts or is abandoned. Each async step of a
// join compares against the value it started with and bails out if it changed —
// this is what stops a timed-out/cancelled join from "finishing" later.
let joinAttempt = 0;
let activeBookingId = null;
let connectTimer = null;
let sessionRecordingEnabled = false;
let sessionRecordingAutoStart = false;
let isRecording = false;
// A start/stop request is in flight — blocks double clicks on the Record button.
let recordingBusy = false;

function myUserId() {
    return document.querySelector('meta[name="user-id"]')?.getAttribute('content');
}

function outgoingPanel() {
    return document.getElementById('zoom-outgoing-call');
}

function incomingPanel() {
    return document.getElementById('zoom-incoming-call');
}

function setOutgoingStatus(text) {
    const status = document.getElementById('zoom-outgoing-status');
    if (status) status.textContent = text;
}

// Caller side. "Calling..." until the callee's browser confirms it received
// the ring (then "Ringing..."), so an offline callee is never shown as ringing.
function showOutgoing(name, bookingId, toUserId) {
    outgoingCall = { bookingId: String(bookingId), toUserId: toUserId || '', name: name || 'user' };
    const label = document.getElementById('zoom-outgoing-name');
    if (label) label.textContent = `Calling ${outgoingCall.name}`;
    setOutgoingStatus('Calling...');
    const panel = outgoingPanel();
    if (panel) {
        panel.dataset.bookingId = bookingId;
        panel.dataset.toUserId = toUserId || '';
        panel.style.display = 'flex';
    }
}

function hideOutgoing() {
    const panel = outgoingPanel();
    if (panel) panel.style.display = 'none';
    if (ringTimeout) {
        clearTimeout(ringTimeout);
        ringTimeout = null;
    }
    if (ringAckTimeout) {
        clearTimeout(ringAckTimeout);
        ringAckTimeout = null;
    }
    outgoingCall = null;
}

function isOutgoingCall(bookingId) {
    return outgoingCall !== null && outgoingCall.bookingId === String(bookingId);
}

function isIncomingCall(bookingId) {
    return incomingCall !== null && incomingCall.bookingId === String(bookingId);
}

function soundButton() {
    return document.getElementById('zoom-enable-sound');
}

// Callee side. Rings until answered, declined, cancelled or missed — every one
// of those paths goes through hideIncoming(), which is what stops the sound.
function showIncoming(name, bookingId, fromUserId) {
    if (isIncomingCall(bookingId)) return; // duplicate delivery of the same ring
    incomingCall = { bookingId: String(bookingId), fromUserId: fromUserId || '', name: name || 'user' };

    const label = document.getElementById('zoom-incoming-name');
    if (label) label.textContent = incomingCall.name;
    const panel = incomingPanel();
    if (panel) {
        panel.dataset.bookingId = bookingId;
        panel.dataset.fromUserId = fromUserId || '';
        panel.style.display = 'flex';
    }

    // false = browser is blocking autoplay until the user interacts with the page.
    const audible = startRingtone();
    if (soundButton()) soundButton().style.display = audible ? 'none' : 'inline-flex';
    startCallAttention(incomingCall.name);

    // Tell the caller this browser is actually showing the ring.
    postJson(`/workspace/zoom/${bookingId}/ringing`, {}).catch((error) => {
        console.error('[zoom-meeting] Unable to acknowledge ring:', error);
    });
}

function hideIncoming() {
    const panel = incomingPanel();
    if (panel) panel.style.display = 'none';
    if (soundButton()) soundButton().style.display = 'none';
    stopRingtone();
    stopCallAttention();
    incomingCall = null;
}

function showCallNotice(text) {
    const notice = document.getElementById('zoom-call-notice');
    const label = document.getElementById('zoom-call-notice-text');
    if (!notice || !label) return;
    label.textContent = text;
    notice.style.display = 'flex';
    if (noticeTimer) clearTimeout(noticeTimer);
    noticeTimer = setTimeout(() => {
        notice.style.display = 'none';
        noticeTimer = null;
    }, NOTICE_MS);
}

// My own share preview (startShareScreen accepts video or canvas).
function shareCanvas() {
    return document.getElementById('zoom-screencast-canvas');
}

// The OTHER participant's shared screen (startShareView requires a real
// <canvas> — passing the video element above here renders nothing, silently).
function remoteShareCanvas() {
    return document.getElementById('zoom-remote-share-canvas');
}

function shareLabel() {
    return document.getElementById('zoom-share-label');
}

function updateShareLabel(userId) {
    const label = shareLabel();
    if (!label) return;
    if (userId === null) {
        label.style.display = 'none';
        return;
    }
    const myId = client.getCurrentUserInfo()?.userId;
    const who = userId === myId ? 'You are' : `${client.getUser(userId)?.displayName || 'The other participant'} is`;
    label.textContent = `${who} sharing their screen`;
    label.style.display = 'block';
}

function setShareButtonAvailability(enabled, sharerName) {
    const btn = document.getElementById('zoom-toggle-screencast');
    if (!btn) return;
    btn.disabled = !enabled;
    btn.title = enabled ? 'Share Screen' : `${sharerName || 'The other participant'} is sharing`;
}

function setButtonState(id, variant, iconClass) {
    const btn = document.getElementById(id);
    if (!btn) return;
    btn.classList.remove('zoom-ctrl-off', 'zoom-ctrl-active');
    if (variant === 'off') {
        btn.classList.add('zoom-ctrl-off');
    } else if (variant === 'active') {
        btn.classList.add('zoom-ctrl-active');
    }
    const icon = btn.querySelector('i');
    if (icon && iconClass) {
        icon.className = iconClass;
    }
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

async function postJson(url, data) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        const error = new Error(`Request to ${url} failed with status ${response.status}`);
        error.status = response.status;
        throw error;
    }

    return response.json();
}

// ── Call duration timer ──────────────────────────────────────────────────────
// Elapsed time is always computed from the moment this call first connected
// (callStartedAt), never by counting ticks — so throttled background tabs,
// reconnects and Retry can't make it drift or reset. The start time is kept
// per server session (timerSessionId): rejoining the same call continues the
// count; a new call starts from 00:00:00.
let callTimerInterval = null;
let callStartedAt = null;
let timerSessionId = null;

function callTimerEl() {
    return document.getElementById('zoom-call-timer');
}

function formatElapsed(ms) {
    const total = Math.max(0, Math.floor(ms / 1000));
    const pad = (n) => String(n).padStart(2, '0');
    return `${pad(Math.floor(total / 3600))}:${pad(Math.floor((total % 3600) / 60))}:${pad(total % 60)}`;
}

function renderCallTimer() {
    const el = callTimerEl();
    if (el && callStartedAt !== null) el.textContent = formatElapsed(Date.now() - callStartedAt);
}

function stopCallTimerInterval() {
    if (callTimerInterval) {
        clearInterval(callTimerInterval);
        callTimerInterval = null;
    }
}

// Called whenever the session is (re)connected.
function startCallTimer(sessionId) {
    if (timerSessionId !== sessionId || callStartedAt === null) {
        timerSessionId = sessionId;
        callStartedAt = Date.now();
    }
    stopCallTimerInterval(); // never more than one interval
    renderCallTimer();
    callTimerInterval = setInterval(renderCallTimer, 1000);

    const el = callTimerEl();
    if (el) el.classList.add('is-visible');
    el?.classList.remove('is-paused');
}

// Connection interrupted: freeze the display but keep the start time, so the
// count picks up correctly (including the time spent reconnecting) afterwards.
function pauseCallTimer() {
    stopCallTimerInterval();
    renderCallTimer();
    callTimerEl()?.classList.add('is-paused');
}

// Call over: remove the timer and forget the start time.
function resetCallTimer() {
    stopCallTimerInterval();
    callStartedAt = null;
    timerSessionId = null;
    const el = callTimerEl();
    if (el) {
        el.classList.remove('is-visible', 'is-paused');
        el.textContent = '00:00:00';
    }
}

function meetingOverlay() {
    return document.getElementById('zoom-meeting-overlay');
}

function connectStatePanel() {
    return document.getElementById('zoom-connect-state');
}

function setConnectStateText(message, detail) {
    const panel = connectStatePanel();
    if (!panel) return;
    panel.querySelector('.zoom-connect-message').textContent = message;
    panel.querySelector('.zoom-connect-detail').textContent = detail || '';
}

// Loader lives inside #zoom-meeting-overlay (position:absolute; inset:0), so it
// only covers the call container, never the rest of the workspace page.
function showConnecting(message, detail) {
    const panel = connectStatePanel();
    if (!panel) return;
    setConnectStateText(message, detail);
    panel.classList.remove('is-error');
    panel.classList.add('is-visible');
    meetingOverlay()?.classList.add('zoom-not-connected');
}

function showConnectError(message, detail) {
    const panel = connectStatePanel();
    if (!panel) return;
    setConnectStateText(message, detail);
    panel.classList.add('is-visible', 'is-error');
    meetingOverlay()?.classList.add('zoom-not-connected');
}

function hideConnectState() {
    connectStatePanel()?.classList.remove('is-visible', 'is-error');
    meetingOverlay()?.classList.remove('zoom-not-connected');
}

function setRecordingBadge(isRecording) {
    const badge = document.getElementById('zoom-recording-badge');
    if (badge) badge.style.display = isRecording ? 'inline-flex' : 'none';
}

function clearConnectTimer() {
    if (connectTimer) {
        clearTimeout(connectTimer);
        connectTimer = null;
    }
}

function errorReason(error) {
    return error?.reason || error?.message || error?.type || String(error);
}

// client.leave() rejects if we never fully joined (e.g. aborting a join that
// is still in flight) — that's expected here, not an error worth surfacing.
async function safeLeave() {
    try {
        await client.leave();
    } catch (error) {
        console.warn('[zoom-meeting] leave() during cleanup:', errorReason(error));
    }
}

// Best-effort: tells the server Zoom's session UUID (needed to look up the
// cloud recording later) and the host's recording state. Never blocks the call.
function syncSessionState(data) {
    const sessionId = currentSessionId;
    if (!sessionId) return;
    postJson(`/workspace/zoom/session/${sessionId}/sync`, data).catch((error) => {
        console.error('[zoom-meeting] Unable to sync session state:', error);
    });
}

function mainView() {
    return document.getElementById('zoom-main-view');
}

function selfView() {
    return document.getElementById('zoom-self-view');
}

// SDK userId -> the .zoom-video-tile wrapper rendering that participant.
const videoTiles = new Map();

async function attachParticipant(userId) {
    const isSelf = userId === client.getCurrentUserInfo()?.userId;
    const element = await stream.attachVideo(userId, VideoQuality.Video_360P);

    const existingWrapper = videoTiles.get(userId);
    if (existingWrapper) existingWrapper.remove();

    const wrapper = document.createElement('div');
    wrapper.className = 'zoom-video-tile';
    wrapper.appendChild(element);
    // Which container a tile lands in is decided by identity (isSelf), never
    // by which side happened to attach first — self always goes to the small
    // corner slot, every remote participant always goes to the main view.
    (isSelf ? selfView() : mainView()).appendChild(wrapper);
    videoTiles.set(userId, wrapper);
}

async function detachParticipant(userId) {
    await stream.detachVideo(userId);
    const wrapper = videoTiles.get(userId);
    if (wrapper) {
        wrapper.remove();
        videoTiles.delete(userId);
    }
}

async function ensureInitialized() {
    if (initialized) {
        return;
    }

    // Without cross-origin-isolation headers (SharedArrayBuffer), the SDK
    // limits rendering to a single video at a time unless this is set — which
    // is why only one side's camera ever showed, on both ends, regardless of
    // who called whom.
    await client.init('en-US', 'Global', { patchJsMedia: true, enforceMultipleVideos: true });

    client.on('peer-video-state-change', async (payload) => {
        if (payload.action === 'Start') {
            await attachParticipant(payload.userId);
        } else {
            await detachParticipant(payload.userId);
        }
    });

    client.on('user-removed', async (payload) => {
        for (const user of payload) {
            await detachParticipant(user.userId);
        }
        // Fallback for when our own backend 'ended' signal doesn't arrive
        // (e.g. Echo/Pusher hiccup) — if nobody else is left in the session,
        // the call is over for us too, not just missing one tile.
        if (callState === 'connected') {
            const myId = client.getCurrentUserInfo()?.userId;
            const remaining = client.getAllUser().filter((u) => u.userId !== myId);
            if (remaining.length === 0) {
                await endCallRemotely();
            }
        }
    });

    // Registered once (ensureInitialized is guarded), so repeated joins/retries
    // never stack duplicate listeners. Events are only acted on for an
    // established call — while connecting or after we've given up on an attempt,
    // joinSession/failConnection own the UI and these would only race them.
    client.on('connection-change', async (payload) => {
        if (payload.state === 'Reconnecting') {
            if (callState !== 'connected') return;
            console.warn('[zoom-meeting] Reconnecting to the video call...');
            callState = 'reconnecting';
            pauseCallTimer();
            showConnecting('Reconnecting...', 'Your connection was interrupted.');
        } else if (payload.state === 'Connected') {
            if (callState !== 'reconnecting') return;
            callState = 'connected';
            startCallTimer(currentSessionId);
            hideConnectState();
        } else if (payload.state === 'Fail') {
            if (callState !== 'connected' && callState !== 'reconnecting') return;
            await failConnection('Lost connection to the Zoom meeting.', payload.reason || 'Check your internet connection and try again.');
        } else if (payload.state === 'Closed') {
            if (callState !== 'connected' && callState !== 'reconnecting') return;
            await endCallRemotely();
        }
    });

    client.on('recording-change', (payload) => {
        // Typed as a RecordingStatus string; tolerate an object shape too.
        const status = typeof payload === 'string' ? payload : payload?.state;
        isRecording = status === 'Recording';
        setRecordingBadge(isRecording);
        updateRecordButton();
    });

    client.on('active-share-change', async (payload) => {
        const myId = client.getCurrentUserInfo()?.userId;
        const isMe = payload.userId === myId;

        if (payload.state === 'Active') {
            sharingUserId = payload.userId;
            updateShareLabel(payload.userId);
            setShareButtonAvailability(isMe, client.getUser(payload.userId)?.displayName);

            if (isMe) {
                // My own share — startShareScreen() already drew the local
                // preview into this same element; just confirm button state.
                isSharingScreen = true;
                setButtonState('zoom-toggle-screencast', 'active', 'fas fa-stop-circle');
            } else {
                // Remote share — render it as the main view, replacing the
                // peer's camera feed (still in the DOM underneath, so it
                // resumes instantly with no re-attach once sharing stops).
                // startShareView requires an actual <canvas> — the video
                // element above is only valid for our OWN startShareScreen
                // preview, passing it here renders nothing (black screen).
                const canvas = remoteShareCanvas();
                canvas.style.display = 'block';
                try {
                    await stream.startShareView(canvas, payload.userId);
                } catch (error) {
                    console.error('Unable to render remote screen share:', error);
                }
            }
        } else {
            const wasMe = payload.userId === myId;
            sharingUserId = null;
            updateShareLabel(null);
            setShareButtonAvailability(true);
            shareCanvas().style.display = 'none';
            remoteShareCanvas().style.display = 'none';

            if (wasMe) {
                isSharingScreen = false;
                setButtonState('zoom-toggle-screencast', 'default', 'fas fa-tv');
            } else {
                try {
                    await stream.stopShareView();
                } catch (error) {
                    console.error('Error stopping share view:', error);
                }
            }
        }
    });

    client.on('passively-stop-share', () => {
        // Fires on MY side when I stop sharing via the browser/OS "Stop
        // Sharing" bar instead of our button. 'active-share-change' above
        // already resets this generically — this is just a safety net in
        // case that event doesn't reach us for some reason.
        isSharingScreen = false;
        sharingUserId = null;
        updateShareLabel(null);
        setShareButtonAvailability(true);
        setButtonState('zoom-toggle-screencast', 'default', 'fas fa-tv');
        shareCanvas().style.display = 'none';
    });

    initialized = true;
}

async function toggleAudio() {
    if (!stream) return;
    try {
        if (!audioStarted) {
            // Joining audio failed on connect (e.g. mic permission was denied
            // then) — this click is the user explicitly asking for their mic,
            // so join audio unmuted now.
            await stream.startAudio();
            audioStarted = true;
            setButtonState('zoom-toggle-audio', 'default', 'fas fa-microphone');
        } else if (stream.isAudioMuted()) {
            await stream.unmuteAudio();
            setButtonState('zoom-toggle-audio', 'default', 'fas fa-microphone');
        } else {
            await stream.muteAudio();
            setButtonState('zoom-toggle-audio', 'off', 'fas fa-microphone-slash');
        }
    } catch (error) {
        console.error('Unable to toggle microphone:', error);
        showCallNotice(`Unable to use your microphone: ${errorReason(error)}`);
    }
}

async function toggleVideo() {
    if (!stream) return;
    try {
        if (stream.isCapturingVideo()) {
            await stream.stopVideo();
            await detachParticipant(client.getCurrentUserInfo().userId);
            setButtonState('zoom-toggle-video', 'off', 'fas fa-video-slash');
        } else {
            await stream.startVideo();
            await attachParticipant(client.getCurrentUserInfo().userId);
            setButtonState('zoom-toggle-video', 'default', 'fas fa-video');
        }
    } catch (error) {
        console.error('Unable to toggle camera:', error);
        showCallNotice(`Unable to use your camera: ${errorReason(error)}`);
    }
}

async function toggleShareScreen() {
    if (!stream) return;

    if (isSharingScreen) {
        try {
            await stream.stopShareScreen();
        } catch (error) {
            console.error('Error stopping screen share:', error);
        }
        // Mirror the optimistic update on the start path below — don't rely
        // solely on 'active-share-change' to reset our OWN UI. That event is
        // a round trip through the SDK and can arrive late (or, in practice,
        // not reliably at all for your own stop), which is exactly why the
        // "You are sharing" label and preview were getting stuck after
        // clicking stop. The peer's camera tile in #zoom-main-view was never
        // replaced by the share (it's an overlay on top of it), so hiding
        // this preview is the entire "restore" — nothing else to re-attach.
        isSharingScreen = false;
        sharingUserId = null;
        setButtonState('zoom-toggle-screencast', 'default', 'fas fa-tv');
        updateShareLabel(null);
        setShareButtonAvailability(true);
        shareCanvas().style.display = 'none';
        return;
    }

    const myId = client.getCurrentUserInfo()?.userId;
    if (sharingUserId !== null && sharingUserId !== myId) {
        const name = client.getUser(sharingUserId)?.displayName || 'The other participant';
        alert(`${name} is already sharing their screen.`);
        return;
    }

    const canvas = shareCanvas();
    canvas.style.display = 'block';
    try {
        await stream.startShareScreen(canvas);
        // 'active-share-change' also fires for our own share and will set the
        // same state again — harmless — but we set it immediately here too so
        // the button responds the instant the browser's share is granted,
        // without waiting on that event's round trip.
        isSharingScreen = true;
        sharingUserId = myId;
        setButtonState('zoom-toggle-screencast', 'active', 'fas fa-stop-circle');
        updateShareLabel(myId);
    } catch (error) {
        // User cancelled the OS screen-picker, or the share failed outright —
        // don't leave a blank overlay or a stuck button behind either way.
        console.error('Unable to start screen share (cancelled or failed):', error);
        canvas.style.display = 'none';
    }
}

async function joinSession(bookingId) {
    if (!window.isSecureContext) {
        alert('Unable to start the video call: this page must be served over HTTPS (or localhost) for camera/microphone access to work in the browser.');
        return;
    }

    // One call at a time per page. The same booking can legitimately ask twice
    // (e.g. the 'accepted' signal arriving while we're already joining) — ignore
    // that; a different booking means a second call, which the SDK can't do.
    if (callState === 'connecting' || callState === 'connected' || callState === 'reconnecting') {
        if (String(activeBookingId) !== String(bookingId)) {
            alert('You are already in a video call. Leave it before joining another one.');
        }
        return;
    }

    const attempt = ++joinAttempt;
    const isStale = () => attempt !== joinAttempt;

    activeBookingId = bookingId;
    callState = 'connecting';
    callEndedHandled = false;

    const overlay = meetingOverlay();
    overlay.style.display = 'block';
    overlay.scrollIntoView({ behavior: 'smooth', block: 'center' });
    showConnecting('Connecting...');

    clearConnectTimer();
    connectTimer = setTimeout(() => {
        if (isStale() || callState !== 'connecting') return;
        failConnection('Connecting to the meeting is taking too long.', 'Please check your connection and try again.');
    }, CONNECT_TIMEOUT_MS);

    try {
        const session = await postJson(`/workspace/zoom/${bookingId}/start`, {});
        if (isStale()) {
            // User clicked Leave before the server answered — the server-side
            // session exists now, so close it out like a normal leave would.
            if (callEndedHandled && session?.session_id) {
                postJson(`/workspace/zoom/session/${session.session_id}/end`, {}).catch(() => {});
            }
            return;
        }
        currentSessionId = session.session_id;
        sessionRecordingEnabled = Boolean(session.recording_enabled);
        sessionRecordingAutoStart = Boolean(session.recording_auto_start);

        await ensureInitialized();
        if (isStale()) return;

        // Resolves only once the SDK has actually joined the session — this, not
        // a timer, is what moves us out of the loading state.
        await client.join(session.session_name, session.signature, session.user_name || 'WiserLance User');
        if (isStale()) {
            // Joined after we already gave up (timeout) or the user left.
            await safeLeave();
            return;
        }
    } catch (error) {
        if (isStale()) return;
        if (error.status === 409) {
            // Answered after the caller cancelled / it was marked missed.
            joinAttempt++;
            cleanupCallUi();
            showCallNotice('This call is no longer available.');
            return;
        }
        console.error('Unable to start/join Zoom video call:', error);
        await failConnection('Unable to connect to the Zoom meeting.', errorReason(error));
        return;
    }

    clearConnectTimer();
    callState = 'connected';
    // client.join() has resolved — the session is genuinely connected now.
    startCallTimer(currentSessionId);
    syncSessionState({ zoom_session_id: client.getSessionInfo()?.sessionId });

    // Connected — keep the panel up (with a different message) only while the
    // browser joins audio (muted), which may be waiting on a mic permission prompt.
    showConnecting('Joining audio (microphone muted)...');
    stream = client.getMediaStream();
    await startLocalMedia();
    if (isStale()) return;
    hideConnectState();

    // peer-video-state-change only fires for changes that happen AFTER we're
    // connected — whoever joins second (the callee) needs to separately check
    // for a peer whose camera was already on before they joined, or that peer's
    // video never gets attached at all. See Zoom's Video SDK docs on rendering
    // remote videos ("When a user joins for the first time...").
    const myUserInfoId = client.getCurrentUserInfo().userId;
    const allUsers = client.getAllUser();
    for (const user of allUsers) {
        // Skip anyone already attached — 'peer-video-state-change' can fire
        // for an already-on peer before this loop runs. Re-attaching the
        // same userId tears down the working video element and replaces
        // it with one that never gets wired to the live stream, which is
        // why the peer's tile ended up broken/wrong.
        if (user.userId !== myUserInfoId && user.bVideoOn && !videoTiles.has(user.userId)) {
            try {
                await attachParticipant(user.userId);
            } catch (error) {
                console.error('Unable to render participant video:', error);
            }
        }
    }

    await startCloudRecordingIfHost();
}

// Everyone (host and participant alike) joins with the microphone muted and the
// camera off; they turn either on themselves with the controls.
//  - Audio: startAudio({ mute: true }) joins the audio channel with the mic
//    already muted, so nothing is transmitted, and so the user can still hear
//    the other side. Unmuting later is instant (no second permission round trip).
//  - Video: simply not started. toggleVideo() starts it on request.
async function startLocalMedia() {
    const mediaStream = stream;
    // stopLocalMedia() nulls `stream` if the call ends while we're in here.
    const stillActive = () => stream && stream === mediaStream;

    setButtonState('zoom-toggle-audio', 'off', 'fas fa-microphone-slash');
    setButtonState('zoom-toggle-video', 'off', 'fas fa-video-slash');

    try {
        await mediaStream.startAudio({ mute: true });
        if (!stillActive()) return;
        audioStarted = true;
    } catch (error) {
        if (!stillActive()) return;
        // Not fatal: toggleAudio() retries startAudio() when the user unmutes.
        console.error('Unable to join audio:', error);
    }
}

// ── Cloud recording (Zoom's own recording, stored in Zoom Cloud) ────────────
// Only the Video SDK host (whoever placed the call) may start/stop it, and only
// if the server enabled it. The recording is made and stored by Zoom; once Zoom
// finishes processing it, the webhook / zoom:reconcile-recordings fetch its
// details and save the URL in zoom_recordings.

const RECORDING_DISABLED_MESSAGE = 'Cloud recording is not enabled on your Zoom Video SDK account.';

function recordButton() {
    return document.getElementById('zoom-toggle-record');
}

function updateRecordButton() {
    const btn = recordButton();
    if (!btn) return;
    btn.style.display = sessionRecordingEnabled ? 'flex' : 'none';
    btn.disabled = recordingBusy;
    btn.title = isRecording ? 'Stop recording' : 'Start recording';
    setButtonState('zoom-toggle-record', isRecording ? 'off' : 'default', isRecording ? 'fas fa-stop' : 'fas fa-record-vinyl');
}

function recordingClientOrNull() {
    try {
        return client.getRecordingClient();
    } catch (error) {
        console.error('Recording client unavailable:', error);
        return null;
    }
}

// silent = automatic start on connect: report problems to the server log only.
// Otherwise (button click) also tell the user what went wrong.
async function startRecording({ silent = false } = {}) {
    const recordingClient = recordingClientOrNull();
    if (!recordingClient || !sessionRecordingEnabled || isRecording || recordingBusy) return;

    if (!recordingClient.canStartRecording()) {
        console.warn('[zoom-meeting] Cloud recording is not available for this session.');
        syncSessionState({ recording: 'unavailable', reason: 'canStartRecording() returned false — cloud recording not enabled for this Video SDK account' });
        if (!silent) showCallNotice(RECORDING_DISABLED_MESSAGE);
        return;
    }

    recordingBusy = true;
    updateRecordButton();
    try {
        await recordingClient.startCloudRecording();
        // The REC badge/button follow 'recording-change', i.e. Zoom's real state.
        syncSessionState({ recording: 'started' });
    } catch (error) {
        console.error('Unable to start cloud recording:', error);
        const code = error?.errorCode ? `errorCode ${error.errorCode}: ` : '';
        syncSessionState({ recording: 'failed', reason: (code + errorReason(error)).slice(0, 255) });
        if (!silent) {
            showCallNotice(error?.errorCode === 7200 ? RECORDING_DISABLED_MESSAGE : `Unable to start recording: ${errorReason(error)}`);
        }
    } finally {
        recordingBusy = false;
        updateRecordButton();
    }
}

async function stopRecording() {
    const recordingClient = recordingClientOrNull();
    if (!recordingClient || !isRecording || recordingBusy) return;

    recordingBusy = true;
    updateRecordButton();
    try {
        await recordingClient.stopCloudRecording();
        showCallNotice('Recording stopped.');
    } catch (error) {
        console.error('Unable to stop cloud recording:', error);
        showCallNotice(`Unable to stop recording: ${errorReason(error)}`);
    } finally {
        recordingBusy = false;
        updateRecordButton();
    }
}

function toggleRecording() {
    return isRecording ? stopRecording() : startRecording();
}

// Runs once connected: show the Record button to the host, reflect a recording
// that's already running, and auto-start if configured.
async function startCloudRecordingIfHost() {
    const recordingClient = recordingClientOrNull();
    if (!recordingClient) return;

    // Participants joining a call that is already being recorded should see it.
    if (recordingClient.getCloudRecordingStatus?.() === 'Recording') {
        isRecording = true;
        setRecordingBadge(true);
        if (sessionRecordingEnabled) syncSessionState({ recording: 'started' });
    }
    updateRecordButton();

    if (sessionRecordingEnabled && sessionRecordingAutoStart && !isRecording) {
        await startRecording({ silent: true });
    }
}

// Joining failed, timed out, or an established call dropped. Keep the panel open
// in its error state (inside the call container) with Retry/Close, instead of
// alerting and closing — the server-side session stays active so Retry rejoins it.
async function failConnection(message, detail) {
    joinAttempt++;
    callState = 'failed';
    clearConnectTimer();
    // Retry rejoins the same session, so keep the start time; the count resumes.
    pauseCallTimer();
    showConnectError(message, detail);
    // Retry re-reads Zoom's real recording state once reconnected.
    isRecording = false;
    recordingBusy = false;
    setRecordingBadge(false);
    updateRecordButton();

    await stopLocalMedia();
    await safeLeave();
    resetCallMedia();
}

function retry() {
    if (callState !== 'failed' || !activeBookingId) return;
    joinSession(activeBookingId);
}

function recipientId() {
    // #ajax-content wraps the active conversation; its inner [data-id] div is
    // the recipient of THIS conversation — reuse it directly.
    return document.querySelector('#ajax-content [data-id]')?.getAttribute('data-id') || null;
}

async function callUser(bookingId) {
    if (outgoingCall) return; // already ringing someone
    if (callState !== 'idle' && callState !== 'failed') {
        showCallNotice('You are already in a video call.');
        return;
    }

    // This click is a user gesture: prime audio for future incoming rings and
    // ask (once) for permission to notify about calls while the tab is hidden.
    unlockRingtone();
    requestCallNotificationPermission();

    const toUserId = recipientId();
    const otherName = document.querySelector('.user-name')?.textContent?.trim();
    // Shown before the request so a fast 'ringing' ack can't arrive first.
    showOutgoing(otherName, bookingId, toUserId);

    try {
        await postJson(`/workspace/zoom/${bookingId}/ring`, { to_user_id: toUserId });
    } catch (error) {
        console.error('Unable to ring:', error);
        hideOutgoing();
        showCallNotice('Unable to start the video call. Please try again.');
        return;
    }

    if (!isOutgoingCall(bookingId)) return; // cancelled while the request was in flight

    ringAckTimeout = setTimeout(() => missCall(bookingId, 'unavailable'), RING_ACK_TIMEOUT_MS);
    ringTimeout = setTimeout(() => missCall(bookingId, 'no-answer'), RING_TIMEOUT_MS);
}

function onRingAcknowledged(bookingId) {
    if (!isOutgoingCall(bookingId)) return;
    if (ringAckTimeout) {
        clearTimeout(ringAckTimeout);
        ringAckTimeout = null;
    }
    setOutgoingStatus('Ringing...');
}

// Nobody answered (or the callee never received the ring). The server records
// it as missed and leaves a "Missed video call" note in the workspace chat.
async function missCall(bookingId, reason) {
    if (!isOutgoingCall(bookingId)) return;
    const name = outgoingCall.name;
    // Stop both timers, but keep outgoingCall set until the server confirms —
    // if the callee answered at this exact moment, 'accepted' must still find it.
    clearTimeout(ringTimeout);
    clearTimeout(ringAckTimeout);
    ringTimeout = null;
    ringAckTimeout = null;

    try {
        await postJson(`/workspace/zoom/${bookingId}/missed`, {});
    } catch (error) {
        // 409 = no longer ringing: the callee answered or declined at the same
        // moment, and that signal will update this UI.
        if (error.status === 409) return;
        console.error('Unable to record missed call:', error);
    }

    if (!isOutgoingCall(bookingId)) return;
    hideOutgoing();
    showCallNotice(reason === 'unavailable' ? `${name} is not available right now.` : `${name} didn't answer.`);
}

async function cancelCall(bookingId) {
    const targetBookingId = bookingId || outgoingCall?.bookingId;
    const toUserId = outgoingCall?.toUserId || outgoingPanel()?.dataset.toUserId;
    hideOutgoing();
    if (!targetBookingId) return;
    try {
        await postJson(`/workspace/zoom/${targetBookingId}/cancel`, { to_user_id: toUserId });
    } catch (error) {
        console.error('Unable to cancel call:', error);
    }
}

async function acceptCall(bookingId) {
    requestCallNotificationPermission();
    hideIncoming();
    await joinSession(bookingId);
}

async function declineCall(bookingId) {
    requestCallNotificationPermission();
    const fromUserId = incomingCall?.fromUserId || incomingPanel()?.dataset.fromUserId;
    hideIncoming();
    try {
        await postJson(`/workspace/zoom/${bookingId}/decline`, { to_user_id: fromUserId });
    } catch (error) {
        console.error('Unable to decline call:', error);
    }
}

function handleCallSignal(payload) {
    const bookingId = payload.booking_id;
    const type = payload.type;
    const fromName = payload.from?.name || 'The other user';
    const fromUserId = payload.from?.id;

    switch (type) {
        case 'ring':
            // Busy in another call: don't ring or acknowledge, so the caller
            // gets "not available" instead of a ring that can't be answered.
            if (callState === 'connecting' || callState === 'connected' || callState === 'reconnecting') return;
            showIncoming(fromName, bookingId, fromUserId);
            break;
        case 'ringing':
            onRingAcknowledged(bookingId);
            break;
        case 'accepted':
            // Only the tab that placed the call joins (the caller's other tabs
            // get this too) — or a tab whose call dropped, when the callee rejoins.
            if (isOutgoingCall(bookingId)) {
                hideOutgoing();
                joinSession(bookingId);
            } else if (callState === 'failed' && String(activeBookingId) === String(bookingId)) {
                joinSession(bookingId);
            }
            break;
        case 'declined':
            if (!isOutgoingCall(bookingId)) return;
            hideOutgoing();
            showCallNotice(`${fromName} declined the call.`);
            break;
        case 'cancelled':
        case 'missed':
            if (!isIncomingCall(bookingId)) return;
            hideIncoming();
            showCallNotice(`Missed video call from ${fromName}.`);
            break;
        case 'handled':
            // Answered/declined in another tab of mine — stop ringing here too.
            if (isIncomingCall(bookingId)) hideIncoming();
            break;
        case 'ended':
            endCallRemotely();
            break;
    }
}

let signalingChannel = null;

function initCallSignaling() {
    if (signalingChannel) return; // never subscribe the same listener twice

    const userId = myUserId();
    if (!userId) {
        console.warn('[zoom-meeting] No user-id meta tag found; ring signaling disabled.');
        return;
    }
    if (!window.Echo) {
        console.warn('[zoom-meeting] window.Echo is not available; ring signaling disabled.');
        return;
    }

    const channelName = `App.Models.User.${userId}`;

    signalingChannel = window.Echo.private(channelName)
        .listen('.video-call-signal', handleCallSignal)
        .error((error) => {
            console.error(`[zoom-meeting] Failed to subscribe to ${channelName}:`, error);
        });

    if (window.Echo.connector?.pusher?.connection) {
        const conn = window.Echo.connector.pusher.connection;
        conn.bind('error', (err) => {
            console.error('[zoom-meeting] Pusher connection error:', err);
        });
    }
}

// An incoming call answered on another page (see call-notifier.js) navigates
// here with ?zoom_answer=<bookingId>; pick it up and join. Removed from the URL
// straight away so a refresh doesn't try to answer the same call again.
function answerCallFromUrl() {
    const url = new URL(window.location.href);
    const bookingId = url.searchParams.get('zoom_answer');
    if (!bookingId) return;
    url.searchParams.delete('zoom_answer');
    window.history.replaceState(window.history.state, '', url.toString());
    acceptCall(bookingId);
}

// Guards against double-cleanup / double-alerts — both our own backend
// broadcast AND the SDK's native user-removed/connection-change events can
// report the same "call is over" fact, sometimes both, so every exit path
// funnels through here and only the first one actually does anything.
let callEndedHandled = false;

// Clears video/share state but leaves the call container (and any error panel)
// on screen — used both on full cleanup and when showing the Retry state.
function resetCallMedia() {
    mainView().innerHTML = '';
    selfView().innerHTML = '';
    videoTiles.clear();
    shareCanvas().style.display = 'none';
    remoteShareCanvas().style.display = 'none';
    sharingUserId = null;
    updateShareLabel(null);
    setShareButtonAvailability(true);
    // Mic and camera start off on every join, so that's the resting state too.
    setButtonState('zoom-toggle-audio', 'off', 'fas fa-microphone-slash');
    setButtonState('zoom-toggle-video', 'off', 'fas fa-video-slash');
    setButtonState('zoom-toggle-screencast', 'default', 'fas fa-tv');
    isSharingScreen = false;
}

function cleanupCallUi() {
    resetCallMedia();
    meetingOverlay().style.display = 'none';
    hideConnectState();
    setRecordingBadge(false);
    clearConnectTimer();
    resetCallTimer();
    callState = 'idle';
    activeBookingId = null;
    sessionRecordingEnabled = false;
    sessionRecordingAutoStart = false;
    isRecording = false;
    recordingBusy = false;
    updateRecordButton();
    currentSessionId = null;
    hideOutgoing();
    hideIncoming();
}

async function stopLocalMedia() {
    if (!stream) return;
    if (isSharingScreen) {
        try {
            await stream.stopShareScreen();
        } catch (error) {
            console.error('Error stopping screen share on cleanup:', error);
        }
    }
    try {
        if (stream.isCapturingVideo?.()) await stream.stopVideo();
    } catch (error) {
        console.error('Error stopping local video:', error);
    }
    try {
        // Muted or not: leave the audio channel and release the microphone.
        if (audioStarted) await stream.stopAudio();
    } catch (error) {
        console.error('Error stopping local audio:', error);
    }
    audioStarted = false;
    stream = null;
}

// I clicked Exit — tell the backend so the OTHER participant gets notified
// over the same private channel used for ring/accept, then leave locally.
async function leave() {
    if (callEndedHandled) return;
    callEndedHandled = true;
    // Abandon any join still in flight (Leave/Close clicked while connecting).
    joinAttempt++;
    clearConnectTimer();

    const sessionId = currentSessionId;
    if (sessionId) {
        try {
            await postJson(`/workspace/zoom/session/${sessionId}/end`, {});
        } catch (error) {
            console.error('Unable to notify server the call ended:', error);
        }
    }

    await stopLocalMedia();
    await safeLeave();
    cleanupCallUi();
}

// The call ended some other way — the other participant clicked Exit (backend
// signal), Zoom detected they disconnected (user-removed/connection-change),
// or the session itself was closed. Clean up without re-notifying the backend.
// Closes quietly: the call panel disappearing is the "call ended" feedback.
async function endCallRemotely() {
    // No call on this page (e.g. a stale 'ended' signal) — nothing to end.
    if (callState === 'idle') return;
    if (callEndedHandled) return;
    callEndedHandled = true;
    joinAttempt++;
    clearConnectTimer();

    await stopLocalMedia();
    await safeLeave();
    cleanupCallUi();
}

document.addEventListener('DOMContentLoaded', () => {
    const soundBtn = soundButton();
    if (soundBtn) {
        // Autoplay fallback: this click is the gesture the browser was waiting for.
        soundBtn.addEventListener('click', async () => {
            if (await unlockRingtone()) soundBtn.style.display = 'none';
        });
    }

    initCallSignaling();
    answerCallFromUrl();
});

// Restored from the back/forward cache: pagehide removed the listener.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) initCallSignaling();
});

// Closing/refreshing the tab mid-call skips our normal leave() button flow —
// fetch() isn't reliable during unload, so use sendBeacon as a best-effort
// notification. Laravel's CSRF check accepts the token from the request body
// (`_token`) as well as the header, which is all sendBeacon lets us set here.
window.addEventListener('beforeunload', () => {
    if (!currentSessionId || callEndedHandled) return;
    const payload = JSON.stringify({ _token: csrfToken() });
    navigator.sendBeacon(
        `/workspace/zoom/session/${currentSessionId}/end`,
        new Blob([payload], { type: 'application/json' }),
    );
});

// Leaving the page: don't leave a callee ringing for a caller who's gone, and
// release everything this page holds for ringing (audio, title flash, timers,
// the Echo listener).
window.addEventListener('pagehide', () => {
    if (outgoingCall) {
        const payload = JSON.stringify({ _token: csrfToken(), to_user_id: outgoingCall.toUserId });
        navigator.sendBeacon(
            `/workspace/zoom/${outgoingCall.bookingId}/cancel`,
            new Blob([payload], { type: 'application/json' }),
        );
        hideOutgoing();
    }
    hideIncoming();
    if (noticeTimer) clearTimeout(noticeTimer);
    clearConnectTimer();
    stopCallTimerInterval();
    if (signalingChannel) {
        signalingChannel.stopListening('.video-call-signal', handleCallSignal);
        signalingChannel = null;
    }
});

window.ZoomWorkspace ={ callUser, acceptCall, declineCall, cancelCall, leave, retry, toggleAudio, toggleVideo, toggleShareScreen, toggleRecording };
