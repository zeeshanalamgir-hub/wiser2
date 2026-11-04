<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Additionalservice;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Employermembershiptransactions;
use App\Models\Freelancermembershiptransactions;
use App\Models\GatewayCurrency;
use App\Models\Service;
use App\Models\ServicesDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicePaymentControler extends Controller
{
    
    public function serviceBooking(Request $request)
    {
        DB::beginTransaction();

        $decryptedServiceDetailId = decrypt($request->service_detail_id);
        $request->merge(['service_detail_id' => $decryptedServiceDetailId]);

        $request->validate([
            'converted_amount' => 'required|numeric|gt:0',
            'service_detail_id' => 'required|integer|exists:services_details,id',
        ]);

        $user = Auth::user();

        if($user->balance < $request->input('converted_amount')) {
            $notify[] = ["error", "Your account balance is insufficient. Please deposit money to proceed."];
            return back()->withNotify($notify);
        }

        $serviceDetail = ServicesDetail::where('is_active',1)->findOrFail($request->service_detail_id);

        $service = Service::where('status', 1)->where('id', $serviceDetail->service_id)->firstOrFail();
        if ($service->user_id == $user->id) {
            $notify[] = ["error", "You can not be booked your self-service"];
            return back()->withNotify($notify);
        }

        $discountedAmount = $request->input('discount',0);

        // if($request->input('coupon')) {
        //     $request->validate([
        //         'coupon' => 'string|exists:coupons,code'
        //     ]);

        //     $coupan = Coupon::where('code', $request->input('coupon'))->first();
        //     if ($coupan->type === 1) {
        //         $discountedAmount = $coupan->value;
        //     } elseif ($coupan->type === 2) {
        //         $discountedAmount = ($coupan->value / 100) * $request->input('converted_amount');
        //     }
        // }

        $methodCode = '114';
        $currency = "USD";

        $gateway = GatewayCurrency::whereHas('method', function ($query) {
            $query->where('status', 1);
        })->where('method_code', $methodCode)->where('currency', $currency)->first();

        if (!$gateway) {
            $notify = 'Invalid gateway selected';
            return back()->withNotify($notify);
        }

        $extra_services_data = $request->input('extra_services_data', []);
        $qty = $request->input('quantity', 0);

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $user->id)->first();
        $buyerServicePlatformFee = $usermembership?->service_platform_fee ?? 0; // service platform fee

        $freelancer = User::where('id', $serviceDetail->user_id)->first();

        $freelancerMembership = Freelancermembershiptransactions::join(
            'freelancer_membership',
            'freelancer_membership_transactions.membership_id',
            '=',
            'freelancer_membership.id'
        )->where('user_id', $freelancer->id)->first();

        $freelancerService = $freelancerMembership->service_cost;
        $extra_days = 0;
        try {
            $addonIds = json_decode($request->addon_ids[0] ?? '[]', true);

            $addonsFromDb = DB::table('user_services_adds_on as a')
                ->join('service_tiers as t', 'a.services_addson_id', '=', 't.id')
                ->whereIn('a.id', $addonIds)
                ->select('a.id', 't.name as title', 'a.price','a.delivery_time')
                ->get();
            

            $totalServicePrice = $request->input('converted_amount', 0);

            // $total = max(0, $totalServicePrice - $discountedAmount); // discount fee as well
            $total = max(0, $totalServicePrice);

            $after_service_fee_service_amount = $total - $request->input('serviceFeeAmount', 0);

            $buyerPlatformFeeAmount = ($buyerServicePlatformFee / 100) * $total;
            $sellerServiceCost = ($freelancerService / 100) * $total;

            $otherPrices = 0;
            if (isset($extra_services_data) && !empty($extra_services_data)) {
                $extra_services_data = json_decode($extra_services_data, true);
                foreach ($extra_services_data as $extra_price) {
                    $otherPrices += $extra_price['price'];
                }
            }

            if (isset($addonsFromDb) && !empty($addonsFromDb)) {
                foreach ($addonsFromDb as $addon) {
                    $otherPrices += $addon->price;
                    $extra_days += $addon->delivery_time;
                }
            }

            //Payment from wallet
            $booking = new Booking();
            $booking->buyer_id = $user->id;
            $booking->seller_id = $service->user_id;
            $booking->service_id = $service->id;
            $booking->service_detail_id = $serviceDetail->id;
            $booking->qty = $qty;
            $booking->amount = $after_service_fee_service_amount;
            $booking->agreed_amount = $after_service_fee_service_amount;
            $booking->discount = $discountedAmount;
            $booking->service_revisions = $serviceDetail->revision;
            $booking->service_days = $serviceDetail->delivery_time + $extra_days;
            $booking->service_package = $serviceDetail->id;
            $booking->service_price = $serviceDetail->price;
            $booking->otherprice = $otherPrices; //refer to additional services price
            // $booking->otheramount = $otherPrices;

            $booking->seller_platform_fee = $buyerPlatformFeeAmount;
            $booking->buyer_service_fee = $request->input('serviceFeeAmount');

            $booking->order_number = getTrx();
            $booking->status = 9; // pending
            $booking->booking_type = "service";
            $booking->user_extra_services = $request->input('extra_services_data') ? json_decode($request->input('extra_services_data'),true) : null;
            $booking->extra_service = $request->addon_ids[0] ? json_decode($request->addon_ids[0],true) : null;
            $booking->updated_at = Carbon::now();
            $booking->status_updated_at = Carbon::now();
            $booking->save();

            if (isset($extra_services_data) && !empty($extra_services_data)) {
                foreach ($extra_services_data as $extra_price) {
                    $add_service = new Additionalservice();
                    $add_service->service_id = $service->id;
                    $add_service->booking_id = $booking->id;
                    $add_service->title = $extra_price['title'] ?? null;
                    $add_service->amount = $extra_price['price'] ?? null;
                    $add_service->save();
                }
            }

            if (isset($addonsFromDb) && !empty($addonsFromDb)) {
                foreach ($addonsFromDb as $addon) {
                    $add_service = new Additionalservice();
                    $add_service->service_id = $service->id;
                    $add_service->booking_id = $booking->id;
                    $add_service->title = $addon->title ?? null;
                    $add_service->amount = $addon->price ?? null;
                    $add_service->save();
                }
            }
           
            sendServiceEmailWithNotification($user->id, $service->user_id, $service->id, 'SERVICE_BOOKED',true, bookingId:$booking->id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' booked a service '.$service->title, route('admin.service.details',['id'=>$service->id]));

            DB::commit();
            $notify[] = ["success", "Service booking has been created"];
            return redirect()->route('user.service-booked')->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ["error","Wallet Error: ".$e->getMessage()];
            return back()->withNotify($notify);
        }
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
        $response = [
            'code' => $coupon->code,
            'type'=> $coupon->type == 1 ? 'fixed' : 'percentage',
            'value'=> $coupon->value
        ];
        session()->put('coupon', $coupon->code);
        $response['status'] = true;
        $response['message'] = 'Coupon has applied successfully';
        return response()->json($response);
    }
}
