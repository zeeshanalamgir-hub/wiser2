{{-- Embedded Zoom Video SDK panel for the workspace video call (inline in the page, not a modal) --}}
<style>
    #zoom-meeting-overlay { box-shadow: 0 4px 20px rgba(0, 63, 69, 0.25); }

    #zoom-meeting-overlay .zoom-header-bar {
        position: absolute; top: 0; left: 0; right: 0; z-index: 1055;
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 15px;
        background: linear-gradient(180deg, rgba(0, 63, 69, 0.65), rgba(0, 63, 69, 0));
    }
    #zoom-meeting-overlay .zoom-header-bar span {
        color: #fff; font-weight: 600; font-size: 14px;
    }

    /* The SDK's <video-player> custom element has no intrinsic size or
       default display of its own (Zoom's docs use exactly this pattern) —
       without it, it renders inline at a small default size instead of
       filling the container, which is why the call looked squeezed. */
    #zoom-main-view video-player, #zoom-self-view video-player {
        display: block;
        width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        background: #111;
    }

    /* Two explicit slots — which peer's video lands in which one is decided
       by SDK userId (see attachParticipant in zoom-meeting.js), never by
       whichever side happened to join/attach first. */
    #zoom-main-view {
        display: block; width: 100%; height: 100%; overflow: auto;
    }
    #zoom-self-view {
        display: block; position: absolute; bottom: 90px; right: 16px;
        width: 25%; max-width: 160px; min-width: 90px;
        border-radius: 8px; overflow: hidden; z-index: 1058;
        box-shadow: 0 2px 10px rgba(0,0,0,.4); border: 2px solid rgba(255,255,255,.6);
    }

    .zoom-video-tile { position: relative; }

    #zoom-meeting-overlay .zoom-header-right { display: flex; align-items: center; gap: 10px; }

    /* Lives in the header bar (z-index 1055, above every video tile), so it never
       covers a participant. Hidden until the call is actually connected. */
    #zoom-call-timer {
        display: none; align-items: center; gap: 6px;
        background: rgba(0, 0, 0, 0.45); backdrop-filter: blur(4px);
        color: #fff; font-size: 13px; font-weight: 600; line-height: 1;
        padding: 6px 10px; border-radius: 14px;
        font-variant-numeric: tabular-nums; /* digits don't jiggle each second */
        letter-spacing: .3px; user-select: none;
    }
    #zoom-call-timer.is-visible { display: inline-flex; }
    #zoom-call-timer::before {
        content: ''; width: 7px; height: 7px; border-radius: 50%; background: #0CB48C;
    }
    /* Paused while reconnecting / after the connection drops. */
    #zoom-call-timer.is-paused { color: rgba(255, 255, 255, 0.7); }
    #zoom-call-timer.is-paused::before { background: #f0ad4e; }
    @media (max-width: 576px) {
        #zoom-call-timer { font-size: 11px; padding: 5px 8px; }
        #zoom-meeting-overlay .zoom-header-right { gap: 6px; }
    }

    #zoom-meeting-leave {
        width: 42px; height: 42px; border: none; border-radius: 50%;
        background-color: #ea5455; color: #fff; font-size: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,.35);
        transition: background-color .2s ease;
    }
    #zoom-meeting-leave:hover { background-color: #d33a3b; color: #fff; }

    #zoom-meeting-controls {
        background: rgba(0, 63, 69, 0.55);
        backdrop-filter: blur(4px);
        padding: 10px 18px;
        border-radius: 50px;
    }

    .zoom-ctrl-btn {
        width: 48px; height: 48px; border: none; border-radius: 50%;
        background-color: #037e89; color: #fff; font-size: 17px; padding: 0;
        display: flex; align-items: center; justify-content: center;
        transition: background-color .2s ease, transform .15s ease;
    }
    .zoom-ctrl-btn i { line-height: 1; }
    .zoom-ctrl-btn:hover { background-color: #003f45; color: #fff; transform: translateY(-2px); }
    .zoom-ctrl-btn.zoom-ctrl-off { background-color: #ea5455; }
    .zoom-ctrl-btn.zoom-ctrl-off:hover { background-color: #d33a3b; }
    .zoom-ctrl-btn.zoom-ctrl-active { background-color: #0CB48C; }
    .zoom-ctrl-btn.zoom-ctrl-active:hover { background-color: #099b77; }
    .zoom-ctrl-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }
    .zoom-ctrl-btn:disabled:hover { background-color: #037e89; transform: none; }

    #zoom-share-label {
        display: none; position: absolute; top: 50px; left: 50%; transform: translateX(-50%);
        z-index: 1059; background: rgba(0, 0, 0, .65); color: #fff;
        font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;
        pointer-events: none;
    }

    /* Connecting / reconnecting / error state — scoped to the call container
       (position:absolute inside #zoom-meeting-overlay), never full-page. Sits
       above the video tiles but below the header bar so Leave stays usable. */
    #zoom-connect-state {
        display: none; position: absolute; inset: 0; z-index: 1054;
        flex-direction: column; align-items: center; justify-content: center; gap: 14px;
        background: rgba(0, 31, 34, 0.92); color: #fff; text-align: center; padding: 20px;
    }
    #zoom-connect-state.is-visible { display: flex; }
    #zoom-meeting-overlay.zoom-not-connected #zoom-meeting-controls,
    #zoom-meeting-overlay.zoom-not-connected #zoom-self-view,
    #zoom-meeting-overlay.zoom-not-connected #zoom-share-label { visibility: hidden; }
    #zoom-connect-state .zoom-spinner {
        width: 48px; height: 48px; border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.2); border-top-color: #0CB48C;
        animation: zoom-spin .9s linear infinite;
    }
    @keyframes zoom-spin { to { transform: rotate(360deg); } }
    #zoom-connect-state .zoom-connect-icon { display: none; font-size: 40px; color: #ea5455; }
    #zoom-connect-state .zoom-connect-message { margin: 0; font-size: 15px; font-weight: 600; }
    #zoom-connect-state .zoom-connect-detail { margin: 0; font-size: 12px; color: rgba(255, 255, 255, 0.7); max-width: 420px; }
    #zoom-connect-state .zoom-connect-actions { display: none; gap: 10px; }
    #zoom-connect-state .zoom-connect-actions button {
        border: none; border-radius: 20px; padding: 8px 20px; font-size: 13px; font-weight: 600; color: #fff;
    }
    #zoom-connect-retry { background-color: #0CB48C; }
    #zoom-connect-retry:hover { background-color: #099b77; }
    #zoom-connect-close { background-color: #6c757d; }
    #zoom-connect-close:hover { background-color: #5a6268; }
    #zoom-connect-state.is-error .zoom-spinner { display: none; }
    #zoom-connect-state.is-error .zoom-connect-icon { display: block; }
    #zoom-connect-state.is-error .zoom-connect-actions { display: flex; }

    #zoom-recording-badge {
        display: none; align-items: center; gap: 6px; margin-left: 10px;
        background: rgba(234, 84, 85, 0.9); color: #fff; font-size: 11px; font-weight: 700;
        padding: 3px 10px; border-radius: 12px;
    }
    #zoom-recording-badge::before {
        content: ''; width: 8px; height: 8px; border-radius: 50%; background: #fff;
        animation: zoom-rec-blink 1.2s infinite;
    }
    @keyframes zoom-rec-blink { 50% { opacity: .3; } }

    .zoom-call-toast {
        display: none; align-items: center; gap: 14px;
        position: fixed; top: 20px; right: 20px; z-index: 1070;
        background: #fff; border-radius: 12px; padding: 14px 18px;
        box-shadow: 0 8px 24px rgba(0, 63, 69, 0.25);
        min-width: 260px;
        animation: zoom-call-pop .25s ease;
    }
    @keyframes zoom-call-pop {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .zoom-call-toast .zoom-call-icon {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background-color: #037e89; color: #fff; font-size: 18px; flex-shrink: 0;
    }
    .zoom-call-toast .zoom-call-body { flex: 1; min-width: 0; }
    .zoom-call-toast .zoom-call-title { font-weight: 700; color: #003f45; font-size: 14px; margin: 0; }
    .zoom-call-toast .zoom-call-sub { font-size: 12px; color: #8c8c8c; margin: 0; }
    .zoom-call-toast .zoom-call-actions { display: flex; gap: 8px; }
    .zoom-call-toast .zoom-call-btn {
        border: none; border-radius: 50%; width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center; color: #fff;
        transition: opacity .15s ease;
    }
    .zoom-call-toast .zoom-call-btn:hover { opacity: .85; }
    .zoom-call-toast .zoom-call-sound-btn {
        margin-top: 6px; border: none; background: #e9f7f4; color: #037e89;
        font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 12px;
    }
    #zoom-outgoing-call .zoom-call-icon,
    #zoom-incoming-call .zoom-call-icon { animation: zoom-ring-pulse 1.2s infinite; }
    @keyframes zoom-ring-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(3, 126, 137, 0.5); }
        50% { box-shadow: 0 0 0 8px rgba(3, 126, 137, 0); }
    }
</style>

<div id="zoom-outgoing-call" class="zoom-call-toast" role="status" aria-live="polite">
    <div class="zoom-call-icon"><i class="fas fa-phone-volume"></i></div>
    <div class="zoom-call-body">
        <p class="zoom-call-title"><span id="zoom-outgoing-name"></span></p>
        <p class="zoom-call-sub" id="zoom-outgoing-status">@lang('Calling...')</p>
    </div>
    <div class="zoom-call-actions">
        <button type="button" id="zoom-cancel-call" class="zoom-call-btn" style="background-color:#ea5455;" title="@lang('Cancel')">
            <i class="fas fa-phone-slash"></i>
        </button>
    </div>
</div>

<div id="zoom-incoming-call" class="zoom-call-toast" role="alertdialog" aria-labelledby="zoom-incoming-name">
    <div class="zoom-call-icon"><i class="fas fa-phone"></i></div>
    <div class="zoom-call-body">
        <p class="zoom-call-title"><span id="zoom-incoming-name"></span></p>
        <p class="zoom-call-sub">@lang('Incoming video call...')</p>
        {{-- Shown only if the browser blocked the ringtone (no prior click on the page). --}}
        <button type="button" id="zoom-enable-sound" class="zoom-call-sound-btn" style="display:none;">
            <i class="fas fa-volume-up"></i> @lang('Tap to hear ringtone')
        </button>
    </div>
    <div class="zoom-call-actions">
        <button type="button" id="zoom-accept-call" class="zoom-call-btn" style="background-color:#0CB48C;" title="@lang('Answer')" aria-label="@lang('Answer')">
            <i class="fas fa-phone"></i>
        </button>
        <button type="button" id="zoom-decline-call" class="zoom-call-btn" style="background-color:#ea5455;" title="@lang('Decline')" aria-label="@lang('Decline')">
            <i class="fas fa-phone-slash"></i>
        </button>
    </div>
</div>

{{-- Short-lived status for both sides (declined, no answer, missed call...), instead of alert(). --}}
<div id="zoom-call-notice" class="zoom-call-toast" role="status" aria-live="polite">
    <div class="zoom-call-icon" style="background-color:#6c757d;"><i class="fas fa-phone-slash"></i></div>
    <div class="zoom-call-body">
        <p class="zoom-call-title" id="zoom-call-notice-text"></p>
    </div>
</div>

<div id="zoom-meeting-overlay" style="display:none; position:relative; width:100%; height:500px; background:#000; border-radius:8px; overflow:hidden; margin-top:15px;">
    <div class="zoom-header-bar">
        <span style="display:flex; align-items:center;">
            @lang('Video Call')
            <span id="zoom-recording-badge">@lang('REC')</span>
        </span>
        <div class="zoom-header-right">
            {{-- Elapsed call time; shown and driven by zoom-meeting.js once connected. --}}
            <span id="zoom-call-timer" role="timer" aria-label="@lang('Call duration')">00:00:00</span>
            <a href="#" id="zoom-meeting-leave" title="@lang('Leave call')">
                <i class="fas fa-phone-slash"></i>
            </a>
        </div>
    </div>

    <video-player-container id="zoom-main-view"></video-player-container>
    <video-player-container id="zoom-self-view"></video-player-container>
    {{-- <video>, not <canvas> — the SDK rejects a canvas for share render/preview
         when WebCodecs is enabled ("Use Video element instead of Canvas element
         when WebCodecs enabled"). muted+playsinline avoid autoplay being blocked. --}}
    {{-- Two different elements, not one shared: startShareScreen() (my own
         local preview when I'm sharing) accepts a <video> OR <canvas>, but
         startShareView() (rendering someone ELSE's shared screen) accepts
         ONLY a <canvas> per the SDK's own type declarations. Reusing one
         element for both breaks whichever call doesn't match its type. --}}
    <video id="zoom-screencast-canvas" autoplay playsinline muted style="display:none; position:absolute; inset:0; width:100%; height:100%; background:#000; object-fit:contain;"></video>
    <canvas id="zoom-remote-share-canvas" style="display:none; position:absolute; inset:0; width:100%; height:100%; background:#000;"></canvas>
    <div id="zoom-share-label"></div>

    <div id="zoom-connect-state" role="status" aria-live="polite">
        <div class="zoom-spinner" aria-hidden="true"></div>
        <i class="fas fa-exclamation-triangle zoom-connect-icon" aria-hidden="true"></i>
        <p class="zoom-connect-message">@lang('Connecting ..')</p>
        <p class="zoom-connect-detail"></p>
        <div class="zoom-connect-actions">
            <button type="button" id="zoom-connect-retry">@lang('Retry')</button>
            <button type="button" id="zoom-connect-close">@lang('Close')</button>
        </div>
    </div>

    <div id="zoom-meeting-controls" style="position:absolute; bottom:20px; left:50%; transform:translateX(-50%); z-index:1060; display:flex; gap:14px;">
        <button type="button" id="zoom-toggle-audio" class="zoom-ctrl-btn" title="@lang('Mute/Unmute')">
            <i class="fas fa-microphone"></i>
        </button>
        <button type="button" id="zoom-toggle-video" class="zoom-ctrl-btn" title="@lang('Camera On/Off')">
            <i class="fas fa-video"></i>
        </button>
        <button type="button" id="zoom-toggle-screencast" class="zoom-ctrl-btn" title="@lang('Share Screen')">
            <i class="fas fa-tv"></i>
        </button>
        {{-- Host only; shown by zoom-meeting.js when cloud recording is enabled. --}}
        {{-- <button type="button" id="zoom-toggle-record" class="zoom-ctrl-btn" title="@lang('Start recording')" style="display:none;">
            <i class="fas fa-record-vinyl"></i>
        </button> --}}
    </div>
</div>


<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.zoom-start-call-btn');
        if (!btn) return;
        e.preventDefault();
        const bookingId = btn.getAttribute('data-booking_id');
        if (bookingId && window.ZoomWorkspace) {
            window.ZoomWorkspace.callUser(bookingId);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const leaveBtn = document.getElementById('zoom-meeting-leave');
        if (leaveBtn) {
            leaveBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.leave();
                }
            });
        }

        const cancelCallBtn = document.getElementById('zoom-cancel-call');
        if (cancelCallBtn) {
            cancelCallBtn.addEventListener('click', function () {
                const bookingId = document.getElementById('zoom-outgoing-call')?.dataset.bookingId;
                if (bookingId && window.ZoomWorkspace) {
                    window.ZoomWorkspace.cancelCall(bookingId);
                }
            });
        }

        const acceptCallBtn = document.getElementById('zoom-accept-call');
        if (acceptCallBtn) {
            acceptCallBtn.addEventListener('click', function () {
                const bookingId = document.getElementById('zoom-incoming-call')?.dataset.bookingId;
                if (bookingId && window.ZoomWorkspace) {
                    window.ZoomWorkspace.acceptCall(bookingId);
                }
            });
        }

        const declineCallBtn = document.getElementById('zoom-decline-call');
        if (declineCallBtn) {
            declineCallBtn.addEventListener('click', function () {
                const bookingId = document.getElementById('zoom-incoming-call')?.dataset.bookingId;
                if (bookingId && window.ZoomWorkspace) {
                    window.ZoomWorkspace.declineCall(bookingId);
                }
            });
        }

        const audioBtn = document.getElementById('zoom-toggle-audio');
        if (audioBtn) {
            audioBtn.addEventListener('click', function () {
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.toggleAudio();
                }
            });
        }

        const videoBtn = document.getElementById('zoom-toggle-video');
        if (videoBtn) {
            videoBtn.addEventListener('click', function () {
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.toggleVideo();
                }
            });
        }

        const shareBtn = document.getElementById('zoom-toggle-screencast');
        if (shareBtn) {
            shareBtn.addEventListener('click', function () {
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.toggleShareScreen();
                }
            });
        }

        // const recordBtn = document.getElementById('zoom-toggle-record');
        // if (recordBtn) {
        //     recordBtn.addEventListener('click', function () {
        //         if (window.ZoomWorkspace) {
        //             window.ZoomWorkspace.toggleRecording();
        //         }
        //     });
        // }

        const retryBtn = document.getElementById('zoom-connect-retry');
        if (retryBtn) {
            retryBtn.addEventListener('click', function () {
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.retry();
                }
            });
        }

        const closeBtn = document.getElementById('zoom-connect-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                if (window.ZoomWorkspace) {
                    window.ZoomWorkspace.leave();
                }
            });
        }
    });
</script>
