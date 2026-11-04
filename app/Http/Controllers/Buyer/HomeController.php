<?php

namespace App\Http\Controllers\Buyer;

use App\Services\Membership;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\Employermembershiptransactions;
use App\Models\Newskillbyuser;
use App\Models\User;
use App\Models\Skill;
use App\Models\BidsLog;
use App\Models\Booking;
use App\Models\AdminNotification;
use App\Models\Service;
use App\Models\Jobskill;
use App\Models\Software;
use App\Models\UserOffer;
use App\Models\Userbiding;
use App\Models\Acceptedbid;
use App\Models\ActivityLog;
use App\Models\Transaction;
use App\Models\UserCompany;
use App\Models\ExtraService;
use App\Models\FavoriteItem;
use App\Models\Notification;
use App\Models\ReviewRating;
use App\Models\WorkDelivery;
use Illuminate\Http\Request;
use App\Models\Acceptedoffer;
use App\Models\Jobdisputebid;
use App\Models\UserEducation;
use App\Traits\UserCrossJobs;
use App\Models\GeneralSetting;
use App\Models\StripeResponse;
use App\Rules\FileTypeValidate;
use App\Services\PaymentService;
use App\Models\Buyercounteroffer;
use App\Models\Jobdisputebiddata;
use App\Models\UserCertification;
use App\Models\Jobworkdeliverybid;
use App\Models\Sellercounteroffer;
use App\Models\Servicedisputedata;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Jobworkdeliveryoffer;
use App\Models\JobworkIntervalEntry;
use App\Models\Servicedisputeoffers;
use App\Traits\UbPriceStrategyTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Buyercounterofferdata;
use App\Models\Employerservicescount;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sellercounterofferdata;
use App\Models\Jobworkdeliveryhourlybid;
use App\Models\Jobworkdeliveryhourlyoffer;
use App\Models\GatewayCurrency;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Http\Controllers\Gateway\Stripe\ProcessController;
use App\Models\Category;
use App\Models\UserMilestone;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;


class HomeController extends Controller
{

    use UbPriceStrategyTrait, UserCrossJobs;

    public function __construct(private MembershipService $membershipService)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function dispute( Request $request )
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Dispute";
        $currentUserId = auth()->id();

        // --------------------------
        // 1. Job Disputes
        // --------------------------
        $jobDisputes = DB::table('job_dispute_bid')
            ->joinSub(
                DB::table('bookings')
                    ->select('job_id', 'order_number', 'status', 'created_at')
                    ->groupBy('job_id')
                    ->orderBy('created_at', 'desc'),
                'latest_bookings',
                'job_dispute_bid.job_id',
                '=',
                'latest_bookings.job_id'
            )
            ->join('jobs', 'job_dispute_bid.job_id', '=', 'jobs.id')
            ->where('job_dispute_bid.buyer_id', '=', $currentUserId)
            ->select(
                'job_dispute_bid.*',
                'latest_bookings.order_number as booking_order',
                'latest_bookings.status as booking_status',
                'latest_bookings.created_at as booking_date',
                'jobs.title as job_title',
                DB::raw("'job' as dispute_type")
            )
            ->get();

        // --------------------------
        // 2. Service Disputes
        // --------------------------
        $serviceDisputes = DB::table('service_dispute_data')
            ->join('services', 'service_dispute_data.service_id', '=', 'services.id')
            ->join('bookings', 'service_dispute_data.booking_id', '=', 'bookings.id')
            ->where('service_dispute_data.buyer_id', '=', $currentUserId)
            ->select(
                'service_dispute_data.*',
                'bookings.order_number as booking_order',
                'bookings.status as booking_status',
                'bookings.created_at as booking_date',
                'services.title as job_title',
                DB::raw("'service' as dispute_type")
            )
            ->get();

        // --------------------------
        // 3. Merge both results
        // --------------------------
        $disputes = $jobDisputes->merge($serviceDisputes)->sortByDesc('booking_date');
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Disputes'],
        ];

        return view($this->activeTemplate . 'user.buyer.dispute', compact('pageTitle', 'disputes', 'breadcrumbs'));
    }
    public function index(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();

        $pageTitle = "Dashboard";
        $emptyMessage = "No data found";
        // Retrieve necessary data in fewer queries
        $transactions = Transaction::where('user_id', $user->id)->where('status',1)->orderBy('id', 'DESC')->take(10)->get();
        $totaltransactions = (int) Transaction::where('user_id', $user->id)->where('status',1)->sum('amount');
        $currentBalance = (int) $user->balance;
        // Combining job counts
        $totalJob = Job::where('user_id', $user->id)->count();
        $ongoingJob = Job::where('user_id', $user->id)->where('status', 1)->where('is_completed', 0)->whereHas('userBiding', function ($query) {
            $query->where('seller_offer_responce', 2);
        })->count();
        // Combining regular and featured jobs
        $regularJob = Employerservicescount::where('user_id', $user->id)->sum('normal_jobs');
        $featuredJob = Employerservicescount::where('user_id', $user->id)->sum('featured_jobs');
        $availableJob = $regularJob + $featuredJob;
        $ongoingService = Service::where('user_id', $user->id)->sum('status');
        $serviceBookings = Booking::where('buyer_id', $user->id)->where('status', '!=', 0)->where('booking_type', 'byservice')->count();
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('seller_offer_responce', 2)->count();
        $completedJobs = Job::where('user_id', $user->id)->where('status', 1)->where('is_completed', 1)->count();
        $bpcount = UserCompany::where('user_id', $user->id)->count();
        $profile_completenes = $bpcount > 0 ? 100 : 50;

        $is_membership_expired = (new Membership())->isMembershipExpired($user);
        $expiry_date = (new Membership())->membershipExpireDate($user);
        $membership_id = Employermembershiptransactions::where("user_id", $user->id)->value('membership_id');
        $is_free_membership = (new Membership())->isFreeMembership($user, $membership_id);
        $show_alert = !$is_free_membership && $expiry_date <= now()->addDays(2);

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Dashboard'],
        ];
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view($this->activeTemplate . 'user.buyer.ajax-dashboard', compact(
                    'expiry_date',
                    'is_free_membership',
                    'ongoingService',
                    'user',
                    'pageTitle',
                    'emptyMessage',
                    'transactions',
                    'totaltransactions',
                    'totalJob',
                    'serviceBookings',
                    // 'softwarePurchases',
                    'hireEmploys',
                    // 'bidingresponses',
                    // 'userjobs',
                    // 'assignedjobs',
                    'completedJobs',
                    'currentBalance',
                    'availableJob',
                    'regularJob',
                    'featuredJob',
                    'ongoingJob',
                    'bpcount',
                    'profile_completenes',
                    'show_alert'
                ))->render(),
                'breadcrumbs' => view('components.breadcrumbs', compact('breadcrumbs','expiry_date'))->render(),
            ]);
        }
        // Default view for normal requests
        return view($this->activeTemplate . 'user.buyer.dashboard', compact(
            'ongoingService',
            'user',
            'pageTitle',
            'emptyMessage',
            'transactions',
            'totaltransactions',
            'totalJob',
            'serviceBookings',
            // 'softwarePurchases',
            'hireEmploys',
            // 'bidingresponses',
            // 'userjobs',
            // 'assignedjobs',
            'completedJobs',
            'currentBalance',
            'availableJob',
            'regularJob',
            'featuredJob',
            'ongoingJob',
            'bpcount',
            'profile_completenes',
            'breadcrumbs',
            'is_membership_expired',
            'expiry_date',
            'membership_id',
            'is_free_membership',
            'show_alert',
        ));
    }

    public function serviceBookingItem()
    {
        $user = Auth::user();
        $pageTitle = "Service booking list";
        $emptyMessage = "No data found";
        $serviceBookings = Booking::where('buyer_id', $user->id)->where('booking_type', 'service')
            ->whereNotNull('service_id')->latest()->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function serviceBookingDetails($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = "Service booking details";
        $emptyMessage = "No data found";

        // Fetch the booking record
        $booking = Booking::where('buyer_id', $user->id)
            ->where('id', decrypt($id))
            ->whereNotNull('service_id')
            ->firstOrFail();

        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');
        return view($this->activeTemplate . 'user.buyer.service_booking_details', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'addonPricesSum' => $addonPricesSum ?? 0,
            'paymentProcessingFee' => $fees['paymentProcessingFee'],
            'calculatedBuyerServiceFee' => $fees['calculatedBuyerServiceFee']
        ]);
    }

    public function buyerServiceBookingsCancelled()
    {
        $user = Auth::user();
        $pageTitle = "Cancelled Service Bookings";
        $emptyMessage = "No cancelled bookings found";
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->where('status', 'cancelled')
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Cancelled Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }
    public function buyerServiceBookingsCompleted()
    {
        $user = Auth::user();
        $pageTitle = "Completed Service Bookings";
        $emptyMessage = "No completed bookings found";
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->where('status', 'completed')
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Completed Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function buyerServiceBookingsActive()
    {
        $user = Auth::user();
        $pageTitle = "Active Service Bookings";
        $emptyMessage = "No active bookings found";
        $serviceBookings = Booking::where('buyer_id', $user->id)
            ->where('booking_type', 'service')
            ->whereNotNull('service_id')
            ->where('status', '3') // yahan pe status ka column use kar rahe hain
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Active Service Booking'],
        ];
        return view($this->activeTemplate . 'user.buyer.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
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
        $file = $work->responce_file;
        if(!is_null($file))
        {
            $path = imagePath()['workFile']['path'];
            $full_path = $path . '/' . $file;
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $mimetype = mime_content_type($full_path);
            header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
            header("Content-Type: " . $mimetype);
            return readfile($full_path);
        }
        return redirect()->back();
    }
    public function serviceBookingDetailsContract($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = "Service booking details contract";
        $emptyMessage = "No data found";

        $booking = Booking::where('buyer_id', $user->id)
            ->where('id', decrypt($id))
            ->whereNotNull('service_id')
            ->firstOrFail();

        // Handle extra service price if available
        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        // Fetch add-ons for this booking (all columns)
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();

        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');

        return view($this->activeTemplate . 'user.buyer.service_booking_details_contract', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'paymentProcessingFee' => $fees['paymentProcessingFee'],
            'calculatedBuyerServiceFee' => $fees['calculatedBuyerServiceFee'],
            'extraPrice' => $extraPrice,
            'addOns' => $addOns,
            'packagePrice' => $fees['packagePrice'],
            'addOnPrice' => $fees['addOnsPrice'],
            'total' => $fees['total'],
        ]);
    }

    public function serviceBookingDetailsWorkprogress($id)
    {
        $user = Auth::user();
        $pageTitle = "Service booking progress";
        $emptyMessage = "No data found";

        $booking = Booking::where('buyer_id', $user->id)->where('id', decrypt($id))
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
            ->pluck('booking_id') // Assuming dispute is tied to booking
            ->toArray();
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
            'seller'
        ));
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

    public function serviceBookingDetailsWorkcompleted1($id)
    {
        $user = Auth::user();
        $pageTitle = "Service booking completed";
        $emptyMessage = "No data found";
        $booking = Booking::where('buyer_id', $user->id)->where('id', decrypt($id))
            ->whereNotNull('service_id')->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->where('status', 6)->get();
        $allworkscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->where('status', 6)->count();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();
        $workscount = WorkDelivery::where('booking_id', $id)->where('service_id', $booking->service_id)->count();

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
    public function serviceBookingDetailsWorkcompleted($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = "Service booking Invoice";
        $emptyMessage = "No data found";
        $booking = Booking::where('buyer_id', $user->id)->where('id', decrypt($id))->whereNotNull('service_id')->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        // Fetch add-ons for this booking (all columns)
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();
        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');

        return view(
            $this->activeTemplate . 'user.buyer.service_booking_details_completed',
            [
                'pageTitle' => $pageTitle,
                'emptyMessage' => $emptyMessage,
                'booking' => $booking,
                'paymentProcessingFee' => $fees['paymentProcessingFee'],
                'calculatedBuyerServiceFee' => $fees['calculatedBuyerServiceFee'],
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'packagePrice' => $fees['packagePrice'],
                'addOnPrice' => $fees['addOnsPrice'],
                'total' => $fees['total'],
                'totalBeforeFee' => $fees['totalBeforeFee']
            ]
        );
    }

    public function softwarePurchases()
    {
        $user = Auth::user();
        $pageTitle = "Service purchases list";
        $emptyMessage = "No data found";
        $softwarePurchases = Booking::where('user_id', $user->id)->where('status', '!=', 0)->whereNotNull('software_id')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.buyer.software_purchases', compact('pageTitle', 'emptyMessage', 'softwarePurchases'));
    }


    public function buyerSoftwareFileDownload($id)
    {
        $software = Software::where('id', decrypt($id))->firstOrFail();
        $file = $software->upload_software;
        $path = imagePath()['uploadSoftware']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function buyerSoftwareDocumentFile($id)
    {
        $software = Software::where('id', decrypt($id))->firstOrFail();
        $file = $software->document_file;
        $path = imagePath()['document']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }
    public function openJobs(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.authorization');
        }
        $pageTitle = "All Jobs";
        // $jobs = Job::select('jobs.*')->withCount('userBiding')->where('user_id', $user->id)->where('status', 1)->orderBy('created_at', 'desc')
        $jobs = Job::select('jobs.*')->withCount('userBiding')->where('user_id', $user->id)->orderBy('created_at', 'desc')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->paginate(10)
            ->withQueryString();
        $jobs->getCollection()->transform(function ($job) {
            $job->encrypted_job_id = Crypt::encrypt($job->id);
            $job->created_date = $job->created_at->format('d-M-Y');
            return $job;
        });
        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Posted Jobs & Bids', 'url' => route('user.buyer.hire.open')],
        //     ['title' => 'All Jobs'],
        // ];
        if ($request->ajax()) {
            return view('templates.basic.user.buyer.ajax_job_list', compact('pageTitle', 'jobs', 'user'));
        }

        return view('templates.basic.user.buyer.job_list', compact('pageTitle', 'jobs', 'user'));
    }
    // need to delete
    public function hireEmploy(Request $request)
    {
        $user = Auth::user();
        $pageTitle = "Hiring";
        $jobs = Job::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('templates.basic.user.buyer.ajax_job_list', compact('pageTitle', 'jobs'));
        }
        return view('templates.basic.user.buyer.job_list', compact('pageTitle', 'jobs'));
    }

    public function offeredEmploy()
    {
        $user = Auth::user();
        $pageTitle = "offered employ list";
        $emptyMessage = "No data found";
        // $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
        //     $q->where('user_id', $user->id);
        // })->latest()->paginate(getPaginate());
        // $hireEmploys = UserOffer::where('buyer_id', $user->id)->latest()->orderBy('job_id', 'desc')->groupBy('job_id')->get();
        $hireEmploys = UserOffer::whereHas('job', function ($q) use ($user) {
            $q->where('buyer_id', $user->id)->where('is_completed', '!=', 1);
        })->latest()->orderBy('job_id', 'desc')->groupBy('job_id')->get();
        return view($this->activeTemplate . 'user.buyer.offered_employ', compact('pageTitle', 'emptyMessage', 'hireEmploys'));
    }


    public function hireEmployoffers($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ offers";
        $emptyMessage = "No data found";
        $singlejob = Job::where('id', decrypt($id))->first();
        $singlejoboffers = UserOffer::where('job_id', decrypt($id))->latest()->get();
        return view($this->activeTemplate . 'user.buyer.hire_employ_offers', compact('pageTitle', 'emptyMessage', 'singlejoboffers', 'singlejob'));
    }


    public function hireEmploybids(Request $request,$id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $singlejob = Job::where('id', decrypt($id))->first();
        $user = User::find($singlejob?->user_id);
        $total = (float)$singlejob->amount;
        $is_redirect_to_deposit_money = false;
        if (!$singlejob) {
            return redirect()->back();
        }

        if ($total > (float)$user->balance && $user->balance  == 0.0) {
            $is_redirect_to_deposit_money = true;
        }

        // Fetch all bids related to this job along with user and milestones
        $bids = Userbiding::where('job_id', $singlejob->id)->with(['user', 'milestones'])->get();

        // Fetch job skills
        $jobSkills = Jobskill::where('job_id', $singlejob->id)->pluck('skill_id');
        $allSkills = Skill::whereIn('id', $jobSkills)->get(); // Get all skill details
        $newSkills = Newskillbyuser::where('term_id', $singlejob->id)->where('type',0)->get(); // Get all skill details

        foreach ($bids as $bid) {
            $bidUser = $bid->user;

            // Calculate average rating for the user who placed the bid
            $userRating = ReviewRating::where('pro_id', $bidUser->id)->avg('rating');

            // Calculate Job Success Score (JSS) for the bidder
            $jss = ReviewRating::where('pro_id', $bidUser->id)->avg('jss');

            // Calculate completed projects based on the Booking model
            $completedProjects = Booking::where('status', 3)
                ->where('working_status', 1)->where("seller_id", $bidUser->id)->count();

            // Find matching skills for the bidder
            $matchingSkills = Skill::whereIn('id', function ($query) use ($jobSkills, $bidUser) {
                $query->select('skill_id')
                    ->from('user_profile_skills')
                    ->where('user_id', $bidUser->id)
                    ->whereIn('skill_id', $jobSkills);
            })->pluck('id');

            // Also get the *names* of the bidder's skills (to compare against new skills)
            $bidderSkillNames = DB::table('user_profile_skills')
                ->join('skills', 'skills.id', '=', 'user_profile_skills.skill_id')
                ->where('user_profile_skills.user_id', $bidUser->id)
                ->pluck('skills.name')
                ->map(fn($name) => strtolower(trim($name))); // normalize for better matching

            // Map system (regular) skills
            $mappedAllSkills = $allSkills->map(function ($skill) use ($matchingSkills) {
                return [
                    'name' => $skill->name,
                    'isMatched' => $matchingSkills->contains($skill->id),
                    'type' => 'system',
                ];
            });

            // Map new (extra) skills by comparing names
            $mappedNewSkills = $newSkills->map(function ($skill) use ($bidderSkillNames) {
                return [
                    'name' => $skill->name,
                    'isMatched' => $bidderSkillNames->contains(strtolower(trim($skill->name))), // name-based match
                    'type' => 'extra',
                ];
            });

            // Merge both
            $bid->allSkills = $mappedAllSkills->merge($mappedNewSkills);

            // Add additional details to the bid object
            $bid->bidUser = $bidUser;
            $bid->userRating = round($userRating, 1);
            $bid->jss = round($jss, 2);
            $bid->completedProjects = $completedProjects;
        }

        $pageTitle = "Bids";
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Open Jobs', 'url' => route('user.buyer.hire.open')],
            ['title' => 'Bids'],
        ];
        return view('templates.basic.user.buyer.job_bids_list', compact(
            'pageTitle',
            'user',
            'bids',
            'singlejob',
            'breadcrumbs',
            'is_redirect_to_deposit_money'
        ));
    }

    public function hireEmploybidsList(Request $request)
    {

        $user = Auth::user();
        $data = $request->all();
        $id = $data['encrypt_job_id'];

        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified

        $query = Userbiding::where('job_id', decrypt($id))
            ->where('is_cancel_bid', '0')->orderBy('priorty', 'desc')
            ->with(['user', 'biddingStatus', 'job']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('id', 'desc');
        $data = $query->paginate(10, ['*'], 'page', $page); // Adjust the number 10 to however many results per page you want

        $platformFee = $this->membershipService->getMembershipFee($user->id);

        $data->getCollection()->transform(function ($item) use ($platformFee) {
            $item = $this->calculatePriceAndFormat($item);
            $item = $this->calculatePlatformfeePercentage($item, $platformFee);
            $item->matched_skills = $this->getMatchedSkills($item->user_id, $item->job_id);
            $item->encrypted_job_id = Crypt::encrypt($item->job_id);
            $item->encrypted_id = Crypt::encrypt($item->id);
            $item->created_date = Carbon::parse($item->created_at)->format('Y-m-d');
            return $item;
        });

        return response()->json($data);
    }

    public function hireEmploybidcanceloffer(Request $request, $offerId)
    {
        $offerId = $offerId;
        $counterofferid = DB::table('buyer_counter_offers')->where('id', $offerId)->first();
        $counterdata = Buyercounterofferdata::where('counter_offer_id', $counterofferid->id)->get();
        foreach ($counterdata as $data) {
            $datadel = Buyercounterofferdata::find($data->id);
            $datadel->delete();
        }
        $offerdel = Buyercounteroffer::find($counterofferid->id);
        $offerdel->delete();
        // Buyercounterofferdata::where('bid_id',$id)->where('job_id',$request->sendofferjob_id)->where('sender_id',$request->sendoffersender_id)->delete->where('reciever_id',$request->sendofferrec_id)->delete();
        $bidId = $counterofferid->bid_id;
        $senderId = $counterofferid->sender_id;
        $receiverId = $counterofferid->reciever_id;
        $jobId = $counterofferid->job_id;
        $sent = Userbiding::find($bidId);
        $sent->is_buyer_offer_sent = '0';
        $sent->save();

        sendJobEmailWithNotification($senderId, $receiverId, $jobId, 'POSITION_APPLIED_CLOSED');
        //Vikash Change
        $notify[] = ['success', 'Offer successfully canceled'];
        return redirect()->back()->withNotify($notify);
    }

    public function hireEmploybidcancelbid(Request $request, $offerId = null)
    {
        $offerId = $offerId ?? $request->cancelbid_id;

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Fetch the buyer counter offer
            $counterOffer = DB::table('buyer_counter_offers')->where('id', $offerId)->first();
            if (!$counterOffer) {
                $notify[] = ['error', 'Invalid Counter Offer'];
                return redirect()->back()->withNotify($notify);
            }

            // // Update the status of the buyer_counter_offers to 1
            DB::table('buyer_counter_offers')
            ->where('id', $counterOffer->id)
            ->update([
                'status' => 2,
                'rejected_reason' => $request->cancenreason ?? 'Bid cancelled by buyer',
            ]);

            // // Update counter offer data statuses to 2
            $counterDataRecords = Buyercounterofferdata::where('counter_offer_id', $counterOffer->id)->get();
            if (count($counterDataRecords) > 0) {
                foreach ($counterDataRecords as $data) {
                    $data->status = 2;
                    $data->save();
                }
            }

            // // Update the bid record
            $bid = Userbiding::find($counterOffer->bid_id);
            if (!$bid) {
                $notify[] = ['error', 'Invalid Bid'];
                return redirect()->back()->withNotify($notify);
            }
            $bid->is_buyer_offer_sent = '0';
            $bid->save();

            // // Validate and retrieve the job
            $job = Job::find($bid->job_id);
            if (!$job) {
                $notify[] = ['error', 'Invalid Job'];
                return redirect()->back()->withNotify($notify);
            }

            sendJobEmailWithNotification($job->user_id, $bid->user_id, $job->id, 'PROPOSAL_BID_REJECTED');
            sendAdminRealTimeNotification(Auth::user(), Auth::user()->username.' Bid has been cancelled on '.$job?->title, route('admin.job.details', ['id' => $job->id]));

            // Commit the transaction
            DB::commit();

            // Notify the user of success
            $notify[] = ['success', 'Bid successfully canceled'];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error in hireEmploybidcancelbid: ' . $e->getMessage());

            // Notify the user of the failure
            $notify[] = ['error', 'Something went wrong. Please try again.'];
            return redirect()->back()->withNotify($notify);
        }
    }


    public function hireEmployworkspace($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ workspace";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();
            return view($this->activeTemplate . 'user.buyer.hire_employ_workspace_bid', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedbid', 'accepteddata'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
            return view($this->activeTemplate . 'user.buyer.hire_employ_workspace_offer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'singlejob', 'accepteddata', 'acceptedoffer'));
        }
    }

    public function hireEmploytimesheet($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ Timesheet";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();
            return view($this->activeTemplate . 'user.buyer.hire_employ_hourlytimesheet_bid', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedbid', 'accepteddata'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
            return view($this->activeTemplate . 'user.buyer.hire_employ_hourlytimesheet_offer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'accepteddata'));
        }
    }

    public function activeJobOverview($id)
    {
        $pageTitle = 'Active Jobs';
        return view('templates.basic.user.buyer.components.hire-employee.active.active-jobs-overview', compact('pageTitle'));
    }


    public function hireEmployacceptedbidoffer($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ Accepted bid or offer";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate . 'user.buyer.hire_employ_acceptedbidoffer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedbid', 'accepteddata'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
            // print_r($accepteddata);exit;
            return view($this->activeTemplate . 'user.buyer.hire_employ_acceptedbidoffer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'accepteddata'));
        }
    }
    public function hireinvoicesec($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ Accepted bid or offer";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate . 'user.buyer.hire_employ_invoicetab', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedbid', 'accepteddata'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
            // print_r($accepteddata);exit;
            return view($this->activeTemplate . 'user.buyer.hire_employ_invoicetab', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'accepteddata'));
        }
    }
    public function hirerateandreview($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ Rate & Review";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate . 'user.buyer.hire_employ_rateandreview', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedbid', 'accepteddata'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();
            // print_r($accepteddata);exit;
            return view($this->activeTemplate . 'user.buyer.hire_employ_rateandreviewoffer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'accepteddata'));
        }
    }
    public function generateInvoicePDF($id)
    {

        $pdf = PDF::loadView('myPDF');

        return $pdf->download('nicesnippets.pdf');
    }
    public function hireEmployacceptedofferoffer($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employ Accepted bid or offer";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        $accepteddata = DB::table('accepted_offer')->where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $acceptedoffer->buyer_id)->where('seller_id', $acceptedbid->user_id)->first();
        // print_r($accepteddata);exit;
        return view($this->activeTemplate . 'user.buyer.hire_employ_acceptedofferoffer', compact('pageTitle', 'emptyMessage', 'hireEmploys', 'singlejob', 'acceptedoffer', 'accepteddata'));
    }
    public function hireEmploycompleted()
    {
        $user = Auth::user();
        $pageTitle = "Hire employ completed";
        $emptyMessage = "No data found";
        // $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
        //     $q->where('user_id', $user->id)->where('is_completed',1);
        // })->latest()->paginate(getPaginate());
        $hireEmploys = Job::where('user_id', $user->id)->where('is_completed', 1)->latest()->get();

        return view($this->activeTemplate . 'user.buyer.hire_employ_completed', compact('pageTitle', 'emptyMessage', 'hireEmploys'));
    }

    public function hireEmployclosed()
    {
        $user = Auth::user();
        $pageTitle = "Hire employ closed";
        $emptyMessage = "No data found";
        // $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
        //     $q->where('user_id', $user->id)->where('status',2);
        // })->latest()->paginate(getPaginate());
        $hireEmploys = Job::where('user_id', $user->id)->where('status', 2)->latest()->get();

        return view($this->activeTemplate . 'user.buyer.hire_employ_closed', compact('pageTitle', 'emptyMessage', 'hireEmploys'));
    }

    public function hireEmployhourlytimesheet()
    {
        $user = Auth::user();
        $pageTitle = "Hire employ hourlytimesheet";
        $emptyMessage = "No data found";
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'user.buyer.hire_employ_hourlytimesheet', compact('pageTitle', 'emptyMessage', 'hireEmploys'));
    }


    public function hireEmployDetails($id)
    {
        $user = Auth::user();
        $pageTitle = "Hire employe details";
        $emptyMessage = "No data found";
        $booking = UserBiding::where('id', decrypt($id))->first();

        return view($this->activeTemplate . 'user.buyer.hire_employ_details', compact('pageTitle', 'emptyMessage', 'booking'));
    }


    public function serviceFavoriteItem()
    {
        $user = Auth::user();
        $pageTitle = "Service favorite list";
        $emptyMessage = "No data found";
        $favoriteServices = FavoriteItem::whereNotNull('service_id')->where('user_id', $user->id)->with('service')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.buyer.favorite_service', compact('pageTitle', 'emptyMessage', 'favoriteServices'));
    }

    public function softwareFavoriteItem()
    {
        $user = Auth::user();
        $pageTitle = "Software favorite list";
        $emptyMessage = "No data found";
        $favoriteSoftwares = FavoriteItem::whereNotNull('software_id')->where('user_id', $user->id)->with('software')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.buyer.favorite_software', compact('pageTitle', 'emptyMessage', 'favoriteSoftwares'));
    }

    /*
     * Deposit History
     */
    public function depositHistorytwo(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $user->is_payment_verified = 1;
        $user->save();

        $search = $request->input('search');

        // Display deposit history
        $pageTitle = 'Deposit History';
        $emptyMessage = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id', 'desc')
        ->when(!is_null($search),function($query) use($search) {
            $query->where('trx', 'LIKE', '%' . $search . '%');
        })
        ->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Deposit Histroy'],
        ];
        return view($this->activeTemplate . 'user.deposit_historytwo', compact('pageTitle', 'emptyMessage', 'logs', 'breadcrumbs'));
    }

    public function reportLogs( Request $request )
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Transaction Log";
        $emptyMessage = "No data found";
        $transactions = Transaction::where('user_id', $user->id)->orderby('created_at', 'desc')->paginate(getPaginate());
        $bidsLog = BidsLog::where('user_id', $user->id)->orderby('created_at', 'desc')->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Reports Log'],
        ];
        return view($this->activeTemplate . 'user.buyer.report-logs', compact('pageTitle','breadcrumbs', 'emptyMessage', 'transactions', 'bidsLog'));
    }

    public function transactions(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $search = $request->input('search');
        $user = Auth::user();
        $pageTitle = "Transaction Log";
        $emptyMessage = "No data found";
        $transactions = Transaction::where('user_id', $user->id)
            ->when($search, function ($query) use ($search) {
                $query->where('trx', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('post_balance', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        $bidsLog = BidsLog::where('user_id', $user->id)->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Transections History'],
        ];

        return view($this->activeTemplate . 'user.buyer.transactions', compact('pageTitle', 'emptyMessage', 'transactions', 'bidsLog', 'breadcrumbs'));
    }

    public function proposalApproved(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:approved,cancel'
        ]);
        $user = Auth::user();
        $general = GeneralSetting::first();
        $CheckJob = Userbiding::where('id', $request->order_number)->whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if (!$CheckJob) {
            $notify[] = ['error', 'Invalid Job'];
            return back()->withNotify($notify);
        }
        if ($request->confirm == "approved") {
            $CheckJob->status = 1;
            $CheckJob->working_status = 4;
            $CheckJob->updated_at = Carbon::now();
            $CheckJob->save();
            $notify[] = ['success', 'Accepted'];
            return back()->withNotify($notify);
        } elseif ($request->confirm == "cancel") {
            $CheckJob->status = -1;
            $CheckJob->working_status = 3;
            $CheckJob->save();
            $notify[] = ['success', 'Canceled.'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Invalid Job'];
            return back()->withNotify($notify);
        }
    }

    public function workDeliveryApproved(Request $request)
    {

        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $general = GeneralSetting::first();
        $user = Auth::user();
        if ($request->work_type == "service") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('buyer_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid service booking'];
                return back()->withNotify($notify);
            }
        } elseif ($request->work_type == "jobBiding") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('job_biding_id')->where('user_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid Job order'];
                return back()->withNotify($notify);
            }
        }
        $booking->working_status = 1;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();

        $work = WorkDelivery::where('id', $request->work_id)->firstOrFail();
        $work->status = 6;
        $work->save();

        $notify[] = ['success', 'Work has been Accepted.'];
        return back()->withNotify($notify);
    }
    public function workDeliveryApprovedpayed(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $general = GeneralSetting::first();
        $user = Auth::user();
        if ($request->work_type == "service") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('user_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid service booking'];
                return back()->withNotify($notify);
            }
        } elseif ($request->work_type == "jobBiding") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('job_biding_id')->where('user_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid Job order'];
                return back()->withNotify($notify);
            }
        }
        $booking->status = 3;
        $booking->working_status = 7;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();

        if ($request->work_id != '') {
            //echo "string";exit;
            $work = WorkDelivery::where('id', $request->work_id)->firstOrFail();
            $work->status = 6;
            $work->save();
        }


        $charge = ((($request->totalss) / 100) * $general->charge);
        $payableAmount = (($request->totalss) - $charge);
        if ($booking->service_id) {
            $seller = User::where('id', $booking->service->user_id)->firstOrFail();
            $seller->balance += $payableAmount;
            $seller->income += $payableAmount;
            $seller->save();
            rankUser($seller->id);
        } elseif ($booking->job_biding_id) {
            $seller = User::where('id', $booking->biding->user_id)->firstOrFail();
            $seller->balance += $payableAmount;
            $seller->income += $payableAmount;
            $seller->save();
            rankUser($seller->id);
        }
        $transaction = new Transaction();

        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->amount = $payableAmount;
        $transaction->charge = $charge;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $booking->order_number;
        $transaction->details = "Payment for " . $booking->order_number;
        $transaction->save();

        notify($seller, 'PAYMENT_SELLER', [
            'amount' => getAmount($payableAmount),
            'currency' => $general->cur_text,
            'order_number' => $booking->order_number,
            'post_balance' => getAmount($seller->balance)
        ]);
        // send email
        $service_single = DB::table('services')->where('id', $booking->service_id)->first();
        $emailuser = DB::table('users')->where('id', $service_single->user_id)->first();
        $booker = DB::table('users')->where('id', $booking->user_id)->first();
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


            $order_number = $booking->order_number;
            $type = 'pay';
            $mail->isHTML(true);
            $mail->Subject = 'Booking Payed.';
            $contentBody = view('Emails/Service/serviceworkacceptreject', compact('booker', 'service_single', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Work payment has been payed.'];
        return back()->withNotify($notify);
    }



    public function workDeliveryrejected(Request $request)
    {

        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
        ]);
        $general = GeneralSetting::first();
        $user = Auth::user();
        if ($request->work_type == "service") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('buyer_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid service booking'];
                return back()->withNotify($notify);
            }
        } elseif ($request->work_type == "jobBiding") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('job_biding_id')->where('buyer_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid Job order'];
                return back()->withNotify($notify);
            }
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

        // send email
        $notify[] = ['success', 'Work has been declined.'];
        return back()->withNotify($notify);
    }

    public function workDispute(Request $request, $encbuyerCounterOfferDataId)
    {
        $counterOfferDataId = decrypt($encbuyerCounterOfferDataId);
        $counterOfferData = Buyercounterofferdata::find($counterOfferDataId);
        $jobType = $counterOfferData->buyerCounterOffer->type;
        $jobId = $counterOfferData->job_id;
        $validator = \Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
            'message' => 'required|string|max:255',
            'buyer_amount' => 'required',
            'seller_amount' => 'required',
            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Max 1MB, image only
        ]);
        $disputeAmount = $counterOfferData->amount;
        if ($jobType === 'hourly') {
            $disputeAmount = $request->total_cost;
        }
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;
        $amountequal = $buyer_amount + $seller_amount;
        // if ($amountequal != $disputeAmount) {
        //     return response()->json([
        //         'errors' => ['seller_amount' => ['Offered amount must be equal to dispute amount']]
        //     ], 422);
        // }
        $dispute = $this->handleDisputeCreation($request, $jobType, $disputeAmount, $counterOfferData, $jobId);
        $counterOfferData->milestone_status = 6;
        $counterOfferData->save();
        sendAdminRealTimeNotification(Auth::user(), Auth::user()->username.' rises dispute on job '.$counterOfferData->job?->title, route('admin.job.details', ['id' => $jobId]));

        return response()->json([
            'status' => true,
            'message' => "Work has been disputed successfully"
        ]);
        // $notify[] = ['success', 'Work has been disputed successfully'];
        // return back()->withNotify($notify);
    }

    // New helper function to handle dispute creation logic
    protected function handleDisputeCreation($request, $jobType, $dispute_amount, $counterOfferData, $jobId)
    {
        $dispute_hours = 0;
        $dispute_minutes = 0;
        $buyer_id = 0;
        $seller_id = 0;
        $workId = 0;
        $bidId = 0;
        $offerId = 0;

        if ($jobType === "byproject" || $jobType === "bymilestone") {
            $jobBid = Jobworkdeliverybid::where('data_id', $counterOfferData->id)->where('status', 0)->first();
            if ($jobBid) {
                // Extract data from the job bid
                $workId = $jobBid->id;
                $buyer_id = $jobBid->buyer_id;
                $seller_id = $jobBid->seller_id;
                $bidId = $jobBid->bid_id;
                $offerId = $counterOfferData->id;
                $jobId = $jobBid->job_id;
                // Update the status of the job bid to 6 (disputed)
                $jobBid->status = 6;
                $jobBid->save();
            }
        }
        // Create dispute entry
        $dispute = new Jobdisputebid();
        $dispute->job_id = $jobId;
        $dispute->work_id = $workId;
        $dispute->seller_id = $seller_id;
        $dispute->buyer_id = $buyer_id;
        $dispute->bid_id = $bidId;
        $dispute->offer_id = $offerId;
        $dispute->reason = $request->reason;
        $dispute->dispute_amount = $dispute_amount;
        $dispute->dispute_hours = $dispute_hours;
        $dispute->dispute_minutes = $dispute_minutes;
        $dispute->buyer_amount = $request->buyer_amount;
        $dispute->seller_amount = $request->seller_amount;
        $dispute->seller_message = $request->message;
        $dispute->status = '0';
        $dispute->job_type = $jobType;
        $dispute->work_type = 'bid'; // Set work type dynamically

        $dispute->save();

        // Create dispute data
        $disputeData = new Jobdisputebiddata();
        $disputeData->seller_amount = $request->seller_amount; // Adjust as per your logic
        $disputeData->seller_message = $request->message;
        $disputeData->buyer_amount = $request->buyer_amount; // Adjust as per your logic
        $disputeData->dispute_id = $dispute->id;
        $disputeData->status = '0';
        $disputeData->send_by = 'buyer';
        $disputeData->show_send = '0';
        $disputeData->save();

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dispute', $filename, 'public');
            $dispute->file = $path;
            $dispute->save();
        }
        sendJobEmailWithNotification($buyer_id, $seller_id, $jobId, 'BUYER_DISPUTE_SELLER', false, $jobId, jobDisputeBidId: $dispute->id);
        // sendJobEmailWithNotification($buyer_id, $seller_id, $jobId, 'BUYER_DISPUTE_SELLER', jobDisputeBidId: $dispute->id);
        return $dispute;
    }

    public function disputeIntervals(Request $request)
    {
        DB::beginTransaction(); // Begin transaction for rollback capability

        try {
            // Validate the request
            $validator = \Validator::make($request->all(), [
                'interval_ids' => 'nullable|array',
                'interval_ids.*' => 'exists:job_work_intervals,id', // Ensure intervals exist
                'manual_delivery_hour_ids' => 'nullable|array',
                'manual_delivery_hour_ids.*' => 'exists:job_work_delivery_houly_bid,id', // Ensure manual IDs exist
                'reason' => 'required|string|max:255',
                'message' => 'required|string|max:255',
                'buyer_amount' => 'required|numeric',
                'seller_amount' => 'required|numeric',
                'file' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Max 1MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $intervalIds = $request->interval_ids ?? [];
            $manualDeliveryHourIds = $request->manual_delivery_hour_ids ?? [];

            $disputeAmount = 0;
            $disputeHours = 0;
            $disputeMinutes = 0;

            $pendingIntervals = collect(); // Default empty collection for tracked intervals

            // Process tracked intervals if present
            if (!empty($intervalIds)) {
                $pendingIntervals = JobworkIntervalEntry::whereIn('id', $intervalIds)
                    ->where('status', 1) // Only process intervals with status 1
                    ->get();

                if ($pendingIntervals->isEmpty() && empty($manualDeliveryHourIds)) {
                    return response()->json(['message' => 'No valid intervals or manual delivery hours found to dispute.'], 404);
                }

                // Mark only the selected intervals as disputed (status 6)
                JobworkIntervalEntry::whereIn('id', $intervalIds)->update(['status' => 6]);

                // Calculate dispute hours, minutes, and amount for tracked intervals
                $disputeMinutes += $pendingIntervals->sum(function ($interval) {
                    return \Carbon\Carbon::parse($interval->end_time)
                        ->diffInMinutes(\Carbon\Carbon::parse($interval->start_time));
                });

                $disputeAmount += $pendingIntervals->sum('cost');

                // Update the corresponding Jobworkdeliveryhourlybid entries
                $hourlyBidIds = $pendingIntervals->pluck('hourly_bid_id')->unique();

                foreach ($hourlyBidIds as $hourlyBidId) {
                    $relatedIntervals = JobworkIntervalEntry::where('hourly_bid_id', $hourlyBidId)
                        ->where('status', 1) // Only count intervals with status 1
                        ->get();

                    $totalMinutes = $relatedIntervals->sum(function ($interval) {
                        return \Carbon\Carbon::parse($interval->end_time)
                            ->diffInMinutes(\Carbon\Carbon::parse($interval->start_time));
                    });

                    $totalCost = $relatedIntervals->sum('cost');
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;

                    Jobworkdeliveryhourlybid::where('id', $hourlyBidId)->update([
                        'time' => $hours,
                        'minutes' => $minutes,
                        'total_cost' => $totalCost,
                        'status' => 6,
                    ]);
                }
            }

            // Process manual delivery hour IDs if present
            if (!empty($manualDeliveryHourIds)) {
                $manualHourlyBids = Jobworkdeliveryhourlybid::whereIn('id', $manualDeliveryHourIds)->get();

                if ($manualHourlyBids->isEmpty() && $pendingIntervals->isEmpty()) {
                    return response()->json(['message' => 'No valid manual delivery hours found to dispute.'], 404);
                }

                foreach ($manualHourlyBids as $bid) {
                    // Mark manual delivery hourly bids and their intervals as deleted (status 2)
                    $bid->update(['status' => 2]);
                    JobworkIntervalEntry::where('hourly_bid_id', $bid->id)->update(['status' => 2]);

                    // Add dispute hours, minutes, and amount for manual blocks
                    $disputeMinutes += ($bid->time * 60) + $bid->minutes;
                    $disputeAmount += $bid->total_cost;
                }
            }

            // Convert total minutes to hours and minutes
            $disputeHours += floor($disputeMinutes / 60);
            $disputeMinutes = $disputeMinutes % 60;

            // Use the first interval or manual block to populate dispute details
            $firstIntervalOrManual = $pendingIntervals->first() ?? $manualHourlyBids->first();
            if (!$firstIntervalOrManual) {
                return response()->json(['message' => 'No intervals or manual blocks found to dispute.'], 404);
            }

            $hourlyBid = Jobworkdeliveryhourlybid::findOrFail($firstIntervalOrManual->hourly_bid_id ?? $firstIntervalOrManual->id);
            $status = !empty($manualDeliveryHourIds) ? '7' : '0';

            // Create an entry in Jobdisputebid
            $dispute = new Jobdisputebid();
            $dispute->job_id = $hourlyBid->job_id;
            $dispute->work_id = $hourlyBid->id;
            $dispute->seller_id = $hourlyBid->seller_id;
            $dispute->buyer_id = $hourlyBid->buyer_id;
            $dispute->bid_id = $hourlyBid->bid_id;
            $dispute->offer_id = $hourlyBid->data_id;
            $dispute->reason = $request->reason;
            $dispute->dispute_amount = $disputeAmount;
            $dispute->dispute_hours = $disputeHours;
            $dispute->dispute_minutes = $disputeMinutes;
            $dispute->buyer_amount = $request->buyer_amount;
            $dispute->seller_amount = $request->seller_amount;
            $dispute->seller_message = $request->message;
            $dispute->status = $status;
            $dispute->job_type = 'hourly';
            $dispute->work_type = 'bid';
            $dispute->save();

            // Create an entry in Jobdisputebiddata
            $disputeData = new Jobdisputebiddata();
            $disputeData->seller_amount = $request->seller_amount;
            $disputeData->seller_message = $request->message;
            $disputeData->buyer_amount = $request->buyer_amount;
            $disputeData->dispute_id = $dispute->id;
            $disputeData->status = $status;
            $disputeData->send_by = 'buyer';
            $disputeData->show_send = $status;
            $disputeData->save();

            // Handle file upload if present
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dispute', $filename, 'public');
                $dispute->file = $path;
                $dispute->save();
            }

            // Send notifications to seller
            $this->sendDisputeNotifications($hourlyBid->buyer_id, $hourlyBid->seller_id, $hourlyBid->job_id);

            // Send email notification (optional)
            $this->sendJobEmailWithNotification($hourlyBid->buyer_id, $hourlyBid->seller_id, $hourlyBid->job_id);

            DB::commit(); // Commit the transaction if everything is successful

            return response()->json([
                'message' => 'Intervals have been successfully disputed, and dispute records created.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction in case of an error

            return response()->json([
                'message' => 'An error occurred while processing the dispute.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewdisputeoffers($id)
    {

        $user = Auth::user();
        $pageTitle = "Service booking dispute detail";
        $emptyMessage = "No data found";

        $dispute_data = Servicedisputedata::where('id', decrypt($id))->first();
        $dispute_data_count = Servicedisputedata::where('id', decrypt($id))->count();
        $dispute_offers = Servicedisputeoffers::where('dispute_id', $dispute_data->id)->get();
        $show_send = Servicedisputeoffers::where('dispute_id', $dispute_data->id)->where('show_send', 0)->count();
        $booking = Booking::where('buyer_id', $user->id)->where('id', $dispute_data->booking_id)->whereNotNull('service_id')->firstOrFail();
        $allworks = WorkDelivery::where('booking_id', $booking->id)->where('service_id', $booking->service_id)->get();
        return view($this->activeTemplate . 'user.buyer.service_booking_view_dispute', compact('pageTitle', 'emptyMessage', 'dispute_data', 'dispute_offers', 'booking', 'dispute_data_count', 'allworks', 'show_send'));
    }
    public function workDispute_old(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'work_type' => 'required|in:jobBiding,service',
            'dispute' => 'required|min:50|max:1000'
        ]);
        $general = GeneralSetting::first();
        $user = Auth::user();
        if ($request->work_type == "service") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->where('user_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid service booking'];
                return back()->withNotify($notify);
            }
        } elseif ($request->work_type == "jobBiding") {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('job_biding_id')->where('user_id', $user->id)->first();
            if (!$booking) {
                $notify[] = ['error', 'Invalid job order'];
                return back()->withNotify($notify);
            }
        }
        $booking->working_status = 6;
        $booking->status = 2;
        $booking->dispute_report = $request->dispute;
        $booking->updated_at = Carbon::now();
        $booking->status_updated_at = Carbon::now();
        $booking->save();
        $notify[] = ['success', 'Work has been in dispute'];
        return back()->withNotify($notify);
    }


    public function workDeliveryDownload($id)
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
    public function employoffersendfiledownload($id)
    {
        $work = Userbiding::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;

        if (file_exists($full_path)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $mimetype = mime_content_type($full_path);

            header('Content-Disposition: attachment; filename="' . $file . '.' . $ext . '";');
            header("Content-Type: " . $mimetype);

            // Output buffering to prevent immediate output to the browser
            ob_clean();
            flush();

            // Read and output the file
            readfile($full_path);
            exit; // Ensure script execution stops after file download
        } else {
            // Handle file not found error
            die('File not found');
        }
    }

    public function employcounterofferfiledownload($id)
    {
        $work = Buyercounteroffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;

        if (file_exists($full_path)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $mimetype = mime_content_type($full_path);

            header('Content-Disposition: attachment; filename="' . $file . '.' . $ext . '";');
            header("Content-Type: " . $mimetype);

            // Output buffering to prevent immediate output to the browser
            ob_clean();
            flush();

            // Read and output the file
            readfile($full_path);
            exit; // Ensure script execution stops after file download
        } else {
            // Handle file not found error
            die('File not found');
        }
    }

    public function hireEmploycancelhisofferresponce(Request $request)
    {
        $id = $request->cancelresponce_id;

        $dec = UserOffer::find($id);
        $dec->buyer_offer_responce = '1';
        $dec->is_seller_offer_sent = '0';
        $dec->save();
        $job = Job::where('id', $dec->job_id)->first();

        $noti = new Notification();
        $noti->reciever_id = $dec->pro_id;
        $noti->sender_id = $job->user_id;
        $noti->type = 'buyer_offer_responce_declined';
        $noti->job_id = $job->id;
        $noti->save();

        sendRealTimeNotification($dec->pro_id, 'buyer_offer_response_declinded');
        // send email
        $getJobName = DB::table('jobs')->where('id', $job->id)->first();
        $sender = DB::table('users')->where('id', $job->user_id)->first();
        $reciever = DB::table('users')->where('id', $dec->pro_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'buyer_declined_offer_responce';
            $mail->isHTML(true);
            $mail->Subject = ' Declined offer.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Offer responce successfully declined'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployaccepthisofferresponce(Request $request)
    {
        $id = $request->acceptresponce_id;

        $dec = UserOffer::find($id);
        $dec->buyer_offer_responce = '2';
        $dec->save();
        $job = Job::where('id', $dec->job_id)->first();
        if ($job) {
            $ab = new Acceptedoffer();
            $ab->offer_id = $request->acceptresponce_id;
            $ab->buyer_id = $dec->buyer_id;
            $ab->seller_id = $dec->pro_id;
            $ab->job_id = $dec->job_id;
            $ab->save();
        }
        $noti = new Notification();
        $noti->reciever_id = $dec->pro_id;
        $noti->sender_id = $job->user_id;
        $noti->type = 'buyer_offer_responce_accepted';
        $noti->job_id = $job->id;
        $noti->save();

        //sendRealTimeNotification($dec->pro_id, 'buyer_offer_responce_accepted');
        // send email
        $getJobName = DB::table('jobs')->where('id', $job->id)->first();
        $sender = DB::table('users')->where('id', $job->user_id)->first();
        $reciever = DB::table('users')->where('id', $dec->pro_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'buyer_accepted_offer_responce';
            $mail->isHTML(true);
            $mail->Subject = ' Accepted offer.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Offer responce successfully accepted'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmploycancelhisoffer(Request $request)
    {
        $id = $request->cancelbid_id;

        $dec = UserOffer::find($id);
        $dec->is_cancel_offer = '1';
        $dec->save();
        $job = Job::where('id', $dec->job_id)->first();

        $noti = new Notification();
        $noti->reciever_id = $dec->pro_id;
        $noti->sender_id = $job->user_id;
        $noti->type = 'buyer_cancel_his_offer';
        $noti->job_id = $job->id;
        $noti->save();

        sendRealTimeNotification($dec->pro_id, 'buyer_cancel_his_offer');


        $notify[] = ['success', 'Offer successfully cancelled'];
        return redirect()->back()->withNotify($notify);
    }
    public function employhisofferfiledownload($id)
    {
        $work = UserOffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function employhisofferresponcefiledownload($id)
    {
        $work = Sellercounteroffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function hireEmployacceptworkbid(Request $request)
    {
        DB::beginTransaction();
        try {
            // Find the job work delivery bid and related buyer/seller
            $jobWorkBid = Jobworkdeliverybid::with(['buyer', 'seller', 'counterOfferData'])->findOrFail($request->work_id);
            if($jobWorkBid->counterOfferData->req_type == 'seller' && $jobWorkBid->job_type == 'byproject') {
                $buyer = $jobWorkBid->seller;
                $seller = $jobWorkBid->buyer;
            }else {
                $buyer = $jobWorkBid->buyer;
                $seller = $jobWorkBid->seller;
            }
            $bidId = $jobWorkBid->bid_id;
            $hasMilestones = UserMilestone::where('bid_id', $bidId)->exists();

            $bookingId = $jobWorkBid->job->booking->id;
            if (!$buyer || !$seller) {
                throw new \Exception('Buyer or Seller not found.');
            }

             // Calculate platform fee
            $sellerPlatformFeePercentage = $this->membershipService->getMembershipFee($seller->id);

            //calculate buyer platform fee
            $buyerPlatformFeePercentage = $this->membershipService->getMembershipFee($buyer->id);

            if($jobWorkBid->job->budgettype == 'hourly') {
                $totalAmount = $jobWorkBid->counterOfferData->amount * $jobWorkBid->counterOfferData->days;
            }else {
                $totalAmount = $jobWorkBid->counterOfferData->amount;
            }

            $sellerPlatformFee = round($totalAmount * $sellerPlatformFeePercentage / 100, 2);
            $buyerPlatformFee = round($totalAmount * $buyerPlatformFeePercentage / 100, 2);

            // Calculate seller earnings
            $sellerEarnings = $totalAmount - $sellerPlatformFee;

            // Add earnings to seller wallet
            $seller->balance += $sellerEarnings;
            $seller->save();

            //this code is for additional milestone release payment . normal payment deducted when job accepted by seller
            //16-may commented bcz when buyer accept the additional milestone at that time payment deducted from buyer
            // if($jobWorkBid->counterOfferData->req_type == 'seller') {
            //     $buyer->balance -= ($totalAmount + $buyerPlatformFee);
            //     $buyer->save();
            // }
            if($jobWorkBid->counterOfferData->req_type == 'seller' && $jobWorkBid->job_type == 'byproject') {
                //seller earning
                $transactionId = $this->createTransaction(
                    $seller,
                    $buyer,
                    $totalAmount - $sellerPlatformFee,
                    '+',
                    "Earnings for Job: " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    $sellerPlatformFee
                );

                // Buyer Transaction
                $transactionId = $this->createTransaction(
                    $buyer,
                    $seller,
                    $totalAmount + $buyerPlatformFee,
                    '-',
                    "Payed for Job: " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    $buyerPlatformFee
                );

                if($hasMilestones) {
                    //Buyer Transaction
                    $transactionId = $this->createTransaction(
                        $buyer,
                        $seller,
                        $totalAmount + $buyerPlatformFee,
                        '-',
                        "Payed for Job: " . ($jobWorkBid?->job?->title ?? ''),
                        $bookingId,
                        0
                    );
                }

                // Seller Log platform commission
                $this->createTransaction(
                    $seller,
                    $buyer,
                    $sellerPlatformFee,
                    '-',
                    "Platform commission for Job: " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    0
                );

                //buyer platform commission
                $this->createTransaction(
                    $buyer,
                    $seller,
                    $buyerPlatformFee,
                    '-',
                    "Platform commission have been deducted / levied as a fee. " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    0
                );
            }
            else {
                $transactionId = $this->createTransaction(
                    $seller,
                    $buyer,
                    $totalAmount - $sellerPlatformFee,
                    '+',
                    "Earnings for Job: " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    $sellerPlatformFee
                );

                if($hasMilestones) {
                    //Buyer Transaction
                    $transactionId = $this->createTransaction(
                        $buyer,
                        $seller,
                        $totalAmount + $buyerPlatformFee,
                        '-',
                        "Payed for Job: " . ($jobWorkBid?->job?->title ?? ''),
                        $bookingId,
                        $buyerPlatformFee
                    );

                    $transactionId = $this->createTransaction(
                        $buyer,
                        $seller,
                        $buyerPlatformFee,
                        '-',
                        'Platform commission have been deducted / levied as a fee.'.  ($jobWorkBid?->job?->title ?? ''),
                        $bookingId,
                        0
                    );
                }

                // Seller Log platform commission
                $this->createTransaction(
                    $seller,
                    $buyer,
                    $sellerPlatformFee,
                    '-',
                    "Platform commission for Job: " . ($jobWorkBid?->job?->title ?? ''),
                    $bookingId,
                    0
                );
            }

            // Create transaction for seller earnings

            // Update job work bid status
            $jobWorkBid->status = '5'; // Paid
            $jobWorkBid->updated_at = Carbon::now();
            $jobWorkBid->save();

            if(!$hasMilestones) {
                Transaction::where('booking_id', $bookingId)->update([
                    'status' => 1
                ]);
            }
            // else {
            //     Transaction::where('booking_id', $bookingId)
            //     ->where('details', 'LIKE', '%Platform commission%')
            //     ->update(['status' => 1]);
            // }

            // Update milestone status
            $buyerCounterOfferData = Buyercounterofferdata::findOrFail($jobWorkBid->data_id);
            $buyerCounterOfferData->milestone_status = '3'; // Paid
            $buyerCounterOfferData->updated_at = Carbon::now();
            $buyerCounterOfferData->save();

            DB::commit();
            sendJobEmailWithNotification($buyer->id, $seller->id, $jobWorkBid->job_id, 'WORK_REVIEW_FEEDBACK', false, $jobWorkBid->job_id);
            sendJobEmailWithNotification($buyer->id, $seller->id, $jobWorkBid->job_id, 'PAYMENT_RECEIVED', false, $jobWorkBid->job_id, $transactionId, $sellerEarnings);

            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Work Accepted and Payment send for job '.$jobWorkBid?->job?->title, route('admin.job.details',['id'=>$jobWorkBid->job_id]));


            // sendJobEmailWithNotification($buyer->id, $seller->id, $jobWorkBid->job_id, 'WORK_REVIEW_FEEDBACK', false, $userBiddingId);
            // sendJobEmailWithNotification($buyer->id, $seller->id, $jobWorkBid->job_id, 'PAYMENT_RECEIVED', false, $userBiddingId, $transactionId, $sellerEarnings);

            return response()->json(['status' => 'success', 'message' => 'Work bid accepted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function createTransaction(User $user, ?User $toUser, float $amount, string $trxType, string $details, $bookingId, $charge): string
    {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'to_user' => $toUser ? $toUser->id : null,
            'booking_id' => $bookingId,
            'amount' => $amount,
            'trx_type' => $trxType,
            'details' => $details,
            'charge' => $charge,
            'post_balance' => $user->balance,
            'trx' =>getTrx(),
            'status'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $transaction->trx;
    }

     private function createChargeTransaction(User $user, ?User $toUser, float $amount, string $trxType, string $details, $bookingId, $charge): string
    {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'to_user' => $toUser ? $toUser->id : null,
            'booking_id' => $bookingId,
            'amount' => $amount,
            'trx_type' => $trxType,
            'details' => $details,
            'charge' => $charge,
            'post_balance' => $user->balance,
            'trx' =>getTrx(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $transaction->trx;
    }

    public function hireEmployrejectworkbid(Request $request)
    {
        $accept = Jobworkdeliverybid::findOrFail($request->reject_work_id);
        $accept->status = '3';
        $accept->reason = $request->reason;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('attachment')) {
            $file = $request->attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $accept->counter_file = $filename;
        }
        $accept->updated_at = Carbon::now();
        $acc = $accept->save();
        if ($acc) {
            $data = Buyercounterofferdata::findOrFail($accept->data_id);
            $data->milestone_status = '1';
            $data->updated_at = Carbon::now();
            $data->save();
        }
        sendJobEmailWithNotification($accept->buyer_id, $accept->seller_id, $accept->job_id, 'WORK_REVIEW_DECLINED', false, $accept->job_id);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Work rejected for job '.$accept?->job?->title, route('admin.job.details',['id'=>$accept->job_id]));

        return response()->json(['status' => 'success', 'message' => 'work successfully declined.']);
    }


    public function hireEmployacceptworkoffer(Request $request)
    {
        $accept = Jobworkdeliveryoffer::findOrFail($request->work_id);
        $accept->status = '1';
        $accept->updated_at = Carbon::now();
        $acc = $accept->save();
        if ($acc) {
            $data = Sellercounterofferdata::findOrFail($accept->data_id);
            $data->status = '1';
            $data->is_payable = '1';
            $data->updated_at = Carbon::now();
            $data->save();
        }
        $noti = new Notification();
        $noti->reciever_id = $accept->seller_id;
        $noti->sender_id = $accept->buyer_id;
        $noti->type = 'buyer_accept_bid_work';
        $noti->job_id = $accept->job_id;
        $noti->save();

        sendRealTimeNotification($accept->buyer_id, $accept->seller_id, $accept->job_id, 'buyer_accept_bid_work', false, $accept->job_id);
        // sendRealTimeNotification($accept->seller_id, 'buyer_accept_bid_work');
        // send email
        $getJobName = DB::table('jobs')->where('id', $accept->job_id)->first();
        $sender = DB::table('users')->where('id', $accept->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $accept->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $request->work;
            $type = 'buyer_accept_work';
            $mail->isHTML(true);
            $mail->Subject = ' Accept Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email



        $notify[] = ['success', 'work successfully accepted'];
        return redirect()->back()->withNotify($notify);
    }

    public function hireEmployrejectworkoffer(Request $request)
    {
        $accept = Jobworkdeliveryoffer::findOrFail($request->work_id);
        $accept->status = '3';
        $accept->reason = $request->details;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('attachment')) {
            $file = $request->attachment;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $accept->counter_file = $filename;
        }
        $accept->updated_at = Carbon::now();
        $acc = $accept->save();
        if ($acc) {
            $data = Sellercounterofferdata::findOrFail($accept->data_id);
            $data->status = '3';
            $data->updated_at = Carbon::now();
            $data->save();
        }
        $noti = new Notification();
        $noti->reciever_id = $accept->seller_id;
        $noti->sender_id = $accept->buyer_id;
        $noti->type = 'buyer_reject_bid_work';
        $noti->job_id = $accept->job_id;
        $noti->save();

        sendRealTimeNotification($accept->seller_id, 'buyer_reject_bid_work');
        // send email
        $getJobName = DB::table('jobs')->where('id', $accept->job_id)->first();
        $sender = DB::table('users')->where('id', $accept->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $accept->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $request->work;
            $type = 'buyer_declined_work';
            $mail->isHTML(true);
            $mail->Subject = ' Declined Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email



        $notify[] = ['success', 'work successfully declined'];
        return redirect()->back()->withNotify($notify);
    }


    public function employworkrecievefiledownload($id)
    {
        $work = Jobworkdeliverybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function employworkrecievecounterfiledownload($id)
    {
        $work = Jobworkdeliverybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->reason_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }
    public function hireEmployrequestworkmsbid(Request $request)
    {
        // Decrypt job ID and bid ID
        $jobId = decrypt($request->job_id);
        // dd($request->all(),$jobId);
        if(!is_null($request->bid_id))
            $bidId = decrypt($request->bid_id);


        // Find the existing offer
        $offer = Buyercounteroffer::where('job_id', $jobId)->where('bid_id', $bidId)->where('status', 1)->firstOrFail();
        $user = Auth::user();
        $milestoneData = [];
        foreach ($request->milestone as $milestone) {
            $milestoneData[] = [
                'counter_offer_id' => $offer->id,
                'requested_milestone_status'=>0,
                'job_id' => $jobId,
                'bid_id' => $bidId ?? null,
                'sender_id' => $user->id,
                'reciever_id' => $offer->reciever_id,
                'status' => '0',
                'milestone_status' => '8',
                'req_type' => 'buyer',
                'days' => $milestone['days_from_today'],
                'due_date' => $milestone['due_date'],
                'amount' => $milestone['amount'],
                'title' => $milestone['title'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Buyercounterofferdata::insert($milestoneData);
        // sendJobEmailWithNotification($user->id, $offer->reciever_id, $offer->job_id, 'ADDITIONAL_MILESTONE_BUYER_REQUESTED', false, $offer->bid_id, jobType: $offer->type);
        sendJobEmailWithNotification($user->id, $offer->reciever_id, $offer->job_id, 'ADDITIONAL_MILESTONE_BUYER_REQUESTED', false, $jobId, jobType: $offer->type);

        // Success notification
        $notify[] = ['success', 'Milestone successfully requested'];
        return redirect()->back()->withNotify($notify);
    }


    public function hireEmployrequestworkmsoffer(Request $request)
    {

        $offer = Sellercounteroffer::where('job_id', $request->job_id)->where('offer_id', $request->bid_id)->first();
        $title = $request->milestone_title;
        $amount = $request->milestone_amount;
        $days = $request->milestone_due_date;
        foreach ($days as $key => $new) {
            $offerdata = new Sellercounterofferdata();
            $offerdata->counter_offer_id = $offer->id;
            $offerdata->job_id = $request->job_id;
            $offerdata->offer_id = $request->bid_id;
            $offerdata->sender_id = $offer->sender_id;
            $offerdata->reciever_id = $offer->reciever_id;
            $offerdata->milestone_status = '0';
            $offerdata->req_type = 'buyer';
            $offerdata->days = $days[$key];
            $offerdata->amount = $amount[$key];
            $offerdata->title = $title[$key];
            $offerdata->save();
        }



        $noti = new Notification();
        $noti->reciever_id = $offer->reciever_id;
        $noti->sender_id = $offer->sender_id;;
        $noti->type = 'buyer_request_milestone_bid';
        $noti->job_id = $request->job_id;
        $noti->save();

        sendRealTimeNotification($offer->reciever_id, 'buyer_request_milestone_bid');

        // send email
        $getJobName = DB::table('jobs')->where('id', $request->job_id)->first();
        $reciever = DB::table('users')->where('id', $offer->sender_id)->first();
        $sender = DB::table('users')->where('id', $offer->reciever_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $title;
            $type = 'buyer_request_milestone';
            $mail->isHTML(true);
            $mail->Subject = ' Milestone Request.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email


        $notify[] = ['success', 'Milestone successfully requested'];
        return redirect()->back()->withNotify($notify);
    }

    public function hireEmployacceptextraworkmsbid(Request $request)
    {
        $counterOfferDataId = decrypt($request->new_milestone_accept_request_id);
        $offer = Buyercounterofferdata::find($counterOfferDataId);

        $booking = Booking::where('job_id', $offer->job_id)->first();
        $job = Job::find($offer->job_id);

        $offer->status = 1;
        $offer->milestone_status = '0';
        $offer->save();

        $buyer = User::find($offer->reciever_id);
        if($buyer->balance < $offer->amount) {
            $notify[] = ['error', 'You have insufficient balance to accept this milestone. Please add funds to your wallet.'];
            return redirect()->back()->withNotify($notify);
        }
        $buyer->balance -= $offer->amount;
        $buyer->save();

        $buyer_platform_fee_percentage = getMembershipFee($buyer->id);
        $buyer_platform_fee_amount = ($offer->amount * $buyer_platform_fee_percentage) / 100;


        $transaction = new Transaction();
        $transaction->user_id = $buyer->id;
        $transaction->to_user = null;
        $transaction->amount = $offer->amount;
        $transaction->charge = $buyer_platform_fee_amount;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type = '-';
        $transaction->trx = getTrx();
        $transaction->details = "Additional Milestone for " . $job->title;
        $transaction->save();

        $c_transaction = new Transaction();
        $c_transaction->user_id = $buyer->id;
        $c_transaction->to_user = null;
        $c_transaction->amount = $buyer_platform_fee_amount;
        $c_transaction->charge = 0;
        $c_transaction->post_balance = $buyer->balance;
        $c_transaction->trx_type = '-';
        $c_transaction->trx = getTrx();
        $c_transaction->details = "Charge on Additional Milestone for " . $job->title;
        $c_transaction->save();

        try {
            sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_APP_BY_BUYER', false, $offer->job_id, jobType: $offer->buyerCounterOffer->type);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' accepted additional milestone of job '.$offer?->job?->title, route('admin.job.details',['id'=>$offer->job_id]));
        } catch (\Exception $emailException) {
            // Log the email error but do not rollback the transaction
            \Log::error('Email failed to send: ' . $emailException->getMessage());
        }

        $notify[] = ['success', 'Requested Milestone successfully accepted'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployrejectextraworkmsbid(Request $request)
    {
        $counterOfferDataId = decrypt($request->new_milestone_reject_request_id);
        $offer = Buyercounterofferdata::find($counterOfferDataId);

        $offer->req_responce_status = 'reject';
        $offer->milestone_status = '9';
        $offer->save();
        try {
            if($offer?->job?->budgettype == 'fixed') {
                sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_REJ_BY_BUYER', false, $offer->job_id, $offer->buyerCounterOffer->type);
            }else {
                sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_HOURLY_MILESTONE_REJ_BY_BUYER', false, $offer->job_id, $offer->buyerCounterOffer->type);
            }
            sendAdminRealTimeNotification(Auth::user(), Auth::user()->username. ' rejected the additional milestone of job '.$offer?->job?->title, route('admin.job.details',['id'=>$offer->job_id]));
        } catch (\Exception $emailException) {
            // Log the email error but do not rollback the transaction
            \Log::error('Email failed to send: ' . $emailException->getMessage());
        }

        $notify[] = ['success', 'Requested Milestone successfully rejected'];
        return redirect()->back()->withNotify($notify);
    }



    public function hireEmployacceptextraworkmsoffer(Request $request)
    {
        $offer = Sellercounterofferdata::find($request->work_id);

        $offer->milestone_status = '1';
        $offer->save();




        $noti = new Notification();
        $noti->reciever_id = $offer->sender_id;
        $noti->sender_id = $offer->reciever_id;;
        $noti->type = 'seller_accept_request_milestone_bid';
        $noti->job_id = $offer->job_id;
        $noti->save();

        sendRealTimeNotification($offer->sender_id, 'seller_accept_request_milestone_bid');


        $notify[] = ['success', 'Requested Milestone successfully accepted'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployrejectextraworkmsoffer(Request $request)
    {
        $offer = Sellercounterofferdata::find($request->work_id);

        $offer->req_responce_status = 'reject';
        $offer->milestone_status = '2';
        $offer->save();




        $noti = new Notification();
        $noti->reciever_id = $offer->sender_id;
        $noti->sender_id = $offer->reciever_id;;
        $noti->type = 'seller_reject_request_milestone_bid';
        $noti->job_id = $offer->job_id;
        $noti->save();

        sendRealTimeNotification($offer->sender_id, 'seller_reject_request_milestone_bid');


        $notify[] = ['success', 'Requested Milestone successfully rejected'];
        return redirect()->back()->withNotify($notify);
    }


    public function hireEmployacceptworktimesheetbid(Request $request)
    {

        $ts = Jobworkdeliveryhourlybid::findOrFail($request->approved);
        $ts->status = '1';
        $ts->is_payable = '1';
        $ts->save();
        $noti = new Notification();
        $noti->reciever_id = $ts->seller_id;
        $noti->sender_id = $ts->buyer_id;
        $noti->type = 'buyer_accept_timesheet_bid';
        $noti->job_id = $ts->job_id;
        $noti->save();

        sendRealTimeNotification($ts->seller_id, 'buyer_accept_timesheet_bid');
        // send email
        $getJobName = DB::table('jobs')->where('id', $ts->job_id)->first();
        $sender = DB::table('users')->where('id', $ts->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $ts->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $ts->title;
            $type = 'buyer_accept_work';
            $mail->isHTML(true);
            $mail->Subject = ' Accept Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {
                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Timesheet Accepted'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message could not be sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email


        $notify[] = ['success', 'Timesheet Accepted'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployrejectedworktimesheetbid(Request $request)
    {
        // print_r($request->all());exit;
        $ts = Jobworkdeliveryhourlybid::findOrFail($request->rejected);
        $ts->status = '3';
        $ts->reason = $request->reason;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $ts->files = $filename;
        }
        $ts->save();
        $noti = new Notification();
        $noti->reciever_id = $ts->seller_id;
        $noti->sender_id = $ts->buyer_id;
        $noti->type = 'buyer_reject_timesheet_bid';
        $noti->job_id = $ts->job_id;
        $noti->save();

        sendRealTimeNotification($ts->seller_id, 'buyer_reject_timesheet_bid');
        // send email
        $getJobName = DB::table('jobs')->where('id', $ts->job_id)->first();
        $sender = DB::table('users')->where('id', $ts->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $ts->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $ts->title;
            $type = 'buyer_declined_work';
            $mail->isHTML(true);
            $mail->Subject = ' Declined Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            }


            $notify[] = ['success', 'Timesheet Rejected'];
            return redirect()->back()->withNotify($notify);
        } catch (Exception $e) {

            $notify[] = ['error', 'Message could not be sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email




        $notify[] = ['success', 'Timesheet Rejected'];
        return redirect()->back()->withNotify($notify);
    }



    public function hireEmployacceptworktimesheetoffer(Request $request)
    {

        $ts = Jobworkdeliveryhourlyoffer::findOrFail($request->approved);
        $ts->status = '1';
        $ts->is_payable = '1';
        $ts->save();
        $noti = new Notification();
        $noti->reciever_id = $ts->seller_id;
        $noti->sender_id = $ts->buyer_id;
        $noti->type = 'buyer_accept_timesheet_bid';
        $noti->job_id = $ts->job_id;
        $noti->save();

        sendRealTimeNotification($ts->seller_id, 'buyer_accept_timesheet_bid');
        // send email
        $getJobName = DB::table('jobs')->where('id', $ts->job_id)->first();
        $sender = DB::table('users')->where('id', $ts->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $ts->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $ts->title;
            $type = 'buyer_accept_work';
            $mail->isHTML(true);
            $mail->Subject = ' Accept Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email


        $notify[] = ['success', 'Timesheet Accepted'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployrejectedworktimesheetoffer(Request $request)
    {
        // print_r($request->all());exit;
        $ts = Jobworkdeliveryhourlyoffer::findOrFail($request->rejected);
        $ts->status = '3';
        $ts->reason = $request->reason;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $ts->files = $filename;
        }
        $ts->save();
        $noti = new Notification();
        $noti->reciever_id = $ts->seller_id;
        $noti->sender_id = $ts->buyer_id;
        $noti->type = 'buyer_reject_timesheet_bid';
        $noti->job_id = $ts->job_id;
        $noti->save();

        sendRealTimeNotification($ts->seller_id, 'buyer_reject_timesheet_bid');
        // send email
        $getJobName = DB::table('jobs')->where('id', $ts->job_id)->first();
        $sender = DB::table('users')->where('id', $ts->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $ts->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $ts->title;
            $type = 'buyer_declined_work';
            $mail->isHTML(true);
            $mail->Subject = ' Declined Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email


        $notify[] = ['success', 'Timesheet Rejected'];
        return redirect()->back()->withNotify($notify);
    }

    public function employtimesheetfiledownload($id)
    {
        $work = Jobworkdeliveryhourlybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }
    public function employtimesheetcounterfiledownload($id)
    {
        $work = Jobworkdeliveryhourlybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->response_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path . '/' . $file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function hireEmploydisputeworkbid(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'buyer_amount' => 'required|numeric',
            'seller_amount' => 'required|numeric',
            'reason' => 'required|string',
            'message' => 'required|string',
        ]);

        // Return validation errors as JSON for AJAX requests
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        $decryptedJobBidId = Crypt::decrypt($request->encJobWorkBidsId); // Assuming you are passing the job_bid_id to the request
        // Fetch the total_cost from job_work_delivery_hourly_bid based on the decrypted ID
        $jobBid = Jobworkdeliveryhourlybid::findOrFail($decryptedJobBidId);
        if ($jobBid->is_manual == 1) {
            $jobBid->status = 6; // Set the status to 3
            $jobBid->save();     // Save the updated job bid
        }
        $dispute_amount = $jobBid->total_cost;
        // Validate the offered amounts
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;
        $amountequal = $buyer_amount + $seller_amount;

        if ($amountequal != $dispute_amount) {
            return response()->json([
                'errors' => ['seller_amount' => ['Offered amount must be equal to dispute amount']]
            ], 422);
        }


        $dispute = new Jobdisputebid();
        $dispute->job_id = $jobBid->job_id;
        $dispute->service_id = $jobBid->id;
        $dispute->seller_id = $jobBid->seller_id; // Assuming you have a seller_id in your Jobworkdeliveryhourlybid table
        $dispute->buyer_id = $jobBid->buyer_id;
        $dispute->bid_id = $jobBid->bid_id;
        $dispute->reason = $request->reason;
        $dispute->disputed_amount = $dispute_amount; // Use the fetched total_cost
        $dispute->buyer_amount = $buyer_amount;
        $dispute->seller_amount = $seller_amount;
        $dispute->seller_message = $request->message;
        $dispute->status = '0';
        $dispute->send_by = 'buyer';
        $dispute->job_type = 'bid';


        $dispute->save();

        $disputedata = new Jobdisputebiddata();
        $disputedata->seller_amount = $request->seller_amount;
        $disputedata->seller_message = $request->seller_message;
        $disputedata->buyer_amount = $request->buyer_amount;
        $disputedata->dispute_id = $dispute->id;
        $disputedata->status = '0';
        $disputedata->send_by = 'buyer';
        $disputedata->show_send = '0';
        if ($request->hasFile('file')) {
            $file = $request->file('file'); // Get the uploaded file

            // Generate a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store the file in the public/dispute directory
            $path = $file->storeAs('dispute', $filename, 'public'); // Using the 'public' disk

            // Save the filename in the dispute record
            $dispute->file = $path; // Store the path in the database
        }
        $dispute->save();

        $noti = new Notification();
        $noti->reciever_id = $jobBid->seller_id;
        $noti->sender_id = $jobBid->buyer_id;
        $noti->type = 'buyer_created_dispute';
        $noti->job_id = $jobBid->job_id;
        $noti->save();

        sendRealTimeNotification($jobBid->seller_id, 'buyer_created_dispute');
        // send email
        $getJobName = DB::table('jobs')->where('id', $jobBid->job_id)->first();
        sendJobEmailWithNotification($jobBid->buyer_id, $jobBid->seller_id, $getJobName->id, 'BUYER_DISPUTE_SELLER');

        $notify[] = ['success', 'Work has been in dispute'];
        return back()->withNotify($notify);
    }

    public function hireEmploydisputeworkoffer(Request $request)
    {
        $dispute_amout = $request->dispute_amount;
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;
        $amountequal = $buyer_amount + $seller_amount;
        if ($amountequal != $dispute_amout) {
            $notify[] = ['error', 'Offered amount must be equal to dispute amout'];
            return back()->withNotify($notify);
        }
        $acc = Acceptedoffer::where('id', $request->accepted_id)->first();
        $dispute = new Jobdisputebid();
        $dispute->job_id = $acc->job_id;
        $dispute->seller_id = $acc->seller_id;
        $dispute->buyer_id = $acc->buyer_id;
        $dispute->offer_id = $acc->offer_id;
        $dispute->work_id = $request->work_id;
        $dispute->work_type = $request->bid_type;
        $dispute->job_type = 'offer';
        $dispute->reason = $request->reason;
        $dispute->dispute_amount = $request->dispute_amount;
        $dispute->buyer_amount = $request->buyer_amount;
        $dispute->seller_amount = $request->seller_amount;
        $dispute->seller_message = $request->seller_message;
        $dispute->status = '0';
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $dispute->file = $filename;
        }
        $dispute->save();

        $disputedata = new Jobdisputebiddata();
        $disputedata->seller_amount = $request->seller_amount;
        $disputedata->seller_message = $request->seller_message;
        $disputedata->buyer_amount = $request->buyer_amount;
        $disputedata->dispute_id = $dispute->id;
        $disputedata->status = '0';
        $disputedata->show_send = '0';
        $disputedata->send_by = 'buyer';
        $disputedata->save();


        $data = Sellercounterofferdata::findOrFail($request->work_id);
        $data->status = '6';
        $data->save();

        $working_id = DB::table('job_work_delivery_offer')->where('data_id', $request->work_id)->first();

        $works = Jobworkdeliveryoffer::findOrFail($working_id->id);
        $works->status = '6';
        $works->save();

        $noti = new Notification();
        $noti->reciever_id = $acc->seller_id;
        $noti->sender_id = $acc->buyer_id;
        $noti->type = 'buyer_created_dispute';
        $noti->job_id = $acc->job_id;
        $noti->save();

        sendRealTimeNotification($acc->seller_id, 'buyer_created_dispute');
        // send email
        $getJobName = DB::table('jobs')->where('id', $acc->job_id)->first();
        $sender = DB::table('users')->where('id', $acc->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $acc->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'dispute';
            $work = $works->title;
            $mail->isHTML(true);
            $mail->Subject = ' Dispute.';
            $contentBody = view('Emails/Job/dispute_job', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Work has been in dispute'];
        return back()->withNotify($notify);
    }

    public function hireEmployviewbiddispute(Request $request, $type, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $activeTab = $request->get('tab', 'case'); // default to 'case'

        $user = Auth::user();
        $pageTitle = "View Dispute";
        $emptyMessage = "No data found";

        $disputeId = decrypt($id);
        $jobDisputeId = $id;

        if ($type === 'job') {
            $disputedetail = Jobdisputebid::findOrFail($disputeId);
            $negotiations = Jobdisputebiddata::with(['buyer', 'seller'])
                ->where('dispute_id', $disputedetail->id)
                ->orderBy('created_at', 'asc')
                ->get();

            $settledDispute = Jobdisputebiddata::where('dispute_id', $disputedetail->id)
                ->where('status', 1)
                ->first();

            $booking = Booking::where('job_id', $disputedetail->job_id)->first();
        } elseif ($type === 'service') {
            $disputedetail = Servicedisputedata::findOrFail($disputeId);
            $negotiations = Servicedisputeoffers::where('dispute_id', $disputedetail->id)
                ->orderBy('created_at', 'asc')
                ->get();

            $settledDispute = Servicedisputeoffers::where('dispute_id', $disputedetail->id)
                ->where('status', 1)
                ->first();

            $booking = Booking::find($disputedetail->booking_id);
        } else {
            abort(404, 'Invalid dispute type');
        }

        // ✅ AJAX Handling
        if ($request->ajax()) {
            if ($request->has('tab') && $request->tab === 'case') {
                return view('templates.basic.user.buyer.dispute_case', compact(
                    'disputedetail',
                    'booking',
                    'settledDispute',
                    'type'
                ));
            }

            if ($request->has('tab') && $request->tab === 'negotiation') {
                return view('templates.basic.user.buyer.dispute_negotiation', compact(
                    'negotiations',
                    'disputedetail',
                    'booking',
                    'settledDispute',
                    'type'
                ));
            }

            return response()->json(['error' => 'Invalid tab'], 400);
        }

         $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dispute')],
            ['title' => 'Offered Jobs', 'url' => route('user.offered.jobs')],
            ['title' => 'View Dispute Identifier'],
        ];

        // ✅ Full Page Load
        return view($this->activeTemplate . 'user.buyer.hire_employ_bid-view-dispute', compact(
            'pageTitle',
            'breadcrumbs',
            'emptyMessage',
            'disputedetail',
            'booking',
            'settledDispute',
            'negotiations',
            'disputeId',
            'jobDisputeId',
            'type',
            'activeTab'
        ));
    }

    public function loadDisputeCaseIdentification(Request $request, $jobDisputeId)
    {
        $pageTitle = "Case Identification";
        $disputedetail = Jobdisputebid::where('id', decrypt($jobDisputeId))->first();
        $booking = Booking::where('job_id', $disputedetail->job_id)->first();
        $settledDispute = Jobdisputebiddata::where('dispute_id', $disputedetail->id)->where('status', 1)->first();
        if ($request->ajax())
            return view('templates.basic.user.buyer.dispute_case', compact('disputedetail', 'booking', 'settledDispute'));
        return view($this->activeTemplate . 'user.buyer.hire_employ_bid-view-dispute', compact('pageTitle', 'disputedetail', 'booking', 'jobDisputeId', 'settledDispute'));
    }

    public function loadServiceDisputeCase($id)
    {
        $pageTitle = "Case Identification";
        $dispute = Servicedisputedata::findOrFail(decrypt($id));
        $booking = Booking::find($dispute->booking_id);

        return view('templates.basic.user.buyer.service_dispute_case', compact('dispute', 'booking'));
    }

    public function loadDisputeNegotiation(Request $request, $type, $id)
    {
        $pageTitle = "Negotiation";
        $decryptedId = decrypt($id);
        $jobDisputeId = $id;
        if ($type === 'job') {

            $disputedetail = Jobdisputebid::with('bidData')->findOrFail($decryptedId);
            $settledDispute = Jobdisputebiddata::where('dispute_id', $disputedetail->id)->where('status', 1)->first();
            $booking = Booking::where('job_id', $disputedetail->job_id)->first();
            $negotiations = $disputedetail->bidData;
        } elseif ($type === 'service') {

            $disputedetail = Servicedisputedata::findOrFail($decryptedId);
            $settledDispute = Servicedisputeoffers::where('dispute_id', $disputedetail->id)->where('status', 1)->first();
            $booking = Booking::find($disputedetail->booking_id);
            $negotiations = Servicedisputeoffers::where('dispute_id', $disputedetail->id)->get();
        } else {
            abort(404, 'Invalid dispute type');
        }

        if ($request->ajax()) {
            return view('templates.basic.user.buyer.dispute_negotiation', compact(
                'jobDisputeId',
                'negotiations',
                'booking',
                'disputedetail',
                'settledDispute',
                'type'
            ));
        }

        return view($this->activeTemplate . 'user.buyer.hire_employ_bid-view-dispute-nego', compact(
            'jobDisputeId',
            'pageTitle',
            'negotiations',
            'disputedetail',
            'booking',
            'id',
            'settledDispute',
            'type'
        ));
    }

    public function getActivitiesCount(Request $request)
    {
        // Step 1: Retrieve interval IDs from the request
        $intervalIds = $request->input('intervalIds');

        // Step 2: Count the number of activity logs (in minutes) based on interval IDs
        $activityCountInMinutes = ActivityLog::whereIn('job_work_intervals_id', $intervalIds)->count();

        // Step 3: Fetch job work intervals
        $hourlyBidId = JobworkIntervalEntry::whereIn('id', $intervalIds)->pluck('hourly_bid_id')->first();
        // Step 4: Retrieve per-hour rate from job_work_delivery_hourly_bid using hourly_bid_id
        $perHourRate = JobWorkDeliveryHourlyBid::where('id', $hourlyBidId)->pluck('per_hour_rate')->first();
        // Step 5: Convert activity count to hours (since activityCountInMinutes is in minutes)
        $activityCountInHours = $activityCountInMinutes / 60;

        // Step 6: Calculate the total amount (multiply hours by the per-hour rate)
        $totalAmount = round($activityCountInHours * $perHourRate, 2);
        return response()->json(['amount' => $totalAmount]);
    }



    public function hireEmploydisputeofferworkbid(Request $request)
    {
        // Validate the input
        $validator = \Validator::make($request->all(), [
            'buyer_amount' => 'required|numeric',
            'seller_amount' => 'required|numeric',
            'message' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Max 1MB, image only
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return all validation errors as JSON
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->messages() // Return structured errors
            ], 422);
        }

        // Fetch the dispute
        $dispute = Jobdisputebid::where('id', $request->dispute_id)->first();

        // Validate that the buyer and seller amounts add up to the dispute amount
        $dispute_amount = $dispute->dispute_amount;
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;
        $amount_equal = $buyer_amount + $seller_amount;

        if ($amount_equal != $dispute_amount) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'amount' => ['Offered amount must be equal to dispute amount']
                ]
            ], 422);
        }

        // Save dispute data
        $disputedata = new Jobdisputebiddata();
        $disputedata->seller_amount = $seller_amount;
        $disputedata->seller_message = $request->message;
        $disputedata->buyer_amount = $buyer_amount;
        $disputedata->dispute_id = $dispute->id;
        $disputedata->status = '0';
        $disputedata->show_send = '0';
        $disputedata->send_by = 'buyer';

        // File upload logic
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dispute', $filename, 'public');
            $disputedata->file = $path;
        }
        $disputedata->save();

        sendJobEmailWithNotification($dispute->buyer_id, $dispute->seller_id, $dispute->job_id, "SEND_DISPUTE_MSG_TO_SELLER", jobDisputeBidId: $dispute->id);
        // Send real-time notification
        //sendRealTimeNotification($dispute->seller_id, 'buyer_offer_new_dispute');


        return response()->json([
            'status' => 'success',
            'message' => 'Dispute offer has been sent successfully!'
        ]);
    }

    public function hireEmploydisputeofferworkbidaccept(Request $request)
    {
        $disputedata = Jobdisputebiddata::findOrFail($request->work_id);
        $disputedata->status = '1';
        $disputedata->save();
        $dispute = Jobdisputebid::findOrFail($disputedata->dispute_id);
        $dispute->status = '1';
        $dispute->save();
        // send email
        $getJobName = DB::table('jobs')->where('id', $dispute->job_id)->first();
        $sender = DB::table('users')->where('id', $dispute->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $dispute->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'dispute_offer_accept';
            $work = '';
            $mail->isHTML(true);
            $mail->Subject = ' Dispute Offer Accepted.';
            $contentBody = view('Emails/Job/dispute_job', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute offer has been accepted'];
        return back()->withNotify($notify);
    }
    public function hireEmploydisputeofferworkbiddecline(Request $request)
    {
        $disputedata = Jobdisputebiddata::findOrFail($request->work_id);
        $disputedata->status = '3';
        $disputedata->show_send = '1';
        $disputedata->save();
        $dispute = Jobdisputebid::findOrFail($disputedata->dispute_id);
        // send email
        $getJobName = DB::table('jobs')->where('id', $dispute->job_id)->first();
        $sender = DB::table('users')->where('id', $dispute->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $dispute->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'dispute_offer_decline';
            $work = '';
            $mail->isHTML(true);
            $mail->Subject = ' Dispute Offer Declined.';
            $contentBody = view('Emails/Job/dispute_job', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email


        $notify[] = ['success', 'Dispute offer has been declined'];
        return back()->withNotify($notify);
    }
    public function hireEmploydisputeofferworkbidsendtoadmin(Request $request)
    {
        $dispute = Jobdisputebid::findOrFail($request->dispute_id);
        $dispute->status = '2';
        $dispute->sent_to_admin_by = "Buyer";
        $dispute->save();

        $disputeDataRecords = Jobdisputebiddata::where('dispute_id', $dispute->id)
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
    public function hireEmployrating(Request $request)
    {
        // print_r($request->all());exit;
        // $offer=Acceptedoffer::where('id',$request->data_id)->firstOrFail();
        // echo $offer->id;exit;
        $rev = new ReviewRating();
        $rev->user_id = $request->buyer_id;
        $rev->pro_id = $request->seller_id;
        $skill = $request->skill;
        $rat1 = $request->rating1;
        $rat2 = $request->rating2;
        $rat3 = $request->rating3;
        $rat = ($skill + $rat1 + $rat2 + $rat3) / 4;
        $rev->skill = $request->skill;
        $rev->rating = $rat;
        $rev->review = $request->comment;
        $rev->communicationrating = $request->rating1;
        $rev->recommendrating = $request->rating2;
        $rev->describedrating = $request->rating3;
        $rev->reason = $request->reason;
        $rev->data_id = $request->data_id;
        $rev->type = 'offer';
        $rev->save();

        $offer = Acceptedoffer::where('id', $request->data_id)->firstOrFail();
        $offer->is_endcontract = '1';
        $offer->save();

        $job = Job::where('id', $offer->job_id)->firstOrFail();
        $job->show_completed = '1';
        $job->save();
        // send email
        $getJobName = DB::table('jobs')->where('id', $job->id)->first();
        $sender = DB::table('users')->where('id', $request->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $request->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = '';
            $type = 'end_contract';
            $mail->isHTML(true);
            $mail->Subject = ' Contract End.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Contract ended successfully'];
        return redirect()->back()->withNotify($notify);
    }
    public function hireEmployratingbid(Request $request)
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
            // Create a new ReviewRating instance and save the ratings
            if(array_key_exists('job_id', $request->all()))
            {
                $rev = ReviewRating::where('data_id',decrypt($request->input('job_id')))->latest()->first();
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
            $jobId = decrypt($request->job_id);
            $rev->data_id = $jobId; // Link to the relevant bid/job
            $rev->type = 'bid';

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
            // Marking offer as completed
            $offer = Acceptedbid::where('job_id', $jobId)->first();
            if($offer)
            {
                $offer->is_endcontract = '1';
                $offer->status = '3';
                $offer->save();
            }

            // Mark job as completed
            $job = Job::where('id', $jobId)->first();
            if($job)
            {
                $job->status = '3';
                $job->job_status = '2';
                $job->is_completed = '1';
                $job->show_completed = '1';
                $job->completed_at = Carbon::now('UTC');
                $job->save();
            }
            // Update booking status
            $booking = Booking::where('status', 1)->where('job_id', $jobId)->first();
            if ($booking) {
                $booking->working_status = 1;
                $booking->status = 3;
                $booking->save();
                sendJobEmailWithNotification($booking?->buyer_id, $booking->seller_id, $booking->job_id, 'CONTRACT_HAS_ENDED', false, $offer->job_id);
                sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' ended contract of job and reviewed '.$booking?->job?->title, route('admin.job.details',['id'=>$offer->job_id]));
            }


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
            \Log::error('Service review error: ' . $e->getMessage());
            $notify[] = ['error', 'Something went wrong. Please try again!'];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function bidpayment(Request $request)
    {

        $user = Auth::user();
        $work = Buyercounterofferdata::where('bid_id', $request->acceptedbid)->first();
        $cutamount = DB::table('user_biding')->where('id', $request->acceptedbid)->first();
        if ($user->balance < $request->amount) {
            $notify[] = ['success', 'Recharge your account to pay'];
            return redirect()->back()->withNotify($notify);
        }
        $all = ($request->amount * $cutamount->cutout) / 100;
        $now = $request->amount - $all;
        $payableAmount = $now;

        $seller = User::where('id', $work->reciever_id)->firstOrFail();
        $seller->balance += $payableAmount;
        $seller->income += $payableAmount;
        $seller->save();
        rankUser($seller->id);


        if ($request->value == 'disputed') {
            $work->disputed_amount = $request->amount;
        } else {
            $work->disputed_amount = 0;
        }
        // echo($work->amount);exit;
        $work->is_payable = 0;
        $work->status = 5;
        $work->save();


        $transaction = new Transaction();
        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->amount = $payableAmount;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $work->id;
        $transaction->details = "bid Payment for " . $work->id;
        $transaction->save();

        // require base_path("vendor/autoload.php");
        notify($seller, 'PAYMENT_SELLER', [
            'amount' => getAmount($payableAmount),
            'order_number' => $work->id,
            'post_balance' => getAmount($seller->balance)
        ]);
        // send email
        $getJobName = DB::table('jobs')->where('id', $work->job_id)->first();
        sendJobEmailWithNotification($user->id, $seller->id, $getJobName->id, 'PAYMENT_REQUES_ACCEPTED');
        $notify[] = ['success', 'Payment has been sent.'];
        return back()->withNotify($notify);
    }
    public function bidhourlypayment(Request $request)
    {
        $user = Auth::user();

        $work = Jobworkdeliveryhourlybid::where('id', $request->work_id)->first();

        if ($user->balance < $request->amount) {
            $notify[] = ['success', 'Recharge your account to pay'];
            return redirect()->back()->withNotify($notify);
        }
        $cutamount = DB::table('user_biding')->where('id', $request->acceptedbid)->first();
        $all = ($request->amount * @$cutamount->cutout) / 100;
        $now = $request->amount - $all;
        $payableAmount = $now;
        $seller = User::where('id', $work->seller_id)->firstOrFail();
        $seller->balance += $payableAmount;
        $seller->income += $payableAmount;
        $seller->save();
        rankUser($seller->id);



        // echo($work->amount);exit;
        $work->is_payable = 0;
        $work->status = 5;
        $work->save();


        $transaction = new Transaction();
        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->amount = $payableAmount;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $work->id;
        $transaction->details = "hourly Payment for " . $work->id;
        $transaction->save();

        notify($seller, 'PAYMENT_SELLER', [
            'amount' => getAmount($payableAmount),
            'order_number' => $work->id,
            'post_balance' => getAmount($seller->balance)
        ]);
        $getJobName = DB::table('jobs')->where('id', $work->job_id)->first();

        require base_path("vendor/autoload.php");
        sendJobEmailWithNotification($user->id, $seller->id, $getJobName->id, 'PAYMENT_REQUES_ACCEPTED');

        $notify[] = ['success', 'Payment has been sent.'];
        return back()->withNotify($notify);
    }

    public function offerpayment(Request $request)
    {


        $user = Auth::user();

        $work = Sellercounterofferdata::where('id', $request->work_id)->first();
        //print_r($work);exit;
        if ($user->balance < $request->amount) {
            $notify[] = ['success', 'Recharge your account to pay'];
            return redirect()->back()->withNotify($notify);
        }
        $cutamount = DB::table('user_offers')->where('id', $request->acceptedoffer)->first();
        $all = ($request->amount * $cutamount->cutout) / 100;
        $now = $request->amount - $all;
        $payableAmount = $now;

        $seller = User::where('id', $work->sender_id)->firstOrFail();
        $seller->balance += $payableAmount;
        $seller->income += $payableAmount;
        $seller->save();
        rankUser($seller->id);

        if ($request->value == 'disputed') {
            $work->disputed_amount = $request->amount;
        } else {
            $work->disputed_amount = 0;
        }

        // echo($work->amount);exit;
        $work->is_payable = 0;
        $work->status = 5;
        $work->save();


        $transaction = new Transaction();
        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->amount = $payableAmount;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $work->id;
        $transaction->details = "bid Payment for " . $work->id;
        $transaction->save();

        notify($seller, 'PAYMENT_SELLER', [
            'amount' => getAmount($payableAmount),
            'order_number' => $work->id,
            'post_balance' => getAmount($seller->balance)
        ]);
        // send email
        $getJobName = DB::table('jobs')->where('id', $work->job_id)->first();
        $sender = DB::table('users')->where('id', $work->sender_id)->first();
        $reciever = DB::table('users')->where('id', $work->reciever_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $work->title;
            $type = 'payed_work';
            $mail->isHTML(true);
            $mail->Subject = ' Payment of Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Payment has been sent.'];
        return back()->withNotify($notify);
    }
    public function offerhourlypayment(Request $request)
    {


        $user = Auth::user();

        $work = Jobworkdeliveryhourlyoffer::where('id', $request->work_id)->first();

        if ($user->balance < $request->amount) {
            $notify[] = ['success', 'Recharge your account to pay'];
            return redirect()->back()->withNotify($notify);
        }
        $cutamount = DB::table('user_offers')->where('id', $request->acceptedoffer)->first();
        $all = ($request->amount * $cutamount->cutout) / 100;
        $now = $request->amount - $all;
        $payableAmount = $now;

        $seller = User::where('id', $work->seller_id)->firstOrFail();
        $seller->balance += $payableAmount;
        $seller->income += $payableAmount;
        $seller->save();
        rankUser($seller->id);



        // echo($work->amount);exit;
        $work->is_payable = 0;
        $work->status = 5;
        $work->save();


        $transaction = new Transaction();
        $user = Auth::user();
        $transaction->user_id = $user->id;
        $transaction->to_user = $seller->id;
        $transaction->amount = $payableAmount;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type = '+';
        $transaction->trx = $work->id;
        $transaction->details = "hourly Payment for " . $work->id;
        $transaction->save();

        notify($seller, 'PAYMENT_SELLER', [
            'amount' => getAmount($payableAmount),
            'order_number' => $work->id,
            'post_balance' => getAmount($seller->balance)
        ]);
        // send email
        $getJobName = DB::table('jobs')->where('id', $work->job_id)->first();
        $sender = DB::table('users')->where('id', $work->buyer_id)->first();
        $reciever = DB::table('users')->where('id', $work->seller_id)->first();

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
            $mail->addAddress($reciever->email);
            // $mail->addCC($request->emailCc);
            // $mail->addBCC($request->emailBcc);

            $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

            if (isset($_FILES['emailAttachments'])) {
                for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }

            $work = $work->title;
            $type = 'payed_work';
            $mail->isHTML(true);
            $mail->Subject = ' Payment of Work.';
            $contentBody = view('Emails/Job/workresponce', compact('sender', 'reciever', 'getJobName', 'work', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Payment has been sent.'];
        return back()->withNotify($notify);
    }
    public function acceptbuyerservicedisputeoffer(Request $request)
    {

        $disputedata = Servicedisputeoffers::findOrFail($request->work_id);
        $disputedata->status = '1';
        $disputedata->show_send = '1';
        $disputedata->save();
        $dispute = Servicedisputedata::findOrFail($disputedata->dispute_id);
        $dispute->status = '1';
        $dispute->save();
        $booking = Booking::findOrFail($dispute->booking_id);
        $booking->agreed_amount = $disputedata->seller_amount;
        $booking->status = 0;
        $booking->working_status = 1;
        $booking->save();

        // send email
        $service_single = DB::table('services')->where('id', $booking->service_id)->first();
        $emailuser = DB::table('users')->where('id', $service_single->user_id)->first();
        $booker = DB::table('users')->where('id', $booking->user_id)->first();
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

            $order_number = $booking->order_number;
            $type = 'dispute_offer_buyer_accept';
            $mail->isHTML(true);
            $mail->Subject = 'Dispute Offer Accepted.';
            $contentBody = view('Emails/Service/serviceworkacceptreject', compact('booker', 'service_single', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute offer has been accepted'];
        return back()->withNotify($notify);
    }
    public function declinebuyerservicedisputeoffer(Request $request)
    {

        $disputedata = Servicedisputeoffers::findOrFail($request->work_id);
        $disputedata->status = '3';
        $disputedata->show_send = '1';
        $disputedata->save();


        $dis = DB::table('service_dispute_data')->where('id', $disputedata->dispute_id)->first();
        $data = DB::table('bookings')->where('id', $dis->booking_id)->first();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $service_single->user_id)->first();
        $booker = DB::table('users')->where('id', $data->user_id)->first();
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

            $order_number = $data->order_number;
            $type = 'dispute_offer_buyer_declined';
            $mail->isHTML(true);
            $mail->Subject = 'Dispute Offer Declined.';
            $contentBody = view('Emails/Service/serviceworkacceptreject', compact('booker', 'service_single', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute offer has been declined'];
        return back()->withNotify($notify);
    }
    public function newbuyerservicedisputeoffer(Request $request)
    {

        $disputedata = new Servicedisputeoffers();
        $disputedata->dispute_id = $request->dispute_id;
        $disputedata->seller_amount = $request->seller_amount;
        $disputedata->buyer_amount = $request->buyer_amount;
        $disputedata->message = $request->seller_message;
        $disputedata->status = '0';
        $disputedata->send_by = 'buyer';
        $disputedata->save();

        $dis = DB::table('service_dispute_data')->where('id', $request->dispute_id)->first();
        $data = DB::table('bookings')->where('id', $dis->booking_id)->first();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $service_single->user_id)->first();
        $booker = DB::table('users')->where('id', $data->user_id)->first();
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

            $order_number = $data->order_number;
            $type = 'dispute_offer_buyer';
            $mail->isHTML(true);
            $mail->Subject = 'New Dispute Offer Sent.';
            $contentBody = view('Emails/Service/serviceworkacceptreject', compact('booker', 'service_single', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute counter offer has been sent'];
        return back()->withNotify($notify);
    }
    public function sendadminbuyerservicedisputeoffer(Request $request)
    {

        $disputedata = Servicedisputedata::findOrFail($request->dispute_id);
        $disputedata->status = '2';
        $disputedata->save();
        //echo "w";exit;
        // $dispute = Servicedisputedata::findOrFail($disputedata->dispute_id);
        //  $dispute->status = '2';
        //    $dispute->save();
        $data = Booking::findOrFail($disputedata->booking_id);
        $data->status = '7';
        $data->working_status = '6';
        $data->admin_reason = $request->reason;
        $data->save();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $service_single->user_id)->first();
        $booker = DB::table('users')->where('id', $data->user_id)->first();
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

            $order_number = $data->order_number;
            $type = 'dispute_admin';
            $mail->isHTML(true);
            $mail->Subject = 'Dispute Sent To Admin.';
            $contentBody = view('Emails/Service/serviceworkacceptreject', compact('booker', 'service_single', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (!$mail->send()) {

                $notify[] = ['error', 'Email not sent.'];
                return redirect()->back()->withNotify($notify);
            } else {

                $notify[] = ['success', 'Email has been sent!'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {

            $notify[] = ['error', 'Message Could not sent.'];
            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Dispute has been sent to admin'];
        return back()->withNotify($notify);
    }

    public function profile(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $next_step = $user?->completed_step + 1 > 5 ? 5 : $user?->completed_step + 1;
        // dd($next_step);
        session()->flash('activeStep', $next_step ?? 1);
        $userId = $user->id;
        $status = $user->status;
        $pageTitle = 'Profile';
        $emptyMessage = "No data found";
        $bpcount = DB::table('user_company')->where('user_id', Auth::user()->id)->count();
        $ucinfo = UserCompany::where('user_id', Auth::user()->id)->first();
        $country_code = $user->country_code;
        $educations = UserEducation::where('user_id', Auth::user()->id)->get();
        $certificates = UserCertification::where('user_id', Auth::user()->id)->get();

        $noti = DB::table('email_sms_templates')
            ->leftJoin('user_profile_alerts', function ($join) use ($userId) {
                $join->on('email_sms_templates.id', '=', 'user_profile_alerts.email_sms_template_id')
                    ->where('user_profile_alerts.user_id', '=', $userId);
            })
            ->select(
                'email_sms_templates.id',
                'email_sms_templates.name',
                'email_sms_templates.email_status',
                'user_profile_alerts.status as user_status' // Fetch the status from user_profile_alerts
            )
            ->where('email_sms_templates.email_status', 1)
            ->whereIn('email_sms_templates.notification_for', [1, 3])
            ->get();
        $userAlertsCount = DB::table('user_profile_alerts')->where('user_id', auth()->id())->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);

        return view($this->activeTemplate . 'user.buyer.profile', compact('pageTitle', 'country_code', 'countries', 'userAlertsCount', 'status', 'emptyMessage', 'user', 'bpcount', 'ucinfo', 'educations', 'certificates', 'noti'));
    }
    public function acceptTerms(Request $request)
    {
        // Store acceptance in the database or session
        $user = auth()->user();
        $user->terms_accepted = true; // Assuming there's a 'terms_accepted' column in the users table
        $user->save();

        return response()->json(['message' => 'Terms accepted successfully.']);
    }
    public function createServiceDispute(Request $request, $orderNumber)
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

        $dispute_amount = $request->dispute_amount;
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;

        if (($buyer_amount + $seller_amount) != $dispute_amount) {
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
        $booking->update([
            'status' => '8',
            'working_status' => '6'
        ]);

        // Send notification email inside try-catch
        try {
            sendJobEmailWithNotification(
                senderId: $booking->buyer_id,
                receiverId: $booking->seller_id,
                jobId: $booking->service_id,
                type: 'DISPUTE_CREATED',
                admin: false,
                userBiddingId: null,
                transactionId: null,
                transactionAmount: null
            );
        } catch (\Exception $emailException) {
            \Log::error('Email failed to send: ' . $emailException->getMessage());
        }

        return back()->withNotify([['success', 'Work has been successfully disputed']]);
    }

    public function saveTransaction(Request $request)
    {
        $data = $request->input('data');
        $payment_intent = $data['payment_intent'];
        $amount = (int)$payment_intent['amount']/100;
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $percentage_charge = $gatewayCurrency[0]->percent_charge;
        $fixed_charge = $gatewayCurrency[0]->fixed_charge;
        $percentageAmount = ($amount * $percentage_charge) / 100;
        $totalCharge = $percentageAmount + $fixed_charge;
        $finalAmount = round($amount - $totalCharge);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user->is_payment_verified = 1;
            $user->save();
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'to_user' => null,
                'booking_id' => null,
                'amount' => $finalAmount,
                'charge'=>$totalCharge,
                'trx_type' => '+',
                'details' => 'Amount Deposite',
                'post_balance' => $user->balance + $finalAmount,
                'trx' => $trx = uniqid('trx_'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->balance += $finalAmount;
            $user->save();
            $stripe_response = new StripeResponse();
            $stripe_response->user_id = $user->id;
            $stripe_response->transaction_id = $transaction->id;
            $stripe_response->response = $data;
            $stripe_response->amount = $finalAmount;
            $stripe_response->save();

            DB::commit();
            return response()->json([
                'status'=>true,
                'message'=>'Transaction Successfull'
            ]);
        }
        catch(Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status'=>false,
                'message'=>'Something went wrong. Please try again!'
            ]);
        }
    }

     /*
     * Withdraw Operation
     */

    public function withdrawMoney(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $withdrawMethod = WithdrawMethod::where('status', 1)->get();
        $pageTitle = 'Withdraw Money';
        $emptyMessage = "No data found";
        $onBoarding = StripeResponse::where([
            ['user_id',auth()->user()->id],
            ['type','onBoarding']
        ])
        ->first();

        $account = StripeResponse::where([
            ['user_id',auth()->user()->id],
            ['type','create_account']
        ])
        ->first();
        $isOnboardingComplete = false;
        if($account && !empty($account))
        {
            $process_controller = new ProcessController();
            $isOnboardingComplete = $process_controller->checkOnboardingStatus($account?->response['id']);
        }
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Withdraw Money' ],
        ];
        return view($this->activeTemplate . 'user.buyer.methods', compact('pageTitle','onBoarding','breadcrumbs', 'withdrawMethod', 'emptyMessage','isOnboardingComplete'));
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        // if ($request->amount < $method->min_limit) {
        //     $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
        //     return back()->withNotify($notify);
        // }
        // if ($request->amount > $method->max_limit) {
        //     $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
        //     return back()->withNotify($notify);
        // }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.buyer.withdraw.preview');
    }

    public function withdrawPreview( Request $request )
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $data = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->first();
        $pageTitle = 'Withdraw Preview';
        return view($this->activeTemplate. 'user.buyer.preview', compact('pageTitle', 'data'));
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->firstOrFail();
        $stripe_response = StripeResponse::where([
            ['type','create_account'],
            ['user_id',Auth::user()->id]
        ])
        ->first();
        if(!is_null($stripe_response))
        {
            $transfer_response = (new ProcessController())->transfer($stripe_response->response['id'],number_format($withdraw?->final_amount,0));
            $payout_response = (new ProcessController())->payout($stripe_response->response['id'],number_format($withdraw?->final_amount,0));
        }
        else
        {
            $notify[] = ['error', 'Please connect your account before withdrawing the money.'];
            return to_route('user.buyer.withdraw')->withNotify($notify);
        }

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg', 'jpeg', 'png']));
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);

        $user = auth()->user();
        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }


        if ($withdraw->amount > $user->balance) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = imagePath()['verify']['withdraw']['path'] . '/' . $directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
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
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
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
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $user->balance -= $withdraw->amount;
        $user->save();

        $user = Auth::user();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->to_user = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = ' Withdraw amount ' . getAmount($withdraw->amount) ;
        $transaction->trx = $withdraw->trx;
        $transaction->save();

        $ctransaction = new Transaction();
        $ctransaction->user_id = $user->id;
        $ctransaction->to_user = $withdraw->user_id;
        $ctransaction->amount = $withdraw->charge;
        $ctransaction->post_balance = $user->balance;
        $ctransaction->trx_type = '-';
        $ctransaction->details =  ' Fee deducted '. getAmount($withdraw->charge);
        $ctransaction->trx = $withdraw->trx;
        $ctransaction->save();

        // $adminNotification = new AdminNotification();
        // $adminNotification->user_id = $user->id;
        // $adminNotification->title = 'New withdraw request from ' . $user->username;
        // $adminNotification->click_url = urlPath('admin.withdraw.details', $withdraw->id);
        // $adminNotification->save();

        // notify($user, 'WITHDRAW_REQUEST', [
        //     'method_name' => $withdraw->method->name,
        //     'method_currency' => $withdraw->currency,
        //     'method_amount' => getAmount($withdraw->final_amount),
        //     'amount' => getAmount($withdraw->amount),
        //     'charge' => getAmount($withdraw->charge),
        //     'currency' => $general->cur_text,
        //     'rate' => getAmount($withdraw->rate),
        //     'trx' => $withdraw->trx,
        //     'post_balance' => getAmount($user->balance),
        //     'delay' => $withdraw->method->delay
        // ]);

        sendJobAdminRealTimeNotification($user, 'WITHDRAW_REQUEST', '#');

        $notify[] = ['success', 'Amount Withdraw Successfully'];
        return to_route('user.buyer.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = "No data found";
        $data['emptyMessage'] = "No Data Found!";
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Withdraw Log' ],
        ];
        return view($this->activeTemplate . 'user.buyer.log', compact('pageTitle', 'withdraws', 'emptyMessage','breadcrumbs'));
    }

    public function changePassword()
    {
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Change Password' ],
        ];
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.buyer.password', compact('pageTitle','breadcrumbs'));
    }

     public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

}
