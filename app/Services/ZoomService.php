<?php

namespace App\Services;

use App\Exceptions\ZoomApiException;
use Firebase\JWT\JWT;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Generate the Video SDK JWT signature required to join a session.
     *
     * @param string $sessionName
     * @param int $role 1 = host, 0 = participant
     * @return string
     */
    public function generateSignature(string $sessionName, int $role)
    {
        $sdkKey = config('zoom.sdk_key');
        $iat = time() - 30;
        $exp = $iat + 60 * 60 * 2;

        $payload = [
            'app_key' => $sdkKey,
            'tpc' => $sessionName,
            'role_type' => $role,
            'version' => 1,
            'iat' => $iat,
            'exp' => $exp,
        ];

        return JWT::encode($payload, config('zoom.sdk_secret'), 'HS256');
    }

    /**
     * Short-lived JWT for the Video SDK REST API. Generated per request instead of
     * cached, so an expired token can never be reused and nothing is persisted.
     */
    protected function apiToken(): string
    {
        $apiKey = config('zoom.api_key');
        $apiSecret = config('zoom.api_secret');

        if (!$apiKey || !$apiSecret) {
            throw new ZoomApiException('Zoom Video SDK API credentials (ZOOM_API_KEY / ZOOM_API_SECRET) are not configured.');
        }

        $iat = time() - 30;

        return JWT::encode([
            'iss' => $apiKey,
            'iat' => $iat,
            'exp' => $iat + 60 * 30,
        ], $apiSecret, 'HS256');
    }

    /**
     * Zoom requires a session UUID that starts with "/" or contains "//" to be
     * double URL-encoded when used as a path parameter.
     */
    protected function encodeSessionUuid(string $uuid): string
    {
        if (str_starts_with($uuid, '/') || str_contains($uuid, '//')) {
            return rawurlencode(rawurlencode($uuid));
        }

        return rawurlencode($uuid);
    }

    /**
     * Fetch cloud recording info for one Video SDK session.
     *
     * @return array|null The recording payload, or null when Zoom has no recording for this session.
     *
     * @throws ZoomApiException On rate limiting, server errors, bad credentials or network failures.
     */
    public function getSessionRecordings(string $sessionUuid): ?array
    {
        $path = '/videosdk/sessions/' . $this->encodeSessionUuid($sessionUuid) . '/recordings';

        $response = $this->get($path);

        // A 401 can happen if the clock drifted or the secret was rotated; try once
        // more with a freshly signed token before treating it as a hard failure.
        if ($response->status() === 401) {
            $response = $this->get($path);
        }

        // 404 = Zoom has no recording for this session (never recorded, still
        // processing, or deleted). The caller decides which based on timing.
        if ($response->status() === 404) {
            return null;
        }

        if ($response->failed()) {
            Log::warning('Zoom API request failed', [
                'path' => '/videosdk/sessions/{uuid}/recordings',
                'session_uuid' => $sessionUuid,
                'status' => $response->status(),
                'zoom_code' => $response->json('code'),
                'zoom_message' => $response->json('message'),
            ]);

            throw new ZoomApiException(
                'Zoom API error: ' . ($response->json('message') ?? 'HTTP ' . $response->status()),
                $response->status(),
                $response->status() === 429 ? (int) ($response->header('Retry-After') ?: 60) : null,
            );
        }

        return $response->json();
    }

    protected function get(string $path): Response
    {
        try {
            return Http::withToken($this->apiToken())
                ->acceptJson()
                ->timeout(15)
                ->get(rtrim(config('zoom.api_base_url'), '/') . $path);
        } catch (ConnectionException $e) {
            throw new ZoomApiException('Could not reach the Zoom API: ' . $e->getMessage());
        }
    }

    /**
     * Open a streaming download of a Zoom cloud recording file. Zoom's
     * download_url needs an API token; it is added here, server-side, so it never
     * reaches the browser (or the logs).
     *
     * @throws ZoomApiException
     */
    public function openRecordingStream(string $downloadUrl): Response
    {
        try {
            $response = Http::withOptions(['stream' => true])
                ->connectTimeout(15)
                ->timeout(0) // large files: no total time limit once connected
                ->get($downloadUrl, ['access_token' => $this->apiToken()]);
        } catch (ConnectionException $e) {
            throw new ZoomApiException('Could not reach Zoom to download the recording.');
        }

        if ($response->failed()) {
            Log::warning('Zoom recording download failed', ['status' => $response->status()]);
            throw new ZoomApiException('Zoom returned HTTP ' . $response->status() . ' for the recording download.', $response->status());
        }

        return $response;
    }

    /**
     * Verify a webhook's x-zm-signature header:
     * "v0=" . HMAC_SHA256(secretToken, "v0:{x-zm-request-timestamp}:{raw body}").
     */
    public function verifyWebhookSignature(string $rawBody, ?string $signature, ?string $timestamp): bool
    {
        $secret = config('zoom.webhook_secret_token');

        if (!$secret || !$signature || !$timestamp || !ctype_digit($timestamp)) {
            return false;
        }

        // Replay protection — Zoom sends the timestamp in seconds.
        if (abs(time() - (int) $timestamp) > config('zoom.webhook_tolerance_seconds', 300)) {
            return false;
        }

        $expected = 'v0=' . hash_hmac('sha256', "v0:{$timestamp}:{$rawBody}", $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * Response body for Zoom's endpoint.url_validation challenge.
     */
    public function webhookValidationResponse(string $plainToken): array
    {
        return [
            'plainToken' => $plainToken,
            'encryptedToken' => hash_hmac('sha256', $plainToken, config('zoom.webhook_secret_token')),
        ];
    }
}
