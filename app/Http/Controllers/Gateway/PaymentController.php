<?php

namespace App\Http\Controllers\Gateway;

use Session;
use App\Models\User;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\PaymentSession;
use App\Models\ServicesDetail;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use App\Models\Additionalservice;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Employermembershiptransactions;
use App\Models\Freelancermembershiptransactions;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Deposite Money'],
        ];
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'breadcrumbs'));
    }

    public function deposittwo( Request $request )
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        return view($this->activeTemplate . 'user.payment.deposittwo', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|gt:0',
                'method_code' => 'required',
                'currency' => 'required',
            ]);
            $user = auth()->user();
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })
                ->where('method_code', $request->method_code)
                ->where('currency', $request->currency)->first();
            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

            // if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            //     $notify[] = ['error', 'Please follow deposit limit'];
            //     return back()->withNotify($notify);
            // }

            $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
            $payable = $request->amount + $charge;
            $final_amo = $payable * $gate->rate;

            $data = new Deposit();
            $data->user_id = $user->id;
            $data->method_code = $gate->method_code;
            $data->method_currency = strtoupper($gate->currency);
            $data->amount = $request->amount;
            $data->charge = $charge;
            $data->rate = $gate->rate;
            $data->final_amo = $final_amo;
            $data->btc_amo = 0;
            $data->btc_wallet = "";
            $data->trx = getTrx();
            $data->try = 0;
            $data->status = 0;
            $data->save();
            session()->put('Track', $data->trx);
            return redirect()->route('user.deposit.preview');
        } catch (\Exception $e) {
            \Log::error('Deposit Insert Error: ' . $e->getMessage());
            $notify[] = ['error', 'An error occurred while processing your deposit. Please try again later.'];
            return back()->withNotify($notify);
        }
    }
    public function depositInserttwo(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        // if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
        //     $notify[] = ['error', 'Please follow deposit limit'];
        //     return back()->withNotify($notify);
        // }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return redirect()->route('user.deposit.previewtwo');
    }

    public function payment(Request $request)
    {
        \Log::info('Stripe Payment Initiated', [
            'request_data' => $request->all(),
            'user_id' => auth()->id()
        ]);

        $decryptedServiceDetailId = decrypt($request->service_detail_id);
        $request->merge(['service_detail_id' => $decryptedServiceDetailId]);

        $request->validate([
            'converted_amount' => 'required|numeric|gt:0',
            'service_detail_id' => 'required|integer|exists:services_details,id',
        ]);

        $user = auth()->user();
        $methodCode = '114';
        $currency = "USD";

        $gateway = GatewayCurrency::whereHas('method', function ($query) {
            $query->where('status', 1);
        })->where('method_code', $methodCode)->where('currency', $currency)->first();

        if (!$gateway) {
            return back()->with('error', 'Invalid gateway selected');
        }
        // Fetch the fixed and percentage charges from the gateway data
        $fixedCharge = (float) $gateway->fixed_charge; // Ensure this is a float
        $percentCharge = (float) $gateway->percent_charge; // Ensure this is a float

        $stripeConfig = json_decode($gateway->gateway_parameter);
        $general = GeneralSetting::first();

        \Stripe\Stripe::setApiKey($stripeConfig->secret_key);

        $serviceDetail = ServicesDetail::where('is_active',1)->findOrFail($request->service_detail_id);

        $extra_services_data = $request->input('extra_services_data', []);
        $qty = $request->input('serviceQuantity', 0);

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $user->id)->first();
        $platformFee = $usermembership?->service_platform_fee ?? 0; // platform fee

        $freelancer = User::where('id', $serviceDetail->user_id)->first();
        $freelancerMembership = Freelancermembershiptransactions::join(
            'freelancer_membership',
            'freelancer_membership_transactions.membership_id',
            '=',
            'freelancer_membership.id'
        )->where('user_id', $freelancer->id)->first();
        $freelancerService = $freelancerMembership->service_cost;

        try {
            $addonIds = json_decode($request->addon_ids[0] ?? '[]', true);

            $addonsFromDb = DB::table('user_services_adds_on as a')
                ->join('service_tiers as t', 'a.services_addson_id', '=', 't.id')
                ->whereIn('a.id', $addonIds)
                ->select('a.id', 't.name as title', 'a.price')
                ->get();


            $addonTitles = $addonsFromDb->pluck('title')->toArray();
            $addonPrices = $addonsFromDb->pluck('price')->map(fn($p) => (float) $p)->toArray();

            $servicePrice = $serviceDetail->price;
            $addonTotal = array_sum($addonPrices);
            $discount = floatval($request->discount ?? 0);

            $basePrice = $servicePrice + $addonTotal;
            if ($discount > $basePrice)
                $discount = $basePrice;

            $packagePrice = $basePrice - $discount;
            $paymentFee = $fixedCharge + ($packagePrice * $percentCharge / 100);
            $subTotal = $packagePrice + $paymentFee;
            $platformFeeAmount = ($platformFee / 100) * $packagePrice;
            $total = $subTotal + $platformFeeAmount;

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $user->email,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($gateway->currency),
                            'unit_amount' => $request->converted_amount * 100, // cents
                            'product_data' => [
                                'name' => $general->sitename,
                                'description' => 'Payment via Stripe',
                                'images' => [asset('assets/images/logoIcon/logo.png')],
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('user.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.payment.cancel'),
            ]);

            //Save session details
            PaymentSession::create(attributes: [
                'session_id' => $session->id,
                'buyer_id' => $user->id,
                'gateway_id' => $gateway->id,
                'amount' => $subTotal,
                'qty' => $qty,
                'payable_type' => "service",
                'payable_id' => $decryptedServiceDetailId,
                'metadata' => json_encode([
                    'ip' => $request->ip(),
                    'qty' => $qty ?? 1,
                    'extra_services_data' => $extra_services_data,
                    'user_agent' => $request->userAgent(),
                    // Booking-specific metadata
                    'discount' => $request->discount ?? 0,
                    'addons' => $addonIds,
                    'addon_titles' => $addonTitles,
                    'addon_prices' => $addonPrices,
                    'buyer_service_fee' => $platformFee,
                    'seller_platform_fee' => $freelancerService,
                    'converted_amount' => $request->converted_amount,
                    'package_price' => $servicePrice ?? 0,
                    'service_id' => $serviceDetail->service_id ?? null,
                    // 'othersymbol' => $request->othersymbol ?? 'USD',
                    'payment_processing_fee' => $paymentFee,
                    'total' => $total
                ]),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Failed', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $paymentSession = PaymentSession::where('session_id', $sessionId)->first();

        if (!$paymentSession) {
            return redirect()->route('home')->with('error', 'Invalid payment session.');
        }

        // Perform your logic based on payable_type
        switch ($paymentSession->payable_type) {
            case 'service':
                $user = auth()->user();
                $metadata = json_decode($paymentSession->metadata, true);

                $serviceDetail = ServicesDetail::where('is_active',1)->findOrFail($paymentSession->payable_id);
                $serviceId = $metadata['service_id'];
                $service = Service::findOrFail($serviceId);

                // Safe defaults
                $addonPrices = array_map('floatval', $metadata['addon_prices'] ?? []);
                $totalAddonPrice = array_sum($addonPrices);
                $discount = floatval($metadata['discount'] ?? 0);
                // $conversionRate = floatval($metadata['currentrate'] ?? 1);

                $booking = new Booking();
                $booking->buyer_id = $user->id;
                $booking->service_id = $serviceId;
                $booking->service_detail_id = $serviceDetail->id;
                $booking->qty = intval($metadata['qty'] ?? 1);
                $booking->amount = $paymentSession->amount;
                $booking->agreed_amount = $metadata['total'];
                $booking->discount = $discount;
                $booking->service_revisions = $serviceDetail->revision;
                $booking->service_days = $serviceDetail->delivery_time;
                $booking->service_package = $serviceDetail->id;
                $booking->service_price = floatval($metadata['package_price']);
                $booking->otherprice = $totalAddonPrice;
                //$booking->othersymbol = $metadata['payment_processing_fee'] ?? 'USD';
                $booking->extra_service = $metadata['addon_prices'] ?? 'USD';
                //$booking->currentrate = $conversionRate;
                $booking->otheramount = floatval($metadata['payment_processing_fee']);
                $booking->seller_platform_fee = floatval($metadata['seller_platform_fee'] ?? 0);
                $booking->buyer_service_fee = floatval($metadata['buyer_service_fee'] ?? 0);
                $booking->order_number = getTrx();
                $booking->status = 3;
                $booking->working_status = 0;
                $booking->seller_id = $service->user_id;
                $booking->user_extra_services = $metadata['extra_services_data'] ?? [];
                $booking->booking_type = $paymentSession->payable_type;
                $booking->payment_session_id = $paymentSession->id;
                $booking->updated_at = now();
                $booking->status_updated_at = now();
                $booking->save();

                // ✅ Save add-ons if available
                if (!empty($metadata['addons']) && is_array($metadata['addons'])) {
                    foreach ($metadata['addons'] as $index => $addonId) {
                        $add_service = new Additionalservice();
                        $add_service->service_id = $addonId;
                        $add_service->booking_id = $booking->id;
                        $add_service->title = $metadata['addon_titles'][$index] ?? 'Addon';
                        $add_service->amount = $addonPrices[$index] ?? 0;
                        //$add_service->othersymbol = $metadata['othersymbol'];
                        $add_service->save();
                    }
                }

                // ✅ Record buyer-side transaction (no seller balance change yet)
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->to_user = null; // Stripe holds the money
                $transaction->amount = $booking->amount;
                $transaction->post_balance = $user->balance; // Optional: log current balance
                $transaction->trx_type = '-';
                $transaction->trx = $booking->order_number;
                $transaction->details = "Service booking via Stripe";
                $transaction->save();
                break;

            case 'deposit':
                // Handle deposit logic
                break;

            case 'package':
                // Handle package logic
                break;
        }

        return redirect()->route('user.buyer.service.booked')->with('success', 'Payment successful!');
    }


    public function depositPreview(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $track = session()->get('Track');
        $data = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->first();
        $pageTitle = 'Payment Preview';
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Checkout'],
        ];
        return view($this->activeTemplate . 'user.payment.preview', compact('data', 'pageTitle', 'breadcrumbs'));
    }
    public function depositPreviewtwo(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $track = session()->get('Track');
        $data = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle = 'Payment Preview';
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Checkout'],
        ];
        return view($this->activeTemplate . 'user.payment.previewtwo', compact('data', 'breadcrumbs', 'pageTitle'));
    }

    public function depositConfirm(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            //if deposite is manual
            $user = Auth::user();
            $user->is_payment_verified = 1;
            $user->save();

            $this->userDataUpdate($deposit);
            $notify[] = ['success', 'Your deposit request is queued for approval.'];
            return redirect()->route('user.deposittwo')->withNotify($notify);
        }

        //new code currently deposit status is 0 for pending 1 for completed (approved)
        $deposit->status = 1;
        $deposit->save();

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';
        
        $data = $new::process($deposit);
        $data = json_decode($data);

        $user = Auth::user();
        $user->is_payment_verified = 1;
        $user->save();

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $session = $data->session;
        // if(Auth::user()->is_freelancer == 1 || Auth::user()->is_freelancer == '1')
        // {
        return redirect($session->url);
        // }

        // return response()->json([
        //     'url' => $session->url,
        // ]);
        // $pageTitle = 'Payment Confirm';
        // return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($trx)
    {
        $general = GeneralSetting::first();
        $data = Deposit::where('trx', $trx?->trx)->first();
        if ($data->status == 0) {
            $data->status = 1;
            $data->save();

            $user = User::find($data->user_id);
            $user->balance += $data->amount;
            $user->save();

            $transaction = new Transaction();
            $user = \Auth::user();
            $transaction->user_id = $user->id;
            $transaction->to_user = $data->user_id;
            $transaction->amount = $data->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = $data->charge;
            $transaction->trx_type = '+';
            $transaction->details = 'Deposit Via ' . $data->gatewayCurrency()->name;
            $transaction->trx = $data->trx;
            $transaction->save();

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'Deposit successful via ' . $data->gatewayCurrency()->name;
            $adminNotification->click_url = urlPath('admin.deposit.successful');
            $adminNotification->save();

            notify($user, 'DEPOSIT_COMPLETE', [
                'method_name' => $data->gatewayCurrency()->name,
                'method_currency' => $data->method_currency,
                'method_amount' => getAmount($data->final_amo),
                'amount' => getAmount($data->amount),
                'charge' => getAmount($data->charge),
                'currency' => $general->cur_text,
                'rate' => getAmount($data->rate),
                'trx' => $data->trx,
                'post_balance' => getAmount($user->balance)
            ]);

            if ($data->booking_id) {
                $user = User::find($data->user_id);
                $user->balance -= $data->amount;
                $user->save();

                $booking = Booking::where('id', $data->booking_id)->first();

                if ($booking->service_id) {
                    $booking->status = 1;
                    $booking->working_status = 0;
                    $booking->save();

                    $bookedUser = User::where('id', $booking->service->user_id)->first();

                    $adminNotification = new AdminNotification();
                    $adminNotification->user_id = $user->id;
                    $adminNotification->title = 'Service booking payment ' . $user->username;
                    $adminNotification->click_url = urlPath('admin.booking.service.details', $booking->id);
                    $adminNotification->save();

                    notify($bookedUser, 'SERVICE_BOOKING', [
                        'order_number' => $booking->order_number,
                        'amount' => getAmount($booking->amount),
                        'currency' => $general->cur_text,
                    ]);
                } elseif ($booking->software_id) {
                    $booking->status = 3;
                    $booking->working_status = 1;
                    $booking->save();

                    $bookedUser = User::where('id', $booking->software->user_id)->first();
                    $charge = (($booking->amount / 100) * $general->charge);
                    $payableAmountUser = ($booking->amount - $charge);

                    $bookedUser->balance += $payableAmountUser;
                    $bookedUser->income += $payableAmountUser;
                    $bookedUser->save();
                    rankUser($bookedUser->id);

                    $transaction = new Transaction();
                    $user = Auth::user();
                    $transaction->user_id = $user->id;
                    $transaction->to_user = $bookedUser->id;
                    $transaction->amount = $payableAmountUser;
                    $transaction->post_balance = $bookedUser->balance;
                    $transaction->charge = $charge;
                    $transaction->trx_type = '+';
                    $transaction->trx = $booking->order_number;
                    $transaction->details = "Payment for " . $booking->order_number;
                    $transaction->save();

                    $adminNotification = new AdminNotification();
                    $adminNotification->user_id = $user->id;
                    $adminNotification->title = 'Software purchase complete ' . $user->username;
                    $adminNotification->click_url = urlPath('admin.sales.software.index');
                    $adminNotification->save();

                    notify($bookedUser, 'SOFTWARE_PURCHASE', [
                        'order_number' => $booking->order_number,
                        'amount' => getAmount($booking->amount),
                        'currency' => $general->cur_text,
                    ]);

                    notify($bookedUser, 'PAYMENT_SELLER', [
                        'amount' => getAmount($payableAmountUser),
                        'currency' => $general->cur_text,
                        'order_number' => $booking->order_number,
                        'post_balance' => getAmount($bookedUser->balance)
                    ]);
                } elseif ($booking->job_biding_id) {
                    $booking->status = 1;
                    $booking->working_status = 4;
                    $booking->save();

                    $bookedUser = User::where('id', $booking->biding->user_id)->first();

                    notify($bookedUser, 'HIRE_EMPLOY', [
                        'order_number' => $booking->order_number,
                        'amount' => getAmount($booking->amount),
                        'currency' => $general->cur_text,
                    ]);
                }

                $transaction = new Transaction();
                $user = Auth::user();
                $transaction->user_id = $user->id;
                $transaction->to_user = $data->user_id;
                $transaction->amount = $data->amount;
                $transaction->post_balance = $user->balance;
                $transaction->trx_type = '-';
                $transaction->details = 'Payment Via ' . $data->gatewayCurrency()->name;
                $transaction->trx = $data->trx;
                $transaction->save();

                notify($user, 'PAYMENT_COMPLETE', [
                    'amount' => getAmount($booking->amount),
                    'currency' => $general->cur_text,
                    'order_number' => $booking->order_number,
                    'post_balance' => getAmount($user->balance)
                ]);
            }
        }
    }

    public function manualDepositConfirm(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            return view($this->activeTemplate . 'user.manual_payment.manual_confirm', compact('data', 'pageTitle', 'method'));
        }
        abort(404);
    }
    public function manualDepositConfirmtwo(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            return view($this->activeTemplate . 'user.manual_payment.manual_confirmtwo', compact('data', 'pageTitle', 'method'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }

        $params = json_decode($data->gatewayCurrency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $custom) {
                $rules[$key] = [$custom->validation];
                if ($custom->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg', 'jpeg', 'png']));
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($custom->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($custom->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);


        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = imagePath()['verify']['deposit']['path'] . '/' . $directory;
        $collection = collect($request);
        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory . '/' . uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }



        $data->status = 2; // pending
        $data->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        $general = GeneralSetting::first();
        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => getAmount($data->final_amo),
            'amount' => getAmount($data->amount),
            'charge' => getAmount($data->charge),
            'currency' => $general->cur_text,
            'rate' => getAmount($data->rate),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken.'];
        return redirect()->route('user.deposit.history')->withNotify($notify);
    }


    public function manualDepositUpdatetwo(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }

        $params = json_decode($data->gatewayCurrency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $custom) {
                $rules[$key] = [$custom->validation];
                if ($custom->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg', 'jpeg', 'png']));
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($custom->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($custom->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);


        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = imagePath()['verify']['deposit']['path'] . '/' . $directory;
        $collection = collect($request);
        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory . '/' . uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }



        $data->status = 2; // pending
        $data->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        $general = GeneralSetting::first();
        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => getAmount($data->final_amo),
            'amount' => getAmount($data->amount),
            'charge' => getAmount($data->charge),
            'currency' => $general->cur_text,
            'rate' => getAmount($data->rate),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken.'];
        return redirect()->route('user.deposit.historytwo')->withNotify($notify);
    }

    // New Code
    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        $depositId = $request->get('deposit_id');
        $deposit = Deposit::findOrFail($depositId);
        $user = Auth::user();
        if (!is_null($deposit)) {
            $user = Auth::user();
            $user->balance += $deposit->amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->to_user = null; // Stripe holds the money
            $transaction->amount = $deposit->amount;
            $transaction->charge = $deposit->charge;
            $transaction->post_balance = $user->balance; // Optional: log current balance
            $transaction->trx_type = '+';
            $transaction->status = 1; // add on 20th aug 26 bcz on dashboard we are showing status 1 record
            $transaction->trx = getTrx();
            $transaction->details = "Deposit of " . number_format($deposit->amount, 2) . " received";
            $transaction->save();

            $ctransaction = new Transaction();
            $ctransaction->user_id = $user->id;
            $ctransaction->to_user = null; // Stripe holds the money
            $ctransaction->amount = $deposit->charge;
            $ctransaction->post_balance = $user->balance; // Optional: log current balance
            $ctransaction->trx_type = '-';
            $ctransaction->status = 1; // add on 20th aug 26 bcz on dashboard we are showing status 1 record
            $ctransaction->trx = getTrx();
            $ctransaction->details = "Fee deducted: " . number_format($deposit->charge, 2);
            $ctransaction->save();
        }
        if (Auth::user()->is_freelancer == 1 || Auth::user()->is_freelancer == '1') {
            sendJobAdminRealTimeNotification($user, 'SELLER_PAYMENT_DEPOSIT', '#');
            return redirect()->route('user.home');
        } else {
            sendJobAdminRealTimeNotification($user, 'BUYER_PAYMENT_DEPOSIT', '#');
            return redirect()->route('user.buyer.dashboard');
        }

        // $StripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // \Stripe\Stripe::setApiKey($StripeAcc->secret_key);

        // $session = \Stripe\Checkout\Session::retrieve($sessionId);
        // $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        // dd($session, $paymentIntent->status);

        // if ($paymentIntent->status === 'succeeded') {
        //     $deposit->status = 1;
        //     $deposit->save();

        //     return view('popup-callback', [
        //         'status' => 'success',
        //         'message' => 'Payment successful!',
        //         'payment_intent' => $paymentIntent,
        //     ]);
        // }

        // return view('popup-callback', [
        //     'status' => 'failed',
        //     'message' => 'Payment failed or pending',
        //     'payment_intent' => $paymentIntent,
        // ]);
    }

    public function paymentCancel()
    {
        return view('popup-callback', [
            'status' => 'cancelled',
            'message' => 'Payment was cancelled',
        ]);
    }
}
