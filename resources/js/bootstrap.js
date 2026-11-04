import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
Pusher.logToConsole = true;

// Pick ws/wss at runtime based on how THIS page was actually loaded, rather
// than a fixed build-time value — an https page can't open a plain ws://
// connection (mixed content), so it must go over wss:// through the reverse
// proxy on 443 instead of hitting Reverb's plain port directly.
const isSecurePage = window.location.protocol === 'https:';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.REVERB_APP_KEY,
    wsHost: process.env.REVERB_WS_HOST,
    wsPort: process.env.REVERB_WS_PORT,
    wssPort: isSecurePage ? 443 : process.env.REVERB_WS_PORT,
    forceTLS: isSecurePage,
    // pusher-js's 'ws' strategy is TLS-aware via forceTLS/useTLS — the 'wss'
    // strategy name is only an internal secondary fallback used when forceTLS
    // is false, so it must NOT be swapped in here for secure pages, or the
    // library skips WebSocket entirely and falls straight to HTTP polling.
    enabledTransports: ['ws'],
    encrypted: isSecurePage,
});

