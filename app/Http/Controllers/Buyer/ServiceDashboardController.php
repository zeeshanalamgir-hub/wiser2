<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Acceptedbid;
use App\Models\Additionalservice;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Employermembershiptransactions;
use App\Models\ExtraService;
use App\Models\GeneralSetting;
use App\Models\Job;
use App\Models\ReviewRating;
use App\Models\Service;
use App\Models\WorkDelivery;
use App\Services\MembershipService;
use App\Services\PaymentService;
use App\Models\Servicedisputedata;
use App\Models\Servicedisputeoffers;
use App\Models\ServiceExtension;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\Freelancermembershiptransactions;

class ServiceDashboardController extends Controller
{
    public function __construct(private MembershipService $membershipService)
    {
        $this->activeTemplate = activeTemplate();
    }
    public function serviceBookingItems( Request $request)
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
        $serviceBookings = Booking::where('buyer_id', $user->id)
        ->where('booking_type', 'service')
        ->whereNotNull('service_id')

        ->when($request->search, function ($query) use ($request) {

            $search = $request->search;

            $query->whereHas('service', function ($serviceQuery) use ($search) {
                $serviceQuery->where('title', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(getPaginate())
        ->appends(request()->query());;

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function buyerServiceBookingsCancelled( Request $request)
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
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->where('status', 10)
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends(request()->query());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Cancelled Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }
    public function buyerServiceBookingsCompleted(Request $request)
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
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->where('is_endcontract', 1)
            ->where('status', 6)
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends(request()->query());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Completed Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function buyerServiceBookingsActive( Request $request )
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
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->whereNotIn('status', [6,9,10])
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('service', function ($serviceQuery) use ($search) {
                    $serviceQuery->where('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(getPaginate())
            ->appends(request()->query());;

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Active Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function serviceBookingDetailsContract(Request $request, $id, PaymentService $paymentService)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Service booking details contract";
        $emptyMessage = "No data found";

        $booking = Booking::where('buyer_id', $user->id)
            ->where('id', decrypt($id))
            ->where('booking_type','service')
            ->whereNotNull('service_id')
            ->firstOrFail();

        // Handle extra service price if available
        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $extra_user_services = [];
        if ($booking->user_extra_services) {
            $extra_user_services = $booking->user_extra_services ?? [];
        }

        // Fetch add-ons for this booking (all columns)
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)
        ->where('service_id',$booking->service_id)
        ->orderBy('id','desc')
        ->get();


        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();
        $buyer_service_platform_fee = $usermembership->service_platform_fee;

        $subTotal = $booking?->service_price * $booking->qty;

        $addOnsPrice = $booking?->otherprice;
        
        $extraServicesTotal = $booking->otherprice;

        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $platformFee = $totalServiceCost/100 * $buyer_service_platform_fee;

        
        return view($this->activeTemplate . 'user.buyer.service_booking_details_contract', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'extraPrice' => $extraPrice,
            'addOns' => $addOns,
            'addOnsPrice' => $addOnsPrice,
            'extra_user_services' => $extra_user_services,
            'qty' => $booking->qty,
            'subTotal'=>$subTotal,
            'extraServicesTotal' => $extraServicesTotal ?? 0,
            'platformFee'=>$platformFee ?? 0,
            'totalServiceCost'=>$totalServiceCost ?? 0,
            'buyer_service_platform_fee'=>$buyer_service_platform_fee
        ]);
    }

    public function serviceBookingDetailsWorkprogress(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Service booking progress";
        $emptyMessage = "No data found";

        $booking = Booking::with(['extensionRequest'=>function($qry){
            $qry->where('is_approved',0);
        }])->where('buyer_id', $user->id)->where('id', decrypt($id))
            ->whereNotNull('service_id')->firstOrFail();

        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->get();

        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();

        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();

        $workscount = WorkDelivery::where('booking_id', $id)->where('service_id', $booking->service_id)->count();

        $disputedWorkIds = Servicedisputedata::where('order_number', $booking->order_number)
            ->where('service_id', $booking->service_id)
            ->pluck('booking_id')
            ->toArray();

        if($booking->is_endcontract == 1 || $booking->status != 5) {
            $endDate = 0;
            $endTimestamp = 0;
        }else {
            $endDate = Carbon::parse($booking->created_at)
            ->addDays($booking->service_days);
            $endTimestamp = $endDate->timestamp * 1000;
        }

        $seller = $booking->seller;
        return view($this->activeTemplate . 'user.buyer.service_booking_details_progress', compact(
            'pageTitle',
            'emptyMessage',
            'booking',
            'allworks',
            'workscount',
            'dispute_data',
            'dispute_data_count',
            'disputedWorkIds',
            'seller',
            'endDate',
            'endTimestamp',
        ));
    }

    public function serviceBookingDetailsWorkInvoice(Request $request, $id, PaymentService $paymentService)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = "Service booking Invoice";
        $emptyMessage = "No data found";
        $booking = Booking::where('buyer_id', $user->id)->where('id', decrypt($id))->whereNotNull('service_id')->firstOrFail();

        $extraPrice = $booking?->otherprice;

        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)
        ->where('service_id',$booking->service_id)
        ->orderBy('id','desc')
        ->get();

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();
        $buyer_service_platform_fee = $usermembership->service_platform_fee;

        $addOnsPrice = $booking?->otherprice;

        $subTotal = $booking?->service_price * $booking->qty;

        $extraServicesTotal = $booking->otherprice;

        $totalBookingPrice = $subTotal + $addOnsPrice;

        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $platformFee = $totalServiceCost / 100 * $buyer_service_platform_fee;

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->get();
        
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

        $totalRevisions = ($booking->service_revisions ?? 0) + ($booking->extra_revisions ?? 0);
        

        return view(
            $this->activeTemplate . 'user.buyer.service_booking_details_completed',
            [
                'pageTitle' => $pageTitle,
                'booking' => $booking,
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'addOnPrice' => $addOnsPrice,
                'subTotal' => $subTotal,
                'extraServicesTotal' => $extraServicesTotal ?? 0,
                'platformFee' => $platformFee,
                'totalBookingPrice'=>$totalBookingPrice ?? 0,
                'totalServiceCost'=>$totalServiceCost ?? 0,
                'buyer_service_platform_fee'=>$buyer_service_platform_fee,
                'service_paid_amount'=>$service_paid_amount,
                'transactions'=>$transactions,
                'totalRevisions'=>$totalRevisions,
                'allworks'=>$allworks,
                'paid_amount'=>$paid_amount
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


    public function serviceBookingDetailsWorkcompleted(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Service booking completed";
        $emptyMessage = "No data found";
        $booking = Booking::where('buyer_id', $user->id)->where('id', decrypt($id))
            ->whereNotNull('service_id')->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->whereIn('status',[3,6])->where('service_id', $booking->service_id)->get();
        $allworkscount = WorkDelivery::where('booking_id', decrypt($id))->whereIn('status',[3,6])->where('service_id', $booking->service_id)->count();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();
        $workscount = WorkDelivery::where('booking_id', $id)->whereIn('status',[3,6])->where('service_id', $booking->service_id)->count();

        return view(
            $this->activeTemplate . 'user.buyer.service_booking_details_completed1',
            compact(
                'pageTitle',
                'emptyMessage',
                'booking',
                'extraPrice',
                'allworks',
                'workscount',
                'dispute_data',
                'dispute_data_count',
                'allworkscount'
            )
        );
    }

    public function serviceBookingDetails(Request $request, $id, PaymentService $paymentService)
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

      
        // Fetch the booking record
        $booking = Booking::where('buyer_id', $user->id)
            ->where('id', decrypt($id))
            ->whereNotNull('service_id')
            ->firstOrFail();

        $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();

        $servicePlatformFeePercentage = $usermembership?->service_platform_fee ?? 0;


        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');

        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();

        return view($this->activeTemplate . 'user.buyer.service_booking_details', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'addonPricesSum' => $fees['addOnsPrice'] ?? 0,
            'paymentProcessingFee' => $fees['paymentProcessingFee'],
            'calculatedBuyerServiceFee' => $fees['calculatedBuyerServiceFee'],
            'extraServicesTotal' => $fees['extraServicesTotal'] ?? 0,
            'packagePrice' => $fees['packagePrice'],
            'addOnsPrice' => $fees['addOnsPrice'],
            'platform_fee_amount' => $fees['platform_fee_amount'],
            'servicePlatformFeePercentage'=>$servicePlatformFeePercentage,
            'addOns' => $addOns
        ]);
    }

    public function userworkservicefileDownload($id)
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

    public function userworkresponseservicefileDownload($id)
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

    public function userworkresponserejectedservicefileDownload($id)
    {
        $work = WorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->responce_file;

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

    protected function handleUserExtraService($booking)
    {
        return json_decode($booking->user_extra_services ?? '{}', true);
    }

    public function generateBuyerInvoicePDServiceSeller($id, PaymentService $paymentService)
    {
        $pageTitle = 'Download Invoice';
        $booking = Booking::where('id', $id)->firstOrFail();

        $user = Auth::user();

        // $platformFee = $booking->seller_platform_fee;
         $usermembership = Employermembershiptransactions::join(
            'employer_membership',
            'employer_membership_transactions.membership_id',
            '=',
            'employer_membership.id'
        )->where('user_id', $booking->buyer_id)->first();
        $platformFeePercentage = $usermembership->service_platform_fee;

        $addOnsPrice = $booking?->otherprice;

        $subTotal = $booking?->service_price * $booking->qty;

        $extraServicesTotal = $booking->otherprice;

        $totalBookingPrice = $subTotal + $addOnsPrice;

        $totalServiceCost = ($subTotal + $addOnsPrice) - $booking->discount;

        $platformFee = $totalServiceCost/100 * $platformFeePercentage;

        $addOnsPrice = $booking?->otherprice;
        
        $extraServicesTotal = $booking->otherprice;

        $subTotal = $booking->agreed_amount;

        $totalServiceCost = $subTotal;
        $service_paid_amount = $this->calculateServiceTransactions($booking->id) == $booking->agreed_amount;

        $transactions = Transaction::where(function($query) use($booking) {
            $query->where('booking_id',$booking->id)
            ->where('user_id',Auth::id())
            ->where('charge','>',0);
        })
        ->orderBy('id', 'asc')
        ->get()
        ->sum(function ($transaction) {
            return ($transaction?->amount ?? 0);
        });

        $buyer = Booking::find($id)->buyer;
        $seller = Booking::find($id)->seller;


        $pdf = PDF::loadView('downloadinvoicebuyerservice', compact('booking','subTotal', 'pageTitle', 'platformFee','addOnsPrice', 'totalServiceCost','service_paid_amount','transactions','platformFeePercentage','buyer','seller'));
        return $pdf->download($booking?->buyer?->username.'-'.$booking->order_number.'.pdf');
    }

    public function generateSellerInvoicePDFservicebuyer($id)
    {

        $pageTitle = 'Download Invoice';
        $booking = Booking::where('id', $id)->firstOrFail();
        $pdf = PDF::loadView('downloadinvoicesellerservice', compact('booking', 'pageTitle'));

        return $pdf->download('nicesnippets.pdf');
    }

    public function workDeliveryApproved(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $user = Auth::user();
        $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('buyer_id', $user->id)->first();
        if (!$booking) {
            $notify[] = ['error', 'Invalid service booking'];
            return back()->withNotify($notify);
        }
        
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();


        $revision_amount = number_format($booking->agreed_amount / $booking->service_revisions,2);
        $this->makePayment( $request->input('order_number') , $revision_amount, $request->work_id);

        sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'SERVICE_WORK_PAID',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Work Service Approved for service '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

        $notify[] = ['success', 'Work has been Accepted.'];
        return redirect()->back()->withNotify($notify);
    }

    protected function makePayment( $order_number, $total_booking_amount, $work_id )
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $booking = Booking::where('order_number', $order_number)
            ->whereNotNull('service_id')
            ->where('buyer_id', $user->id)
            ->first();
    
            if (!$booking) {
                $notify[] = ['error', 'Invalid service booking payment'];
                return back()->withNotify($notify);
            }

            if($booking->status != 3)
            {

                $seller_platform_fee_percentage = (float) $this->getMembershipFee($booking->seller_id);
                $buyer_platform_fee_percentage  = (float) $this->getMembershipFee($booking->buyer_id);
    
                $total_booking_amount = (float) $total_booking_amount;
    
                $seller_charge = ($seller_platform_fee_percentage > 0)
                    ? ($total_booking_amount * $seller_platform_fee_percentage) / 100
                    : 0;
    
                $buyer_charge = ($buyer_platform_fee_percentage > 0)
                    ? ($total_booking_amount * $buyer_platform_fee_percentage) / 100
                    : 0;
    
                $buyer_payed_amount = $total_booking_amount + $buyer_charge;
                $seller_earning_amount = $total_booking_amount - $seller_charge;
    
                $work = WorkDelivery::find($work_id);
                if ($work) {
                    $work->status = 6;
                    $work->payed_amount = $buyer_payed_amount;
                    $work->save();
                }
        
                $seller = User::find($booking->seller_id);
                if ($booking->seller_id) {
                    if ($seller) {
                        $seller->balance += $seller_earning_amount;
                        $seller->save();
                        rankUser($seller->id);
                    }
                }
    
                $work_count = WorkDelivery::where('booking_id',$booking->id)->count();
                // Log::info("Booking Count =>",$booking->service_revisions + $booking->extra_revisions, "Work Counr =>",$work_count);
                if($booking->service_revisions + $booking->extra_revisions === $work_count)
                {
                    $booking->status = 3;
                    $booking->working_status = 1;
                    
                }
                $booking->updated_at = Carbon::now();
                $booking->status_updated_at = Carbon::now();
                $booking->save();
    
        
                $user = Auth::user();
    
                $buyer = User::find($booking->buyer_id);
    
                $buyer_transaction = new Transaction();
                $buyer_transaction->user_id = $buyer->id;
                $buyer_transaction->to_user = $seller->id;
                $buyer_transaction->booking_id = $booking->id;
                $buyer_transaction->amount = $buyer_payed_amount;
                $buyer_transaction->charge = $buyer_charge;
                $buyer_transaction->post_balance = $buyer->balance;
                $buyer_transaction->trx_type = '-';
                $buyer_transaction->trx = $booking->order_number;
                $buyer_transaction->work_id = $work_id;
                $buyer_transaction->details = "Payment for " . $booking?->service?->title;
                $buyer_transaction->save();
    
                $buyer_charge_transaction = new Transaction();
                $buyer_charge_transaction->user_id = $buyer->id;
                $buyer_charge_transaction->to_user = $seller->id;
                $buyer_charge_transaction->booking_id = $booking->id;
                $buyer_charge_transaction->amount = $buyer_charge;
                $buyer_charge_transaction->charge = 0;
                $buyer_charge_transaction->post_balance = $buyer->balance;
                $buyer_charge_transaction->trx_type = '-';
                $buyer_charge_transaction->trx = $booking->order_number;
                $buyer_charge_transaction->work_id = $work_id;
                $buyer_charge_transaction->details = "Charge for " . $booking?->service?->title;
                $buyer_charge_transaction->save();
    
                $seller_transaction = new Transaction();
                $seller_transaction->user_id = $seller->id;
                $seller_transaction->to_user = $user->id;
                $seller_transaction->booking_id = $booking->id;
                $seller_transaction->amount = $seller_earning_amount;
                $seller_transaction->charge = $seller_charge;
                $seller_transaction->post_balance = $seller->balance;
                $seller_transaction->trx_type = '+';
                $seller_transaction->trx = $booking->order_number;
                $seller_transaction->work_id = $work_id;
                $seller_transaction->details = "Payment for " . $booking?->service?->title;
                $seller_transaction->save();
    
                $seller_charge_transaction = new Transaction();
                $seller_charge_transaction->user_id = $seller->id;
                $seller_charge_transaction->to_user = $user->id;
                $seller_charge_transaction->booking_id = $booking->id;
                $seller_charge_transaction->amount = $seller_charge;
                $seller_charge_transaction->charge = 0;
                $seller_charge_transaction->post_balance = $seller->balance;
                $seller_charge_transaction->trx_type = '-';
                $seller_charge_transaction->trx = $booking->order_number;
                $seller_charge_transaction->work_id = $work_id;
                $seller_charge_transaction->details = "Charge for " . $booking?->service?->title;
                $seller_charge_transaction->save();
    
    
                DB::commit();
                return true;
            }
            // $booking->status = 3;
            // $booking->working_status = 7;
           
    
        }
        catch(Exception $ex) {
            DB::rollBack();
            \Log::info("Payment for service".$ex->getMessage());
            return false;
        }
    }
    

    public function workDeliveryApprovedpayed(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
        ]);
        $general = GeneralSetting::first();
        $user = Auth::user();
        $booking = Booking::where('order_number', $request->order_number)
        ->whereNotNull('service_id')
        ->where('buyer_id', $user->id)
        ->first();

        if (!$booking) {
            $notify[] = ['error', 'Invalid service booking payment'];
            return back()->withNotify($notify);
        }
        $booking->status = 3;
        $booking->working_status = 7;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();

        $buyer = User::find($booking->buyer_id);
        $buyer->balance -= $booking->agreed_amount;
        $buyer->save();

        $charge = ($request->totalBookingAmount) / 100 * $general->charge;
        $AfterChargePayableAmount = ($request->totalBookingAmount) - $charge;

        // $payableAmount = round($AfterChargePayableAmount, 2)/$booking->service_revisions;

        if ($request->work_id != '') {
            $work = WorkDelivery::find($request->work_id);
            if ($work) {
                $work->status = 6;
                $work->payed_amount = $AfterChargePayableAmount;
                $work->save();
            }
        }

        if ($booking->service_id) {
            $seller = User::find($booking->seller_id);
            if ($seller) {
                $seller->balance += $AfterChargePayableAmount;
                $seller->save();
                rankUser($seller->id);
            }
        }

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'Service booking payment ' . $user->username;
        $adminNotification->click_url = urlPath('admin.booking.service.details', $booking->id);
        $adminNotification->save();

        $transaction = new Transaction();

        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->booking_id = $booking->id;
        $transaction->amount = $AfterChargePayableAmount;
        $transaction->charge = $charge;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $booking->order_number;
        $transaction->details = "Payment for " . $booking->order_number;
        $transaction->save();
        sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'SERVICE_WORK_PAID',true, bookingId:$booking->id);
      
        $notify[] = ['success', 'Work payment has been payed.'];
        return back()->withNotify($notify);
    }

    public function workDeliveryRejected(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $user = Auth::user();
        $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('buyer_id', $user->id)->first();
        if (!$booking) {
            $notify[] = ['error', 'Invalid service booking'];
            return back()->withNotify($notify);
        }

        $booking->working_status = 3;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();

        $work = WorkDelivery::where('id', $request->work_id)->firstOrFail();
        $work->status = 3;
        $work->responce = $request->details;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $work->responce_file = $filename;
        }
        $work->save();

        sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'SERVICE_WORK_REJECTED',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Work Service Rejected for service '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

        $notify[] = ['success', 'Work has been declined.'];
        return back()->withNotify($notify);
    }

    public function storeServiceDispute(Request $request, $orderNumber)
    {
        $request->merge(['order_number' => $orderNumber]);

        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'dispute_amount' => 'required|numeric',
            'buyer_amount' => 'required|numeric',
            'seller_amount' => 'required|numeric',
            'reason' => 'required|string',
            'message' => 'nullable|string',
            'file' => 'nullable|file|mimes:zip,jpg,jpeg,png,pdf', // adjust file types as needed
        ]);

        $user = Auth::user();

        $dispute_amount = (float)$request->dispute_amount;
        $buyer_amount = (float)$request->buyer_amount;
        $seller_amount = (float)$request->seller_amount;

        $total = number_format($buyer_amount + $seller_amount, 2);
        $dispute_amount = number_format($dispute_amount, 2);

        if ($total !== $dispute_amount) {
            return back()->withNotify([['error', 'Offered amount must equal the dispute amount']]);
        }

        $booking = Booking::where('order_number', $orderNumber)->firstOrFail();
        $service = Service::findOrFail($booking->service_id);

        $dispute = new Servicedisputedata([
            'booking_id' => $booking->id,
            'service_id' => $booking->service_id,
            'seller_id' => $service->user_id,
            'buyer_id' => $booking->buyer_id,
            'order_number' => $orderNumber,
            'reason' => $request->reason,
            'disputed_amount' => $dispute_amount,
            'buyer_amount' => $buyer_amount,
            'seller_amount' => $seller_amount,
            'message' => $request->message,
            'status' => '0',
            'send_by' => 'buyer'
        ]);

        // Handle file upload if present
        if ($request->hasFile('file')) {
            try {
                $filename = uploadFile($request->file, imagePath()['workFile']['path']);
                $dispute->file = $filename;
            } catch (\Exception $exp) {
                return back()->withNotify([['error', 'File could not be uploaded.']]);
            }
        }

        $dispute->save();

        // Save dispute offer details
        $disputeOffer = new Servicedisputeoffers([
            'dispute_id' => $dispute->id,
            'disputed_amount' => $dispute_amount,
            'buyer_amount' => $buyer_amount,
            'seller_amount' => $seller_amount,
            'message' => $request->message,
            'status' => '0',
            'send_by' => 'buyer'
        ]);
        $disputeOffer->save();

        // Update booking status
        $booking->status = 8;
        $booking->working_status = 6;
        $booking->save();

        sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'SELLER_SERVICE_WORK_DISPUTE',true, bookingId:$booking->id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Work Service Dispute for service '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

        return back()->withNotify([['success', 'Work has been successfully disputed']]);
    }

    public function hireEmployServiceRating(Request $request)
    {
        $request->validate([
            'skills' => 'required|numeric|min:1|max:5',
            'quality' => 'required|numeric|min:1|max:5',
            'availability' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'cooperation' => 'required|numeric|min:1|max:5',
            'likely_rating' => 'nullable|numeric|min:1|max:10',
            'reason' => 'required|string',
            'comment' => 'nullable|string',
            'strengths' => 'nullable|array',  // Handle strengths checkboxes
            'englishProficiency' => 'required|string',  // Handle proficiency rating
        ]);

        DB::beginTransaction(); // Start the transaction
        try {
            $user = Auth::user();
            // Create a new ReviewRating instance and save the ratings
            if(array_key_exists('service_id', $request->all()))
            {
                $rev = ReviewRating::where('service_id',$request->input('service_id'))->latest()->first();
                if(is_null($rev))
                    $rev = new ReviewRating();
            }
            else {
                $rev = new ReviewRating();
            }
            // $rev = new ReviewRating();
            $rev->user_id = $request->buyer_id;
            $rev->pro_id = $request->seller_id;

            $rev->skill = $request->skills;
            $rev->service_id = $request->service_id;
            $rev->quality = $request->quality;
            $rev->availability = $request->availability;
            $rev->communication = $request->communication;
            $rev->cooperation = $request->cooperation;
            $rev->communicationrating = $request->communication;
            $rev->recommendrating = $request->quality;
            $rev->describedrating = $request->availability;

            // Calculate the overall rating
            $rat = ($request->skills + $request->quality + $request->availability + $request->communication + $request->cooperation) / 5;
            $rev->rating = $rat;

            $rev->reason = $request->reason; // Store the reason for ending the contract
            $rev->likely_rating = $request->likely_rating; // Store the likely rating (1-10 scale)
            $rev->review = $request->comment; // Store the comment if available
            $rev->strengths = json_encode($request->strengths); // Store strengths as JSON
            $rev->english_proficiency = $request->englishProficiency; // Store English proficiency rating
            $service_id = ($request->service_id);
            $rev->data_id = $service_id; // Link to the relevant bid/job
            $rev->type = 'service';

            $ratings = [
                'skills' => $request->skills,
                'quality' => $request->quality,
                'availability' => $request->availability,
                'communication' => $request->communication,
                'cooperation' => $request->cooperation,
            ];
            $jobSuccessScore = calculateJobSuccessScore($ratings, $request->likely_rating, $request->reason);
            $rev->jss = $jobSuccessScore;
            $rev->save();
           
            // Mark job as completed
            $service = Service::find($service_id);
            if($service)
            {
                $service->status = 1; // 1 is to available this service for other buyers
                $service->save();
            }
            // Update booking status
            $booking = Booking::where('service_id', $service_id)
            ->where('buyer_id', $request->buyer_id)
            ->where('seller_id', $request->seller_id)
            ->whereNull('job_id')
            ->latest()
            ->first();
            if ($booking) {
                $booking->working_status = 2;
                $booking->status = 6;
                $booking->is_endcontract = 1;
                $booking->updated_at = now();
                //deduct payment from buyer and pay to seller
                $booking->save();
                // sendJobEmailWithNotification($booking?->buyer_id, $booking->seller_id, $booking->job_id, 'CONTRACT_HAS_ENDED', false, $offer->job_id);
            }

            sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'SELLER_SERVICE_WORK_REVIEWED',false, bookingId:$booking->id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Reviewed Service '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

            // Send email notification
            // dd(sendJobEmailWithNotification($booking->buyer_id, $booking->seller_id, $booking->job_id, 'CONTRACT_HAS_ENDED', false, $offer->bid_id));
            DB::commit(); // Commit the transaction


            $notify[] = ['success', 'Contract ended successfully'];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback all changes if any error occurs
            // Log the error for debugging
             Log::error(
                'Error ending contract: ' . $e->getMessage() .
                ' in ' . $e->getFile() .
                ' at line ' . $e->getLine()
            );

            // Return error message to the user
            $notify[] = ['error', $e->getMessage()];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function hireEmployratingbidedit(Request $request)
    {
        $rev = ReviewRating::findOrFail($request->review_id);
        $skill = $request->skill;
        $rat1 = $request->rating1;
        $rat2 = $request->rating2;
        $rat3 = $request->rating3;
        $rat = ($skill + $rat1 + $rat2 + $rat3) / 4;
        $rev->rating = $rat;
        $rev->review = $request->comment;
        $rev->skill = $request->skill;
        $rev->communicationrating = $request->rating1;
        $rev->recommendrating = $request->rating2;
        $rev->describedrating = $request->rating3;
        $rev->reason = $request->reason;
        $rev->type = 'bid';
        $rev->reply = '';
        $rev->save();



        $notify[] = ['success', 'Contract Reviewd successfully'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployratingofferedit(Request $request)
    {
        // print_r($request->all());exit;
        // $offer=Acceptedoffer::where('id',$request->data_id)->firstOrFail();
        // echo $offer->id;exit;
        $rev = ReviewRating::findOrFail($request->review_id);
        $skill = $request->skill;
        $rat1 = $request->rating1;
        $rat2 = $request->rating2;
        $rat3 = $request->rating3;
        $rat = ($skill + $rat1 + $rat2 + $rat3) / 4;
        $rev->rating = $rat;
        $rev->review = $request->comment;
        $rev->skill = $request->skill;
        $rev->communicationrating = $request->rating1;
        $rev->recommendrating = $request->rating2;
        $rev->describedrating = $request->rating3;
        $rev->reason = $request->reason;
        $rev->type = 'offer';
        $rev->reply = '';
        $rev->save();



        $notify[] = ['success', 'Contract Reviewd successfully'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployratingservice(Request $request)
    {
        // Validate request
        $request->validate([
            'buyer_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'data_id' => 'required|numeric', // Booking ID
            'skill' => 'required|numeric|min:1|max:5',
            'rating1' => 'required|numeric|min:1|max:5', // Communication
            'rating2' => 'required|numeric|min:1|max:5', // Recommendation
            'rating3' => 'required|numeric|min:1|max:5', // Description
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Fetch service/provider
            $provider = DB::table('services')->where('id', $request->service_id)->firstOrFail();

            // Prepare review
            $rev = new ReviewRating();
            $rev->user_id = $request->buyer_id;
            $rev->service_id = $request->service_id;
            $rev->provider_id = $provider->user_id ?? null;
            $rev->skill = $request->skill;
            $rev->communicationrating = $request->rating1;
            $rev->recommendrating = $request->rating2;
            $rev->describedrating = $request->rating3;
            $rev->rating = ($request->skill + $request->rating1 + $request->rating2 + $request->rating3) / 4;
            $rev->review = $request->comment;
            $rev->data_id = $request->data_id; // Booking ID
            $rev->type = 'service';
            $rev->save();

            // Mark booking as contract ended
            $offer = Booking::where('id', $request->data_id)->firstOrFail();
            $offer->is_endcontract = 1;
            $offer->status = 3; // Optional: mark completed (depends on your app logic)
            $offer->save();

            DB::commit();

            $notify[] = ['success', 'Service reviewed successfully'];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service review error: ' . $e->getMessage());
            $notify[] = ['error', 'Something went wrong. Please try again!'];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function changeExtensionServiceStatus( Request $request )
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'service_id' => 'required|exists:services,id',
                'buyer_id' => 'required|exists:users,id',
                'seller_id' => 'required|exists:users,id',
            ]);
            
            $service_extension = ServiceExtension::where('booking_id', $request->booking_id)
                ->where('service_id', $request->service_id)
                ->where('buyer_id', $request->buyer_id)
                ->where('seller_id', $request->seller_id)
                ->where('is_approved', 0)
                ->firstOrFail();
            $from = Carbon::parse($service_extension->from_extension_date);
            $to   = Carbon::parse($service_extension->upto_extension_date);

            $days = $from->diffInDays($to);

            $daysInclusive = $from->diffInDays($to) + 1;

            $status = $request->status == 'accept' ? 1 : 2; // 1 = approved, 2 = rejected

            if ($service_extension) {
                $service_extension->is_approved = $status;
                $service_extension->change_status_date = Carbon::now()->format('Y-m-d');
                $service_extension->save();
            }

            $booking = Booking::where([
                'id' => $request->booking_id,
                'service_id' => $request->service_id,
                'buyer_id' => $request->buyer_id,
                'seller_id' => $request->seller_id,
            ])
            ->firstOrFail();

            
            if ($status == 1) {
                $booking->extra_revisions+=1;
                $booking->is_extension_approved = 1;
                $booking->is_extension_requested = 2; // Mark as no longer requested
                $booking->service_days += $daysInclusive;
                sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Buyer accepted the requested revision. '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));

            } else {
                $booking->is_extension_approved = 2;
                $booking->is_extension_requested = 2; // Mark as no longer requested
                sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Buyer rejected the requested revision. '.$booking?->service?->title, route('admin.service.details',['id'=>$booking->service_id]));
            }
            $booking->save();
            DB::commit();

            sendServiceEmailWithNotification($user->id, $booking?->seller_id, $booking?->service_id, 'BUYER_DECISION_ON_EXTENSION',true, bookingId:$booking->id); // buyer ko send hoga

            $notify[] = ['success', "Extension ".ucfirst($request->status) . ' Successfully'];
            return redirect()->back()->withNotify($notify);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Extension request error: ' . $e->getMessage());
            $notify[] = ['error', 'Something went wrong. Please try again!'];
            return redirect()->back()->withNotify($notify);
        }
    }

    protected function getMembershipFee($userId)
    {
        if (!$userId) {
            return -1;
        }

        $user = User::where(['id' => $userId])->first();

        if (!$user) {
            return -1;
        }

        if ($user->is_freelancer == 1) {
            $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            return $usermembership[0]->fee;
        }

        if ($user->is_employer == 1) {
            $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            return $usermembership1[0]->fee;
        }

        return 0;
    }

     public function sendServiceDisputeToAdmin(Request $request)
    {
        $dispute = service_dispute_data::findOrFail($request->dispute_id);
        $dispute->status = '2';
        $dispute->send_by = "Buyer";
        $dispute->save();

        $disputeDataRecords = Servicedisputeoffers::where('dispute_id', $dispute->id)
            ->where('status', '0')  // Only select records with status 0
            ->get();

        foreach ($disputeDataRecords as $disputeData) {
            $disputeData->status = '2';
            $disputeData->save();
        }


        try {
            //sendJobAdminNotification($user, $job, 'BUYER_CREATE_NEW_JOB');
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute has been sent to admin'];
        return back()->withNotify($notify);
    }

}
