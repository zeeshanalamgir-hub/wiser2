<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;

class ZoomWebhookController extends Controller
{
    public function __construct(protected ZoomService $zoomService)
    {
    }

    public function handle(Request $request)
    {
        $event = $request->input('event');

        if ($event === 'endpoint.url_validation') {
            $payload = $request->input('payload');
            $plainToken = is_array($payload) ? ($payload['plainToken'] ?? null) : null;

            if (!$plainToken) {
                return response()->json([
                    'error' => 'Missing plainToken in endpoint.url_validation payload.',
                ], 400);
            }

            return response()->json(
                $this->zoomService->webhookValidationResponse($plainToken)
            );
        }

        return response()->json(['status' => 'ok']);
    }
}
