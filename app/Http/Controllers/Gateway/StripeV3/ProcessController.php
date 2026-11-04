<?php

namespace App\Http\Controllers\Gateway\StripeV3;

use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
class ProcessController extends Controller
{
    public static function process($deposit)
    {
        $StripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $alias = $deposit->gateway->alias;
        $general = GeneralSetting::first();
        \Stripe\Stripe::setApiKey($StripeAcc->secret_key);

        // Create a Price object dynamically if needed
        $priceData = [
            'currency' => $deposit->method_currency,
            'product_data' => [
                'name' => $general->sitename,
                'description' => 'Deposit with Stripe',
                'images' => [asset('assets/images/logoIcon/logo.png')],
            ],
            'unit_amount' => $deposit->final_amo * 100, // Amount in cents
        ];

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => $priceData,
                'quantity' => 1,
            ]],
            // 'cancel_url' => route(gatewayRedirectUrl()),
            // 'success_url' => route(gatewayRedirectUrl(true)) . '?session_id={CHECKOUT_SESSION_ID}&deposit_id=' . $deposit->id,

           'success_url' => route('user.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}&deposit_id=' . $deposit->id,
            'cancel_url'  => route('user.stripe.cancel'),
            'mode' => 'payment',
        ]);

        $send['view'] = 'user.payment.' . $alias;
        $send['session'] = $session;
        $send['StripeJSAcc'] = $StripeAcc;
        $deposit->btc_wallet = json_decode(json_encode($session))->id;
        $deposit->save();

        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $StripeAcc = GatewayCurrency::where('gateway_alias','StripeV3')->orderBy('id','desc')->first();
        $gateway_parameter = json_decode($StripeAcc->gateway_parameter);


        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key);

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $gateway_parameter->end_point; // main
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];


        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $deposit = Deposit::where('btc_wallet',  $session->id)->orderBy('id', 'DESC')->first();

            if($deposit->status==0){
                PaymentController::userDataUpdate($deposit->trx);
            }
        }
        http_response_code(200);
    }

}
