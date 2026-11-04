<?php

namespace App\Http\Controllers\Gateway\Papyal;

use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Exception;
use Illuminate\Support\Facades\Log;

class SellerPayPalController extends Controller
{

   public function payment()
    {
        $withdraw = Withdrawal::with(['method', 'user'])->where('trx', session()->get('wtrx'))
        ->where('status', 0)->orderBy('id', 'desc')->firstOrFail();

        try
        {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));

            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('user.home2'),
                    "cancel_url" => route('user.home2'),
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($withdraw->charge, 2, '.', '')
                        ],
                        "description" => "Withdrawal Transaction #{$withdraw->trx}"
                    ]
                ]
            ]);

            if (isset($response['id']) && !empty($response['id'])) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }

                $notify[] = ['error', 'Unable to process PayPal payment.'];
                return redirect()->route('user.home2')->withNotify($notify);
            }

            $notify[] = ['error', $response['message'] ?? 'Something went wrong with PayPal.'];
            return redirect()->route('user.home2')->withNotify($notify);

        }
        catch (Exception $e)
        {
            Log::error('PayPal Payment Error: ' . $e->getMessage());
            $notify[] = ['error', 'Payment failed: ' . $e->getMessage()];
            return redirect()->route('user.home2')->withNotify($notify);
        }
    }

    public function paymentCancel()
    {
        return redirect()
            ->route('user.home2')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;

        $provider->setApiCredentials(config('paypal'));

        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('user.home2')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('user.home2')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
