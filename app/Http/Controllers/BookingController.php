<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\ExtraService;
use App\Models\GeneralSetting;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\ServicesDetail;
use Carbon\Carbon;
use App\Models\GatewayCurrency;
use App\Models\Additionalservice;
use App\Models\Deposit;

use App\Models\Employermembershiptransactions;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Classes\Helpers;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function serviceBooking($id)
    {
        //////////////chat start /////////////
        $us = Auth::user();
        $sid = 0;//;$us->id;
        $chats = [];
        // $rid = ServicesDetail::where('id', decrypt($id))->first()->user_id;

        $user = Auth::user();

        if (session()->has('coupon')) {
            session()->forget('coupon');
        }
        $coupon = Coupon::where('status', 1)->get();
        $pageTitle = "Service Booking";

        $serviceDetailId = decrypt($id);
        $serviceDetails = ServicesDetail::where('is_active',1)->find($serviceDetailId);

        $service = Service::with(['category', 'subcategory', 'childcategory'])
            ->where('id', $serviceDetails?->service_id)->firstOrFail();

        $category = $service->category;
        $subcategory = $service->subcategory;
        $childcategory = $service->childcategory;
        $childcategorycount = $childcategory ? 1 : 0;

        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_employer == 1) {
                $usermembership = Employermembershiptransactions::join(
                    'employer_membership',
                    'employer_membership_transactions.membership_id',
                    '=',
                    'employer_membership.id'
                )->where('user_id', $user->id)->first();
            }
        }

        $availableFeatures = DB::table('service_tiers')
            ->where('type', 'checkbox') // optional: include input/select if needed
            ->where('cat_id', $service->category_id)
            ->where('subcat_id', $service->sub_category_id)
            ->when($service->childcat_id > 0, function ($q) use ($service) {
                return $q->where('childcat_id', $service->childcat_id);
            })->get();

        $userFeatures = DB::table('service_detail_tiers')
            ->where('service_detail_id', $serviceDetails->id) // single ID here, not pluck()
            ->get()->groupBy('service_detail_id');

        $serviceAddons = DB::table('user_services_adds_on as a')
            ->join('service_tiers as t', 'a.services_addson_id', '=', 't.id')
            ->where('a.service_id', $service->id)
            ->select('a.*', 't.name as addon_name', 't.type as addon_type')
            ->get();

        $fastTiers = $serviceAddons->filter(fn($item) => $item->service_detail_id == $serviceDetailId);
        $extraAddons = $serviceAddons->filter(fn($item) => is_null($item->service_detail_id));

        $mergedAddons = $extraAddons->merge($fastTiers);

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();

        $platformFee = $usermembership?->service_platform_fee ?? 0;
        $user = Auth::user();
        
        return view($this->activeTemplate . 'service_booking', compact(
            'chats',
            'sid',
            'pageTitle',
            'service',
            'coupon',
            'serviceDetails',
            'category',
            'subcategory',
            'childcategory',
            'childcategorycount',
            'availableFeatures',
            'userFeatures',
            'mergedAddons',
            'gatewayCurrency',
            'platformFee',
            'user'
        ));
    }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'serviceId' => 'required|exists:services,id',
        ]);
        if (session('coupon')) {
            $notify = 'The coupon has already been applied';
            return response()->json(['error' => $notify]);
        }
        $coupon = Coupon::where('code', $request->couponCode)->where('status', 1)->first();
        if (!$coupon || $coupon->code !== $request->couponCode) {
            $notify = 'This coupon doesn\'t exist';
            return response()->json(['error' => $notify]);
        }


        $service = Service::findOrFail($request->serviceId);
        $total = $request->serviceprice * $request->qty;

        $response = [
            'code' => $coupon->code,
            'amount' => getAmount($coupon->discount($total)),
        ];
        session()->put('coupon', $coupon->code);
        $response['success'] = 'Coupon has applied successfully';
        return response()->json($response);
    }

    // public function serviceBooked(Request $request)
    // {  
    //     $request->validate([
    //         'serviceId' => 'required|exists:services,id',
    //         'qty' => 'required|min:1|max:30',
    //         'payment' => 'required|in:wallet,checkout'
    //     ]);
    //     $user = Auth::user();
    //     $service = Service::where('status', 1)->where('id', $request->serviceId)->firstOrFail();
    //     if($service->user_id == $user->id){
    //         $notify[] = ["error","You can not be booked your self-service"];
    //         return back()->withNotify($notify);
    //     }
    //     if($request->payment == "wallet"){
    //         $this->orderWithWallet($service->id,$request->custommizeprice, $request->qty, $request->extraservice);
    //         return back();
    //     }
    //     elseif($request->payment == "checkout"){
    //         $this->orderWithCheckout($service->id,$request->custommizeprice, $request->qty, $request->extraservice);
    //         return redirect()->route('user.payment.method');
    //     }
    //     else{
    //         $notify[] = ["error","Something is wrong"];
    //         return back()->withNotify($notify);
    //     }
    // }

    // private function orderWithWallet($serviceId, $custommizeprice, $qty, $extraService){

    //     $general = GeneralSetting::first();
    //     $user = Auth::user();
    //     $extraPrice = 0;
    //     $discount = 0;
    //     $service = Service::findOrFail($serviceId);
    //     if($extraService)
    //     {
    //         $extraArray = explode(",",$extraService);
    //         foreach ($extraArray as $newValue) {
    //             $extra = ExtraService::where('id',$newValue)->where('service_id', $service->id)->firstOrFail();
    //             $extraPrice += $extra->price;
    //         }
    //     }
    //     $serviceTotalPrice = $service->price * $qty;
    //     if(session()->has('coupon'))
    //     {
    //         $coupon   = Coupon::where('code', session()->get('coupon'))->where('status', 1)->first();
    //         if($coupon){
    //             $discount = getAmount($coupon->discount($serviceTotalPrice));
    //         }
    //         session()->forget('coupon');
    //     }
    //     $totalPrice = (($serviceTotalPrice + $extraPrice+$custommizeprice) - $discount);
    //     if($totalPrice > $user->balance)
    //     {
    //         $notify[] = ['error', 'Your account '.getAmount($user->balance).' '.$general->cur_text.' balance not enough! please deposit money'];
    //         return back()->withNotify($notify);
    //     }

    //     $booking = new Booking();
    //     $booking->user_id = $user->id;
    //     $booking->service_id = $service->id;
    //     $booking->qty = $qty;
    //     $booking->amount = $totalPrice;
    //     $booking->discount = $discount;
    //     $booking->order_number = getTrx();
    //     $booking->extra_service = $extraService;
    //     $booking->status = 1;
    //     $booking->updated_at = Carbon::now();
    //     $booking->status_updated_at = Carbon::now();
    //     $booking->save();

    //     $user->balance -= $booking->amount;
    //     $user->save();

    //     $transaction = new Transaction();
    //     $transaction->user_id = $booking->user_id;
    //     $transaction->amount = $booking->amount;
    //     $transaction->post_balance = $user->balance;
    //     $transaction->trx_type = '-';
    //     $transaction->trx = $booking->order_number;
    //     $transaction->details = "Service booking payment";
    //     $transaction->save();

    //     $adminNotification = new AdminNotification();
    //     $adminNotification->user_id = $user->id;
    //     $adminNotification->title = 'Service booking payment '.$user->username;
    //     $adminNotification->click_url = urlPath('admin.booking.service.details', $booking->id);
    //     $adminNotification->save();

    //     $serviceUser = User::where('id', $service->user_id)->first();
    //     notify($serviceUser, 'SERVICE_BOOKING', [
    //         'order_number' => $booking->order_number, 
    //         'amount' => getAmount($booking->amount),
    //         'currency' => $general->cur_text,
    //     ]);

    //     notify($user, 'PAYMENT_COMPLETE', [
    //         'amount' => getAmount($booking->amount),
    //         'currency' => $general->cur_text,
    //         'order_number' => $booking->order_number,
    //         'post_balance' => getAmount($user->balance)
    //     ]);
    //     $notify[] = ["success","Service booking has been created"];
    //     return redirect()->route('user.home')->withNotify($notify);
    // }

    // private function orderWithCheckout($serviceId,$custommizeprice, $qty, $extraService){
    //     $general = GeneralSetting::first();
    //     $user = Auth::user();
    //     $extraPrice = 0;
    //     $discount = 0;
    //     $service = Service::findOrFail($serviceId);
    //     if($extraService)
    //     {
    //         $extraArray = explode(",",$extraService);
    //         foreach ($extraArray as $newValue) {
    //             $extra = ExtraService::findOrFail($newValue);
    //             $extraPrice += $extra->price;
    //         }
    //     }
    //     $serviceTotalPrice = $service->price * $qty;
    //     if(session()->has('coupon'))
    //     {
    //         dd(session()->get('coupon'));
    //         $coupon   = Coupon::where('code', session()->get('coupon'))->where('status', 1)->first();
    //         if($coupon)
    //         {
    //             $discount = getAmount($coupon->discount($serviceTotalPrice));
    //         }
    //         session()->forget('coupon');
    //     }
    //     $totalPrice = (($serviceTotalPrice + $extraPrice+$custommizeprice) - $discount);

    //     $booking = new Booking();
    //     $booking->user_id = $user->id;
    //     $booking->service_id = $service->id;
    //     $booking->qty = $qty;
    //     $booking->amount = $totalPrice;
    //     $booking->discount = $discount;
    //     $booking->order_number = getTrx();
    //     $booking->extra_service = $extraService;
    //     $booking->status = 0; 
    //     $booking->updated_at = Carbon::now();
    //     $booking->status_updated_at = Carbon::now(); 
    //     $booking->save();
    //     session()->put('booking',$booking->order_number);
    //     return back();
    // }



    public function serviceBooked(Request $request)
    {
        $request->validate([
            'serviceId' => 'required|exists:services,id',
            'qty' => 'required|min:1|max:30',
            'payment' => 'required|in:wallet,checkout'
        ]);
        $user = Auth::user();
        $service = Service::where('status', 1)->where('id', $request->serviceId)->firstOrFail();
        if ($service->user_id == $user->id) {
            $notify[] = ["error", "You can not be booked your self-service"];
            return back()->withNotify($notify);
        }
        if ($request->payment == "wallet") {
            $extra_title = $request->extra_title;
            $extra_price = $request->extra_price;
            // print_r($extra_price);exit;
            $this->orderWithWallet($service->id, $request->qty, $request->price, $request->additionalextraprice, $extra_title, $extra_price, $request->revision, $request->time, $request->package, $request->othersymbol, $request->otherprice, $request->currentrate, $request->seller_platform_fee, $request->buyer_service_fee);
            $notify[] = ["success", "Service booking has been created"];
            return redirect()->route('user.buyer.service.booked')->withNotify($notify);
            // return back();
        } elseif ($request->payment == "checkout") {
            $this->orderWithCheckout($service->id, $request->qty, $request->price, $request->additionalextraprice);
            return redirect()->route('user.payment.method');
        } else {
            $notify[] = ["error", "Something is wrong"];
            return back()->withNotify($notify);
        }
    }

    private function orderWithWallet($serviceId, $qty, $price, $extraprice, $extra_title, $extra_price, $revision, $time, $package, $othersymbol, $otherprice, $currentrate, $seller_platform_fee, $buyer_service_fee)
    {
        //echo $buyer_service_fee;exit;
        if ($extra_price == '') {
            $total = 0;
        } else {
            $total = array_sum($extra_price);
        }

        // echo $total ;
        // exit;
        $general = GeneralSetting::first();
        $user = Auth::user();
        $extraPrice = 0;
        $discount = 0;
        $service = Service::findOrFail($serviceId);

        $serviceTotalPrice = $otherprice * $qty;
        //echo $serviceTotalPrice;exit;
        $totalPrice = ($serviceTotalPrice + $total);
        if (session()->has('coupon')) {
            $coupon = Coupon::where('code', session()->get('coupon'))->where('status', 1)->first();
            if ($coupon) {
                $discount = getAmount($coupon->discount($totalPrice));
            }
            session()->forget('coupon');
        }
        //echo $currentrate;exit;
        $totalPriceneww1 = $totalPrice - $discount;
        $per = ($totalPriceneww1 * $buyer_service_fee) / 100;
        $totalPriceneww = $totalPriceneww1 + $per;
        $totalPricenew = $totalPriceneww / $currentrate;
        //echo $totalPricenew;exit;

        if ($totalPricenew > $user->balance) {
            $notify[] = ['error', 'Your account balance not enough! please deposit money'];
            return back()->withNotify($notify);
        }

        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->service_id = $service->id;
        $booking->qty = $qty;
        $booking->amount = $totalPricenew;
        $booking->discount = $discount;
        $booking->service_revisions = $revision;
        $booking->service_days = $time;
        $booking->service_package = $package;
        $booking->service_price = $price;
        $booking->otherprice = $otherprice - ($discount * $currentrate);
        $booking->othersymbol = $othersymbol;
        $booking->currentrate = $currentrate;
        $booking->otheramount = $totalPriceneww;
        $booking->seller_platform_fee = $seller_platform_fee;
        $booking->buyer_service_fee = $buyer_service_fee;
        $booking->order_number = getTrx();
        $booking->status = 5;
        $booking->pro_id = $service->user_id;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();

        $user->balance -= $booking->amount;
        $user->save();

        if (isset($extra_price)) {
            foreach ($extra_price as $key => $a) {
                $add_service = new Additionalservice();
                $add_service->service_id = $service->id;
                $add_service->booking_id = $booking->id;
                $add_service->title = $extra_title[$key];
                $add_service->amount = $extra_price[$key];
                $add_service->othersymbol = $othersymbol;
                $add_service->save();
            }
        }




        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->to_user = $booking->user_id;
        $transaction->amount = $booking->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type = '-';
        $transaction->trx = $booking->order_number;
        $transaction->details = "Service booking payment";
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'Service booking payment ' . $user->username;
        $adminNotification->click_url = urlPath('admin.booking.service.details', $booking->id);
        $adminNotification->save();

        $serviceUser = User::where('id', $service->user_id)->first();
        notify($serviceUser, 'SERVICE_BOOKING', [
            'order_number' => $booking->order_number,
            'amount' => getAmount($booking->amount),
            'currency' => $general->cur_text,
        ]);

        notify($user, 'PAYMENT_COMPLETE', [
            'amount' => getAmount($booking->amount),
            'currency' => $general->cur_text,
            'order_number' => $booking->order_number,
            'post_balance' => getAmount($user->balance)
        ]);
        // send email
        $emailuserservice = DB::table('services')->where('id', $service->id)->first();
        $emailuser = DB::table('users')->where('id', $emailuserservice->user_id)->first();
        $booker = DB::table('users')->where('id', $user->id)->first();

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {

            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = env('MAIL_PORT');
            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($emailuser->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }


            $mail->isHTML(true);
            $mail->Subject = ' Service Booking.';
            $contentBody = view('Emails/Service/servicebook', compact('emailuserservice', 'booker'))->render();
            // $mail->Body    = $user->ev_code; 
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {
                return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
            } else {
                return back()->with("success", "Email has been sent.");
            }

        } catch (Exception $e) {
            return back()->with('error', 'Message could not be sent.');
        }
        // send email
        $notify[] = ["success", "Service booking has been created"];
        return redirect()->route('user.buyer.service.booked')->withNotify($notify);
    }

    private function orderWithCheckout($serviceId, $custommizeprice, $qty, $extraService)
    {
        $general = GeneralSetting::first();
        $user = Auth::user();
        $extraPrice = 0;
        $discount = 0;
        $service = Service::findOrFail($serviceId);
        if ($extraService) {
            $extraArray = explode(",", $extraService);
            foreach ($extraArray as $newValue) {
                $extra = ExtraService::findOrFail($newValue);
                $extraPrice += $extra->price;
            }
        }
        $serviceTotalPrice = $service->price * $qty;
        if (session()->has('coupon')) {
            $coupon = Coupon::where('code', session()->get('coupon'))->where('status', 1)->first();
            if ($coupon) {
                $discount = getAmount($coupon->discount($serviceTotalPrice));
            }
            session()->forget('coupon');
        }
        $totalPrice = (($serviceTotalPrice + $extraPrice + $custommizeprice) - $discount);

        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->service_id = $service->id;
        $booking->qty = $qty;
        $booking->amount = $totalPrice;
        $booking->discount = $discount;
        $booking->order_number = getTrx();
        $booking->extra_service = $extraService;
        $booking->status = 0;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();
        session()->put('booking', $booking->order_number);
        return back();
    }


    public function payment()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();

        $pageTitle = 'Payment Methods';
        return view($this->activeTemplate . 'user.payment', compact('gatewayCurrency', 'pageTitle'));
    }



    public function paymentInsert(Request $request)
    {

        $request->validate([
            'booking_number' => 'required|exists:bookings,order_number',
            'method_code' => 'required',
            'currency' => 'required',
        ]);
        $booking = Booking::where('status', 0)->where('order_number', $request->booking_number)->first();
        if (!$booking) {
            $notify[] = ['error', 'Invalid booking number'];
            return back()->withNotify($notify);
        }
        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $booking->amount || $gate->max_amount < $booking->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($booking->amount * $gate->percent_charge / 100);
        $payable = $booking->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->booking_id = $booking->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $booking->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = $booking->order_number;
        $data->try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return redirect()->route('user.deposit.preview');
    }
    
    public function confirmBooking(Request $request)
    {
        $service_detail_id = decrypt($request->input('service_detail_id'));
        
        $request->merge([
            'service_detail_id' => $service_detail_id
        ]);
        $request->validate([
            'service_detail_id' => 'required|exists:services_details,id',
            // 'package' => 'required|in:1,2,3',
            'quantity' => 'required|integer|min:1',
            'converted_amount' => 'required|numeric',
            'serviceFee' => 'nullable|numeric',
            'discount' => 'nullable|string',
        ]);

        $serviceDetails = ServicesDetail::where('is_active',1)->find($service_detail_id);
        $service = Service::find($serviceDetails->service_id);

        $basePrice = $request->converted_amount;

        $coupan_amount = 0;
        if (isset($request->coupon)) {
            $coupon = Coupon::where('code', $request->coupon)->first();
            $coupan_amount = $coupon ? $coupon->discount($basePrice) : 0;
        }

        $pageTitle = "Service Order Payment";
        // Compute totals
        $quantity = $request->quantity;
        $service_fee = $request->serviceFee;

        $extra_services = $request->input('extra_services_data');
        $extra_services = json_decode($extra_services);


        $Subtotal = $request->input('Subtotal');

        $serviceFee = ($basePrice * $service_fee) / 100;

        $total = ($basePrice * $quantity);
        $feeAmount = ($total * $serviceFee) / 100;
        $finalPrice = $total + $feeAmount;

        // Fetch revision & time from selected tier or service details
        $package = $request->package;

        // $revision = $package == 3 ? $detail->revision3 : ($package == 2 ? $detail->revision2 : $detail->revision1);
        // $time = $package == 3 ? $detail->delivery_time3 : ($package == 2 ? $detail->delivery_time2 : $detail->delivery_time1);

        $revision = $serviceDetails->revision;
        $time = $serviceDetails->delivery_time;

        $availableFeatures = DB::table('service_tiers')
            ->where('type', 'checkbox') // optional: include input/select if needed
            ->where('cat_id', $service->category_id)
            ->where('subcat_id', $service->sub_category_id)
            ->when($service->childcat_id > 0, function ($q) use ($service) {
                return $q->where('childcat_id', $service->childcat_id);
            })->get();

        $userFeatures = DB::table('service_detail_tiers')
            ->where('service_detail_id', $serviceDetails->id) // single ID here, not pluck()
            ->get()->groupBy('service_detail_id');

        $usermembership = null;
        if (!empty($user)) {
            if ($user->is_employer == 1) {
                $usermembership = Employermembershiptransactions::join(
                    'employer_membership',
                    'employer_membership_transactions.membership_id',
                    '=',
                    'employer_membership.id'
                )->where('user_id', $user->id)->first();
            }
        }
        // You can store the temp booking in session if needed
        $platformFee = $usermembership?->service_platform_fee ?? 0;
        $user = Auth::user();
        
        return view($this->activeTemplate . 'service_billing', [
            'service' => $service,
            'package' => $package,
            'n' => $basePrice, // base price
            'revision' => $revision,
            'time' => $time,
            'rates' => 1, // if you have currency rate
            'usermembership' => [], // add your logic if needed
            'usermembership1' => [], // add your logic if needed
            'helpers' => new Helpers(), // or inject via constructor,
            'pageTitle' => $pageTitle,
            'serviceDetails' => $serviceDetails,
            'availableFeatures' => $availableFeatures,
            'userFeatures' => $userFeatures,
            'platformFee' => $platformFee,
            'finalPrice' => $finalPrice,
            'quantity' => $quantity,
            'extra_services' => $extra_services,
            'Subtotal' => $Subtotal,
            'service_fee' => $service_fee, //its percentage 
            'serviceFee' => $serviceFee, // its actual amount
            'basePrice' => $basePrice,
            'user' => $user,
            'coupan_amount' => $coupan_amount,
            'coupon' => $request->coupon ?? null,
            'addon_ids'=>$request->addon_ids,
            'extra_services_data'=>$request->extra_services_data,
        ]);
    }

}
