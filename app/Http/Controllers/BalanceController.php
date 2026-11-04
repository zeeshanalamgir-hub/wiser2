<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BalanceController extends Controller
{
    public function convert(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }


            $currency = currency_from_country($user?->latestLastLogin?->country ?? $user->country);
            $balance = $user->balance ?? 0;

            $response = Http::get("https://api.exchangerate.host/convert", [
                'access_key' => config('services.exchangerate.key'),
                'from' => 'USD',
                'to' => $currency,
                'amount' => $balance
            ]);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'API failed'
                ], 500);
            }

            $data = $response->json();
            $cacheKey = 'converted_balance_' . $user->id;

            $rates = $data['query'] ?? [];
            $rate = $rates['to'] ?? null;

            $converted = $data['result'];
            $formatted = $currency . ' ' . number_format($converted, 2);

            session([$cacheKey => $formatted]);

            return response()->json([
                'success' => true,
                'converted_balance' => $formatted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
