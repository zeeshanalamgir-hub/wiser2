<?php

return [
    /*
    |-------------------------------------
    | Video SDK
    | Used server-side to sign the JWT the browser SDK needs to join a session.
    |-------------------------------------
    */
    'sdk_key' => env('ZOOM_SDK_KEY'),
    'sdk_secret' => env('ZOOM_SDK_SECRET'),

    /*
    |-------------------------------------
    | Video SDK REST API
    | The Video SDK account's *API* Key/Secret (Zoom Video SDK portal →
    | Build → Keys & Secrets → "API Key"), which is a different pair from the
    | SDK Key/Secret above. Used only server-side to read cloud recordings.
    |-------------------------------------
    */
    'api_key' => env('ZOOM_API_KEY'),
    'api_secret' => env('ZOOM_API_SECRET'),
    'api_base_url' => env('ZOOM_API_BASE_URL', 'https://api.zoom.us/v2'),

    /*
    |-------------------------------------
    | Webhooks
    | "Secret Token" from the Video SDK app's Event Subscription. Used to verify
    | x-zm-signature and to answer endpoint.url_validation challenges.
    | ZOOM_SECRETE_TOKEN is the older (misspelled) key already in .env.
    |-------------------------------------
    */
    // `?:` rather than env()'s default: an empty "ZOOM_WEBHOOK_SECRET_TOKEN=" line
    // must still fall back, or every webhook fails signature verification.
    'webhook_secret_token' => env('ZOOM_WEBHOOK_SECRET_TOKEN') ?: env('ZOOM_SECRETE_TOKEN'),
    // Reject webhook requests whose timestamp is older than this (replay protection).
    'webhook_tolerance_seconds' => (int) env('ZOOM_WEBHOOK_TOLERANCE_SECONDS', 300),

    /*
    |-------------------------------------
    | Cloud recording
    | When enabled, the host's browser starts cloud recording once connected.
    | Requires cloud recording to be enabled on the Video SDK account (paid add-on).
    |-------------------------------------
    */
    'cloud_recording' => [
        'enabled' => (bool) env('ZOOM_CLOUD_RECORDING_ENABLED', false),
        // true = start recording automatically when the host connects; false =
        // only when the host clicks the Record button.
        'auto_start' => (bool) env('ZOOM_CLOUD_RECORDING_AUTO_START', true),
        // How long after a session ends to keep waiting for Zoom to finish
        // processing before giving up (marked "none" or "failed").
        'deadline_hours' => (int) env('ZOOM_RECORDING_DEADLINE_HOURS', 24),
    ],
];
