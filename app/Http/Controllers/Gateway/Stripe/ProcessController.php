<?php

namespace App\Http\Controllers\Gateway\Stripe;

use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StripeResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Illuminate\Support\Facades\Session;
use Stripe\PaymentIntent;


class ProcessController extends Controller
{

    /*
     * Stripe Gateway
     */
    public static function process($deposit)
    {
        $alias = $deposit->gateway->alias;

        $send['track'] = $deposit->trx;
        $send['view'] = 'user.payment.'.$alias;
        $send['method'] = 'post';
        $send['url'] = route('ipn.'.$alias);
        $send['stripeAcc'] = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $this->validate($request, [
            'stripeToken' => 'required', // Now we get token from frontend
        ]);

        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();

        if ($deposit->status == 1) {
            $notify[] = ['error', 'Invalid request.'];
            return redirect()->route(gatewayRedirectUrl(true))->withNotify($notify);
        }

        $stripeToken = $request->stripeToken; // 🔹 Get token from form
        $cnts = round($deposit->final_amo, 2) * 100; // Convert to cents
        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        Stripe::setApiKey($stripeAcc->secret_key);

        try {
            $charge = Charge::create([
                'source' => $stripeToken, // 🔹 Use Stripe Token instead of raw card data
                'currency' => $deposit->method_currency,
                'amount' => $cnts,
                'description' => 'Payment for deposit',
            ]);

            if ($charge['status'] == 'succeeded') {
                PaymentController::userDataUpdate($deposit->trx);
                $notify[] = ['success', 'Payment successful.'];
                return redirect()->route(gatewayRedirectUrl(true))->withNotify($notify);

            }
        } catch (\Exception $e) {
            return redirect()->route(gatewayRedirectUrl(true))->withNotify([['error', $e->getMessage()]]);
        }
    }

    public function createStripeAccount()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $account = $stripe->accounts->create([
            'country' => 'IT',
            'email' =>  auth()->user()->email,
            'controller' => [
                'fees' => ['payer' => 'application'],
                'losses' => ['payments' => 'application'],
                'stripe_dashboard' => ['type' => 'express'],
            ]
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $account;
        $stripe_response->type = 'create_account';
        $stripe_response->save();
        return $this->connectStripeAccount($account?->id);
    }

    public function connectStripeAccount($account_id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $accountLink = $stripe->accountLinks->create([
            'account' => $account_id,
            'refresh_url' => env('REFRESH_URL'),
            'return_url' => env('RETURN_URL'),
            'type' => 'account_onboarding',
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $accountLink;
        $stripe_response->type = 'connect_account';
        $stripe_response->save();
        return ['account_id'=>$account_id,'connectAccountLink'=>$accountLink->url];
    }

    public function retrieveStripeAccount()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $account = $stripe->accounts->retrieve('acct_1SExh8ER6XG6BBSi', []);
        dD($account->toArray());
    }

    public function deleteStripeAccount($account_id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $deleted = $stripe->accounts->delete($account_id, []);
        dd($deleted);
    }

    public function listAllStripeAccounts()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $accounts = $stripe->accounts->all(['limit' => 3]);
    }

    // public function checkoutScreen()
    // {
    //     return view('checkout');
    // }

    // public function makePayment()
    // {
    //     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

    //     $session = $stripe->checkout->sessions->create([
    //     'success_url' => 'https://wiser2.test/user/seller/dashboard',
    //         'line_items' => [
    //             [
    //                 'price' => 'price_1SC4HwCWTG6yySfZywm0OB9c',
    //                 'quantity' => 1,
    //             ],
    //         ],
    //         'mode' => 'payment',
    //     ]);
    //     dd($session);
    // }
    public function retrieveAccount()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $account = $stripe->accounts->retrieve('acct_1SD1wmCtwXQtSu39');
        dd(($account->toArray()));
    }

    public function createPrice()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $price = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => 1000,
            'product_data' => ['name' => 'Gold Plan'],
        ]);
        dd($price);
    }

    public function transfer($account_id,$amount)
    {
        // $paymentIntent = $this->createPaymentIntent($account_id,$charge);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $transfer = $stripe->transfers->create([
            'amount' => $amount * 100,
            'currency' => 'eur',
            'destination' => $account_id,
            // 'transfer_group' => $paymentIntent?->response['transfer_group'],
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $transfer;
        $stripe_response->type = 'transfer';
        $stripe_response->save();
        return $stripe_response;
    }

    public function payout($account_id,$amount)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $payout = $stripe->payouts->create(
            [
                'amount' => $amount * 100,
                'currency' => 'eur'
            ],
            ['stripe_account'=>$account_id]
        );
        return $payout;
    }

    public function createPaymentIntent($account_id,$charge)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $charge * 100,
            'currency' => 'eur',
            'transfer_group' => 'ORDER123',
            'automatic_payment_methods' => ['enabled' => true],

        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $paymentIntent;
        $stripe_response->save();
        return $stripe_response;
    }

    public function balance()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $balance = $stripe->balance->retrieve([],['stripe_account' => 'acct_1SD4YAEHeOFK9sT7']);
        dd($balance->toArray());
    }

    public function createCharge($amount)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $charge = $stripe->charges->create([
            'amount' => $amount * 100,
            'currency' => 'eur',
            'source' => 'tok_visa',
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $charge;
        $stripe_response->amount = $charge->amount;
        $stripe_response->type = 'create_charge';
        $stripe_response->save();
        return $stripe_response;
    }

    public function charges()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $ch = $stripe->charges->retrieve(
        'ch_3SCgLDCWTG6yySfZ1c1zJzpO',
        [],
        ['stripe_account' => 'acct_1SC3zXCX0DqqioIc']
        );
        $ch->capture(); // Uses the same account.
        dd($ch);
    }

    public function accountSession()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $accountSession = $stripe->accountSessions->create([
            'account' => 'acct_1SD5KxEDYBFHfrmz',
            'components' => [
                'account_onboarding' => ['enabled' => true],
                'payments' => ['enabled' => true],
                'payouts' => ['enabled' => true],
                'balances' => ['enabled' => true],
            ],
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $accountSession;
        $stripe_response->type = 'account_session';
        $stripe_response->save();
        return true;
    }

    public function loadOnBoarding() {
        return view('index');
    }

    public function accountSessionResponse()
    {
        $stripe = new \Stripe\StripeClient([
            // Don’t submit any personally identifiable information in requests made with this key.
            "api_key" => env('STRIPE_SECRETE_KEY'),
        ]);

        try {
            $account_session = $stripe->accountSessions->create([
            'account' => "acct_1SChQNEMrtkP7iiA",
            'components' => [
                'payments' => [
                'enabled' => true,
                'features' => [
                    'refund_management' => true,
                    'dispute_management' => true,
                    'capture_payments' => true,
                ],
                ],
            ],
            ]);

            echo json_encode(array(
            'client_secret' => $account_session->client_secret
            ));
        } catch (Exception $e) {
            error_log("An error occurred when calling the Stripe API to create an account session: {$e->getMessage()}");
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function onBoarding($account_id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $accountLink = $stripe->accountLinks->create([
            'account' => $account_id,
            'refresh_url' => env('REFRESH_URL'),
            'return_url' => env('RETURN_URL'),
            'type' => 'account_onboarding',
            'collection_options' => ['fields' => 'eventually_due'],
        ]);
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $accountLink;
        $stripe_response->type = 'onBoarding';
        $stripe_response->save();
        return true;
        // dd($accountLink->toArray());
    }

    public function accCapabilities()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $capability = $stripe->accounts->retrieveCapability(
            'acct_1SD4YAEHeOFK9sT7',
            'card_payments',
            []
        );
        dd($capability);
    }

    public function updateAccount()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        $account = $stripe->accounts->update(
        'acct_1SD1wmCtwXQtSu39',
        [
             "individual" => [
                "id" => "person_1SD1xGCtwXQtSu39QEDcxWh9",
                "object" => "person",
                "account" => "acct_1SD1wmCtwXQtSu39",
                "created" => 1759232571,
                "email" => "saqibtestingseller126@mail.com",
                "first_name" => "M",
                "last_name" => "SK",
                "relationship" => [
                    "authorizer" => false,
                    "director" => false,
                    "executive" => false,
                    "legal_guardian" => false,
                    "owner" => false,
                    "percent_ownership" => null,
                    "representative" => true,
                    "title" => null,
                ]
            ]
        ]
        );
        dd($account);
    }


    public function updateCapabilities()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));
        $capability = $stripe->accounts->updateCapability(
            'acct_1SD4YAEHeOFK9sT7',
            'card_payments',
            ['requested' => true]
        );
        dd($capability->toArray());
    }

    public function checkOnboardingStatus($account_id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETE_KEY'));

        // Retrieve the connected account
        $account = $stripe->accounts->retrieve($account_id);

        // Save Stripe response (optional)
        $stripe_response = new StripeResponse();
        $stripe_response->user_id = Auth::user()->id;
        $stripe_response->response = $account;
        $stripe_response->type = 'check_onboarding_status';
        $stripe_response->save();

        // Check if there are still any requirements due
        $requirements_due = $account->requirements->currently_due ?? [];
        $past_due = $account->requirements->past_due ?? [];
        $eventually_due = $account->requirements->eventually_due ?? [];

        // Check capabilities
        $capabilities = $account->capabilities ?? [];

        // Determine status
        $isOnboardingComplete = empty($requirements_due) && empty($past_due);
        return $isOnboardingComplete;

        return response()->json([
            'onboarding_complete' => $isOnboardingComplete,
            'requirements_due' => $requirements_due,
            'past_due' => $past_due,
            'eventually_due' => $eventually_due,
            'capabilities' => $capabilities,
            'details_submitted' => $account->details_submitted,
            'charges_enabled' => $account->charges_enabled,
            'payouts_enabled' => $account->payouts_enabled,
        ]);
    }

}
