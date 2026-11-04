<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Employermembershiptransactions;
use App\Models\ExtraService;
use App\Models\ReviewRating;
use App\Models\Servicedisputedata;
use App\Models\ServiceExtension;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WorkDelivery;
use App\Services\MembershipService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Models\Freelancermembershiptransactions;


class SellerServiceDashboardController extends Controller
{
    public function __construct(private MembershipService $membershipService)
    {
        $this->activeTemplate = activeTemplate();
    }
    public function serviceBookeds(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Service booking list";
        $emptyMessage = "No data found";
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })->with('seller')
        ->when($request->search, function ($query) use ($request) {

            $search = $request->search;

            $query->whereHas('service', function ($serviceQuery) use ($search) {
                $serviceQuery->where('title', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(getPaginate())
        ->appends($request->query());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Service Booking'],
        ];
        return view($this->activeTemplate . 'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function serviceBookingsCancelled(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Cancelled Service Bookings";
        $emptyMessage = "No cancelled bookings found";
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->where('status', 10)
            ->with('seller')
            ->when($request->search, function ($query) use ($request) {

                $search = $request->search;

                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends($request->query());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Cancelled Service Booking'],
        ];
        return view($this->activeTemplate . 'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    // Completed Bookings (status = 6)
    public function serviceBookingsCompleted(Request $request)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Completed Service Bookings";
        $emptyMessage = "No completed bookings found";
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->whereNotNull('service_id')
            ->where('is_endcontract', 1)
            ->where('status', 6)
            ->with('seller')
            ->when($request->search, function ($query) use ($request) {

                $search = $request->search;

                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends($request->query());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Completed Service Booking'],
        ];
        return view($this->activeTemplate . 'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    // Active Bookings (status = 1, "Running")
    public function serviceBookingsActive(Request $request)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Active Service Bookings";
        $emptyMessage = "No active bookings found";
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->whereNotIn('status', [6,9,10]) // 1 => Running (Active)
            ->with('seller')
            ->when($request->search, function ($query) use ($request) {

                $search = $request->search;

                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends($request->query());;

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Active Service Booking'],
        ];
        return view($this->activeTemplate . 'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function sellerServiceBookingDetails(Request $request, $id, PaymentService $paymentService)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Service booking details";
        $emptyMessage = "No data found";

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }
         
        $fees = [];

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();

        $fees['servicePlatformFeePercentage'] = $usermembership?->service_platform_fee ?? 0;
        
        $fees['platformFee'] = $booking->seller_platform_fee;

        $fees['subTotal'] = $booking?->service_price * $booking->qty;

        $fees['addOnsPrice'] = $booking?->otherprice;
        
        $fees['extraServicesTotal'] = $booking->otherprice;

        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee;

        $amount = (float) ($booking->otherprice + ($booking->service_price * $booking->qty)) - $booking->discount;
        $plarform_fee_amount = ($amount * $platform_fee) / 100;

        $methodCode = '114';
        $currency = "USD";

        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();

        $subTotal = $booking?->service_price * $booking->qty;

        $addOnsPrice = $booking?->otherprice;
        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $payment_processing_fee = getPaymentProcessingFee($methodCode, $currency, $totalServiceCost);

        $fees['paymentProcessingFee'] = getPaymentProcessingFee($methodCode, $currency, ($fees['subTotal'] + $fees['addOnsPrice']));
        $fees['totalServiceCost'] = ($amount) - ($plarform_fee_amount);

        return view($this->activeTemplate . 'user.seller.service_booking_details', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'extraPrice' => $extraPrice,
            'addOns' => $addOns,
            'fees'=>$fees,
            'payment_processing_fee'=>$payment_processing_fee,
            'platform_fee'=>$platform_fee,
            'plarform_fee_amount'=>$plarform_fee_amount
        ]);
    }

    public function sellerServiceBookingDetailsContract(Request $request, $id, PaymentService $paymentService)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Service booking Contract";
        $emptyMessage = "No data found";

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }
        // Fetch add-ons for this booking (all columns)
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)
        ->where('service_id', $booking->service_id)
        ->orderBy('id','desc')
        ->get();

        $extra_user_services = [];
        if($booking?->user_extra_services) {
            $extra_user_services = $booking->user_extra_services ?? [];
        }
        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();


        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee;

        $amount = (float) ($booking->otherprice + ($booking->service_price * $booking->qty)) - $booking->discount;
        $plarform_fee_amount = ($amount * $platform_fee) / 100;

        $platformFee = number_format($booking->seller_platform_fee, 2);
        
        $subTotal = $booking?->service_price * $booking->qty;

        $addOnsPrice = $booking?->otherprice;
        
        $extraServicesTotal = $booking->otherprice;
        
        $methodCode = '114';
        $currency = "USD";

        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $payment_processing_fee = getPaymentProcessingFee($methodCode, $currency, $totalServiceCost);

        return view(
            $this->activeTemplate . 'user.seller.service_booking_details_contract',
            [
                'pageTitle' => $pageTitle,
                'emptyMessage' => $emptyMessage,
                'booking' => $booking,
                'paymentProcessingFee' => $payment_processing_fee,
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'packagePrice' => $booking->service_price,
                'addOnPrice' => $addOnsPrice,
                'total' => $subTotal + $extraServicesTotal,
                'actualReceivable' => $subTotal + $extraServicesTotal - $platformFee,
                'extra_user_services'=>$extra_user_services,
                'servicePlatformFeePercentage'=>$usermembership?->service_platform_fee ?? 0,
                'platformFee'=>$platformFee,
                'totalServiceCost'=>$totalServiceCost,
                'seller_platform_fee'=>$platform_fee,
                'plarform_fee_amount'=>$plarform_fee_amount,
                'amount'=>$amount
            ]
        );
    }

    public function sellerServiceBookingDetailsWorkprogress(Request $request, $id)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Service booking Progress";
        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }
        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->get();
        $workscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->count();
        $review = ReviewRating::where('service_id', $booking->service_id)->where('type', 'service')->first();
        // $review=ReviewRating::where('data_id',decrypt($id))->where('type','service')->first();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();

        // Timer
        if($booking->is_endcontract == 1 || $booking->status != 5) {
            $endDate = 0;
            $endTimestamp = 0;
        }else {
            $endDate = Carbon::parse($booking->created_at)
            ->addDays($booking->service_days);
            $endTimestamp = $endDate->timestamp * 1000;
        }

        return view($this->activeTemplate . 'user.seller.service_booking_details_work_in_progress', compact('pageTitle', 'booking', 'extraPrice', 'allworks', 'workscount', 'review', 'dispute_data', 'dispute_data_count', 'endDate', 'endTimestamp'));
    }

    public function sellerUserworkservicefileDownload($id)
    {
        $work = WorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->work_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    protected function handleExtraService($booking)
    {
        $extraPrice = 0;
        $extraArray = [];

        // Already array? (edge case)
        if (is_array($booking->extra_service)) {
            $extraArray = $booking->extra_service;
        }
        // String: decode JSON
        elseif (is_string($booking->extra_service) && strlen($booking->extra_service) > 0) {
            $decoded = json_decode($booking->extra_service, true);
            if (is_array($decoded)) {
                $extraArray = $decoded;
            }
        }

        if (!empty($extraArray)) {
            foreach ($extraArray as $value) {
                $extra = ExtraService::find($value);
                if ($extra) {
                    $extraPrice += $extra->price;
                }
            }
        }

        return $extraPrice;
    }

    public function sellerServiceBookingDetailsWorkInvoice(Request $request,$id, PaymentService $paymentService)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Service booking work completed";
        $emptyMessage = "No data found";

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $platformFee = number_format($booking->seller_platform_fee, 2);
        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();

        $subTotal = ($booking?->service_price * $booking->qty) - $booking->discount;

        $addOnsPrice = $booking?->otherprice;
        
        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee;

        $amount = (float) ($booking->otherprice + ($booking->service_price * $booking->qty)) - $booking->discount;
        $plarform_fee_amount = ($amount * $platform_fee) / 100;

        $service_cost = ($amount) - ($plarform_fee_amount);


        $methodCode = '114';
        $currency = "USD";

        
        $servicePlatformFee = number_format(($platform_fee / 100) * ($subTotal + $addOnsPrice),2);

        $totalServiceCost = ($subTotal + $addOnsPrice) - $servicePlatformFee;

        $payment_processing_fee = getPaymentProcessingFee($methodCode, $currency, $totalServiceCost);

        $finalTotalServiceCost = number_format((($subTotal+$addOnsPrice)) - ($platformFee+$payment_processing_fee),2);

        $service_paid_amount = $this->calculateServiceTransactions($booking->id) == $booking->agreed_amount;

        $paid_amount = $this->calculateServiceTransactions($booking->id);

        $transactions = Transaction::where(function($query) use($booking) {
            $query->where('booking_id',$booking->id)
            ->where('user_id',Auth::id())
            ->where('charge','>',0);
        })
        ->orderBy('id', 'asc')
        ->get()
        ->keyBy('work_id');

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->get();

        $totalRevisions = ($booking->service_revisions ?? 0) + ($booking->extra_revisions ?? 0);

        return view(
            $this->activeTemplate . 'user.seller.service_booking_details_work_completed',
            [
                'pageTitle' => $pageTitle,
                'emptyMessage' => $emptyMessage,
                'booking' => $booking,
                'paymentProcessingFee' => $payment_processing_fee,
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'packagePrice' => $booking->service_price,
                'addOnPrice' => $addOnsPrice,
                'total' => $subTotal,
                'servicePlatformFeePercentage'=>$platform_fee,
                'platformFee'=>$platformFee ?? 0,
                'totalServiceCost'=>$totalServiceCost,
                'subTotal'=>$subTotal+$addOnsPrice,
                'finalTotalServiceCost'=>$finalTotalServiceCost,
                'platform_fee'=>$plarform_fee_amount,
                'amount'=>$amount,
                'plarform_fee_amount'=>$plarform_fee_amount,
                'service_paid_amount'=>$service_paid_amount,
                'totalRevisions'=>$totalRevisions,
                'transactions'=>$transactions,
                'paid_amount'=>$paid_amount,
                'allworks'=>$allworks,
                'service_cost'=>$service_cost
            ]
        );
    }

    private function calculateServiceTransactions($booking_id)
    {
        return Transaction::where(function($query) use($booking_id) {
            $query->where('booking_id',$booking_id)
            ->where('user_id',Auth::id());
        })
        ->get()
        ->sum(function ($transaction) {
            return ($transaction->amount ?? 0);
        });
    }

    public function sellerServiceBookingDetailsWorkcompleted(Request $request, $id)
    {
         if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Service booking Completed";
        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->whereIn('status',[3,6])->get();
        $allworkscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->whereIn('status',[3,6])->count();
        $workscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->whereIn('status',[3,6])->count();
        $review = ReviewRating::where('data_id', decrypt($id))->where('type', 'service')->first();
        //$review = ReviewRating::where('data_id', decrypt($id))->where('type', 'job')->first();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();
        return view($this->activeTemplate . 'user.seller.service_booking_details_work_in_progress_complete', compact('pageTitle', 'booking', 'extraPrice', 'allworks', 'workscount', 'review', 'dispute_data', 'dispute_data_count', 'allworkscount'));
    }

    public function generateSellerInvoicePDFserviceseller($id)
    {
        $booking = Booking::where('id', $id)->firstOrFail();

        // $usermembership = Employermembershiptransactions::join(
        //     'employer_membership',
        //     'employer_membership_transactions.membership_id',
        //     '=',
        //     'employer_membership.id'
        // )->where('user_id', $booking->buyer_id)->first();

        // $servicePlatformFeePercentage = $usermembership?->service_platform_fee ?? 0;

        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee;

        $amount = (float) ($booking->otherprice + ($booking->service_price * $booking->qty)) - $booking->discount;
        $plarform_fee_amount = ($amount * $platform_fee) / 100;


        $subTotal = $booking?->service_price * $booking->qty;

        $addOnsPrice = $booking?->otherprice;

        $methodCode = '114';
        $currency = "USD";
        
        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $payment_processing_fee = 0;

        
        $transactions = Transaction::where(function($query) use($booking) {
            $query->where('booking_id',$booking->id)
            ->where('user_id',Auth::id())
            ->where('charge','>',0);
        })
        ->orderBy('id', 'asc')
        ->get()
        ->sum(function ($transaction) {
            return ($transaction->amount ?? 0);
        });
        // $payment_processing_fee = getPaymentProcessingFee($methodCode, $currency, $totalServiceCost);

        $buyer = Booking::find($id)->buyer;
        $seller = Booking::find($id)->seller;

        $pageTitle = 'Download Invoice';
        $pdf = PDF::loadView('downloadinvoicesellerservice', compact('booking', 'pageTitle', 'payment_processing_fee', 'addOnsPrice', 'subTotal', 'platform_fee','plarform_fee_amount','transactions','buyer','seller'));

        return $pdf->download(Auth::user()?->firstname.'-'.$booking?->order_number.'.pdf');
    }

    public function sellerReplyRatingService(Request $request)
    {
        $user = Auth::user();
        $booking = Booking::find($request->booking_id);
        $rev = ReviewRating::findOrFail($request->review_id);
        $rev->reply = $request->reply;
        $rev->save();
        $notify[] = ['success', 'Review replied successfully'];

        sendServiceEmailWithNotification($user->id, $booking?->buyer_id, $booking?->service_id, 'BUYER_SERVICE_WORK_REVIEWED',true, bookingId:$booking->id); // buyer ko send hoga

        return redirect()->back()->withNotify($notify);
    }

    public function serviceExtensionRequest(Request $request)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'service_id' => 'required|exists:services,id',
                'buyer_id' => 'required|exists:users,id',
                'seller_id' => 'required|exists:users,id',
                'from_extension_date' => 'required|date',
                'upto_extension_date' => 'required|date|after:from_extension_date',
                'details' => 'nullable|string',
            ],[
                    'upto_extension_date.after' => 'The upto extension date must be after from today date.',

            ]);

            $booking = Booking::findOrFail($request->booking_id);
            $booking->is_extension_requested = true;
            $booking->is_extension_approved = 0;
            $booking->save();
            $booking->fresh();

            if ($booking->seller_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $extension = new ServiceExtension();
            $extension->service_id = $request->service_id;
            $extension->booking_id = $request->booking_id;
            $extension->buyer_id = $request->buyer_id;
            $extension->seller_id = $request->seller_id;
            $extension->from_extension_date = $request->from_extension_date;
            $extension->upto_extension_date = $request->upto_extension_date;
            $extension->details = $request->details ?? null;
            $extension->save();

            sendServiceEmailWithNotification($user->id, $booking?->buyer_id, $booking?->service_id, 'SELLER_REQUEST_FOR_EXTENSION',true, bookingId:$booking->id); // buyer ko send hoga

            DB::commit();
            $notify[] = ['success', 'Extension request sent successfully'];
            return redirect()->back()->withNotify($notify);
        }
        catch(Exception $e) {
            DB::rollBack();
            Log::info("Service Extension Request Error: " . $e->getMessage());
            $notify[] = ['error', $e->getMessage()];
            return redirect()->back()->withNotify($notify);
        }
        
    }

    public function workUpload(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'file' => ['required'],
            'details' => 'required|max:1000',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $user = Auth::user();
        $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();
        if (!$booking) {
            $notify[] = ['error', 'Invalid service booking'];
            return back()->withNotify($notify);
        }
        // $booking->working_status = 2;
        // $booking->updated_at = Carbon::now();
        // $booking->save();

        $work = new WorkDelivery();
        $work->booking_id = $booking->id;
        $work->sender_id = auth()->user()->id;
        $work->receiver_id = $booking->buyer_id;
        $work->service_id = $booking->service_id;
        $work->order_number = $booking->order_number;
        $work->status = 2;
        $work->title = $request->title;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $work->work_file = $filename;
        }
        $work->details = $request->details;
        $work->save();

        sendServiceEmailWithNotification($user->id, $booking?->buyer_id, $booking?->service_id, 'SERVICE_UPLOAD_WORK_DELIEVERY',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' uploaded work for service '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

        $notify[] = ['success', 'Work has been submitted'];
        return back()->withNotify($notify);
    }

    public function approveService( $id )
    {
        $booking = Booking::findOrFail(decrypt($id));
        if ($booking->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $booking->status = 5; // service accepted
        $booking->working_status = 4; // working service in progress
        $booking->created_at = now();
        $booking->save();

        $buyer = User::find($booking->buyer_id);
        $buyer->balance -= number_format($booking->amount + $booking->buyer_service_fee, 2);
        $buyer->save();

        sendServiceEmailWithNotification($booking->seller_id, $booking->buyer_id, $booking->service_id, 'SERVICE_APPROVED',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' accepted service booking '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

        $notify[] = ['success', 'Service Approved successfully'];
        return back()->withNotify($notify);
    }

    public function declineService( Request $request, $id )
    {
        $booking = Booking::findOrFail(decrypt($id));
        if ($booking->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $booking->status = 10;
        $booking->working_status = 3;
        $booking->decline_reason = $request->input('decline_reason',null);
        $booking->save();

        sendServiceEmailWithNotification($booking->seller_id, $booking->buyer_id, $booking->service_id, 'SERVICE_DECLINED',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' declined service booking '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));
        
        $notify[] = ['success', 'Service declined successfully'];
        return back()->withNotify($notify);
    }
}
