<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Additionalservice;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Employermembershiptransactions;
use App\Models\Freelancermembershiptransactions;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\PaymentSession;
use App\Models\Service;
use App\Models\ServicesDetail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CopyServicePaymentControler extends Controller
{
    public function serviceBooking(Request $request)
    {
        $decryptedServiceDetailId = decrypt($request->service_detail_id);
        $request->merge(['service_detail_id' => $decryptedServiceDetailId]);

        $request->validate([
            'converted_amount' => 'required|numeric|gt:0',
            'service_detail_id' => 'required|integer|exists:services_details,id',
        ]);
        $discountedAmount = 0;

        if($request->input('coupon')) {
            $request->validate([
                'coupon' => 'string|exists:coupons,code'
            ]);

            $coupan = Coupon::where('code', $request->input('coupon'))->first();
            if ($coupan->type === 1) {
                $discountedAmount = $coupan->value;
            } elseif ($coupan->type === 2) {
                $discountedAmount = ($coupan->value / 100) * $request->input('converted_amount');
            }
        }

        $user = auth()->user();
        $methodCode = '114';
        $currency = "USD";

        $gateway = GatewayCurrency::whereHas('method', function ($query) {
            $query->where('status', 1);
        })->where('method_code', $methodCode)->where('currency', $currency)->first();

        if (!$gateway) {
            $notify = 'Invalid gateway selected';
            return back()->withNotify($notify);
        }
        // Fetch the fixed and percentage charges from the gateway data
        $fixedCharge = (float) $gateway->fixed_charge; // Ensure this is a float
        $percentCharge = (float) $gateway->percent_charge; // Ensure this is a float

        $stripeConfig = json_decode($gateway->gateway_parameter);
        $general = GeneralSetting::first();

        \Stripe\Stripe::setApiKey($stripeConfig->secret_key);

        $serviceDetail = ServicesDetail::where('is_active',1)->findOrFail($request->service_detail_id);

        $extra_services_data = $request->input('extra_services_data', []);
        $qty = $request->input('quantity', 0);

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $user->id)->first();
        $platformFee = $usermembership?->service_platform_fee ?? 0; // platform fee
        $serviceFeeAmount = $request->input('serviceFeeAmount',0);

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

            $totalServicePrice = $request->input('converted_amount', 0);

            $addonTotal = array_sum($addonPrices);
            $discount = floatval($discountedAmount ?? 0);
            // $service_fee = floatval($request->service_fee_amount ?? 0);

            $basePrice = $servicePrice + $addonTotal;
            // if ($discount > $basePrice)
            //     $discount = $basePrice;

            $packagePrice = $basePrice - $discount;
            $paymentFee = $fixedCharge + ($totalServicePrice * $percentCharge / 100);

            // $subTotal = $totalServicePrice + $paymentFee;
            // $subTotal = $packagePrice + $paymentFee;
            // dd($subTotal);
            $platformFeeAmount = ($platformFee / 100) * $packagePrice;
            // $total = $subTotal + $platformFeeAmount;
            // $total = $totalServicePrice + $platformFeeAmount;
            $total = max(0, $totalServicePrice - $discountedAmount); // container service fee as well
            // dd($total,$servicePrice,$addonTotal,$discount,$basePrice,$packagePrice,$paymentFee<$subTotal,$platformFeeAmount);
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $user->email,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($gateway->currency),
                            'unit_amount' => floatval($total * 100), // cents
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
                'success_url' => route('user.service.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.payment.cancel'),
            ]);

            //Save session details
            PaymentSession::create(attributes: [
                'session_id' => $session->id,
                'buyer_id' => $user->id,
                'gateway_id' => $gateway->id,
                'amount' => floatval($total),
                'qty' => $qty,
                'payable_type' => "service",
                'payable_id' => $decryptedServiceDetailId,
                'metadata' => json_encode([
                    'ip' => $request->ip(),
                    'qty' => $qty ?? 1,
                    'extra_services_data' => $extra_services_data,
                    'user_agent' => $request->userAgent(),
                    // Booking-specific metadata
                    'discount' => floatval($discountedAmount ?? 0),
                    'addons' => $addonIds,
                    'addon_titles' => $addonTitles,
                    'addon_prices' => $addonPrices,
                    'buyer_service_fee' => floatval($platformFee),
                    'seller_platform_fee' => floatval($freelancerService),
                    'converted_amount' => floatval($request->converted_amount ?? 0),
                    'package_price' => floatval($servicePrice ?? 0),
                    'service_id' => (int)$serviceDetail->service_id ?? null,
                    // 'othersymbol' => $request->othersymbol ?? 'USD',
                    'payment_processing_fee' => floatval($paymentFee),
                    'platform_fee_amount' => floatval($platformFeeAmount),
                    'total' => floatval($total)
                ]),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Failed', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $notify[] = ["error","Stripe Error: ".$e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function servicePaymentSuccess(Request $request)
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

        return redirect()->route('user.service-booked')->with('success', 'Payment successful!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'serviceId' => 'required|exists:services,id',
        ]);
        if (session('coupon')) {
            $response['status'] = false;
            $response['message'] = 'The coupon has already been applied';
            return response()->json($response);
        }
        $coupon = Coupon::where('code', $request->coupon)->where('status', 1)->first();
        if (!$coupon || $coupon->code !== $request->coupon) {
            $response['status'] = false;
            $response['message'] = 'This coupon doesn\'t exist';
            return response()->json($response);
        }


        $service = Service::findOrFail($request->serviceId);
        $total = $request->serviceprice * $request->qty;

        $response = [
            'code' => $coupon->code,
        ];
        session()->put('coupon', $coupon->code);
        $response['status'] = true;
        $response['message'] = 'Coupon has applied successfully';
        return response()->json($response);
    }
}
