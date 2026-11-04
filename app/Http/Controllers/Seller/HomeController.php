<?php

namespace App\Http\Controllers\Seller;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Acceptedbid;
use App\Models\Acceptedoffer;
use App\Models\BidsLog;
use App\Models\Booking;
use App\Models\Buyercounteroffer;
use App\Models\Buyercounterofferdata;
use App\Models\Employerservicescount;
use App\Models\ExtraService;
use App\Models\Freelancermembershiptransactions;
use App\Models\GeneralSetting;
use App\Models\Job;
use App\Models\Jobdisputebid;
use App\Models\Jobdisputebiddata;
use App\Models\JobWorkDelivery;
use App\Models\Jobworkdeliverybid;
use App\Models\Jobworkdeliveryhourlybid;
use App\Models\Jobworkdeliveryhourlyoffer;
use App\Models\Jobworkdeliveryoffer;
use App\Models\JobworkIntervalEntry;
use App\Models\Notification;
use App\Models\ReviewRating;
use App\Models\Sellercounteroffer;
use App\Models\Sellercounterofferdata;
use App\Models\Service;
use App\Models\Servicedisputedata;
use App\Models\Servicedisputeoffers;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Userbiding;
use App\Models\UserOffer;
use App\Models\UserOfferMilestone;
use App\Models\WorkDelivery;
use App\Rules\FileTypeValidate;
use App\Services\MembershipService;
use App\Services\PaymentService;
use App\Traits\UbPriceStrategyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class HomeController extends Controller
{
    use UbPriceStrategyTrait;

    public function __construct(private MembershipService $membershipService)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $pageTitle = 'Buyer Dashboard';
        $emptyMessage = 'No data found';
        $transactions = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $totaltransactions = (int) Transaction::where('user_id', $user->id)->sum('amount');
        $currentBalance = (int) User::where('id', $user->id)->value('balance');
        $totalJob = Job::where('user_id', $user->id)->count();
        $regularJob = Employerservicescount::where('user_id', $user->id)->sum('normal_jobs');
        $featuredJob = Employerservicescount::where('user_id', $user->id)->sum('featured_jobs');
        $availableJob = $regularJob + $featuredJob;
        $ongoingJob = Job::where('user_id', $user->id)->where('job_status', 1)->where('is_completed', 0)->count();
        $ongoingService = Service::where('user_id', $user->id)->sum('status');
        $serviceBookings = Booking::where('user_id', $user->id)->where('status', '!=', 0)->whereNotNull('service_id')->count();
        $softwarePurchases = Booking::where('user_id', $user->id)->where('status', '!=', 0)->whereNotNull('software_id')->count();
        // $hireEmploys = Booking::where('user_id', $user->id)->where('status', '!=', 0)->whereNotNull('job_biding_id')->count();
        $hireEmploys = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('is_completed', '!=', 1);
        })->count();
        $bidingresponses = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->where('jobs.user_id', $user->id)->where('user_biding.status', 0)->count();
        $assignedjobs = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->where('jobs.user_id', $user->id)->where('user_biding.status', 1)->count();
        $completedjobs = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->where('jobs.user_id', $user->id)->where('user_biding.status', 2)->count();
        $userjobs = Employerservicescount::where('user_id', $user->id)->get();
        // $totalTransaction = Transaction::where('user_id', $user->id)->sum('amount');

        return view($this->activeTemplate.'user.buyer.dashboard', compact('ongoingService', 'user', 'pageTitle', 'emptyMessage', 'transactions', 'totaltransactions', 'totalJob', 'serviceBookings', 'softwarePurchases', 'hireEmploys', 'bidingresponses', 'userjobs', 'assignedjobs', 'completedjobs', 'currentBalance', 'availableJob', 'regularJob', 'featuredJob', 'ongoingJob'));
    }

    public function serviceBookeds()
    {
        $user = Auth::user();
        $pageTitle = 'Service booking list';
        $emptyMessage = 'No data found';
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })->with('seller')->latest()->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Service Booking'],
        ];

        return view($this->activeTemplate.'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    // Cancelled Bookings (assuming status = 4 is "Refund" as Cancelled)
    public function serviceBookingsCancelled()
    {
        $user = Auth::user();
        $pageTitle = 'Cancelled Service Bookings';
        $emptyMessage = 'No cancelled bookings found';
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->where('status', 4) // 4 => Refund (Cancelled)
            ->with('seller')
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Cancelled Service Booking'],
        ];

        return view($this->activeTemplate.'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    // Completed Bookings (status = 6)
    public function serviceBookingsCompleted()
    {
        $user = Auth::user();
        $pageTitle = 'Completed Service Bookings';
        $emptyMessage = 'No completed bookings found';
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->where('status', 6) // 6 => Completed
            ->with('seller')
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Completed Service Booking'],
        ];

        return view($this->activeTemplate.'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    // Active Bookings (status = 1, "Running")
    public function serviceBookingsActive()
    {
        $user = Auth::user();
        $pageTitle = 'Active Service Bookings';
        $emptyMessage = 'No active bookings found';
        $serviceBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })
            ->where('status', 3) // 1 => Running (Active)
            ->with('seller')
            ->latest()
            ->paginate(getPaginate());

        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Active Service Booking'],
        ];

        return view($this->activeTemplate.'user.seller.service_booking', compact('pageTitle', 'emptyMessage', 'serviceBookings', 'breadcrumbs'));
    }

    public function serviceBookingDetails($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = 'Service booking details';
        $emptyMessage = 'No data found';

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }
        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();

        return view($this->activeTemplate.'user.seller.service_booking_details', [
            'pageTitle' => $pageTitle,
            'emptyMessage' => $emptyMessage,
            'booking' => $booking,
            'addonPricesSum' => $addonPricesSum ?? 0,
            'paymentProcessingFee' => $fees['paymentProcessingFee'],
            'calculatedSellerServiceFee' => $fees['calculatedSellerServiceFee'],
            'extraPrice' => $extraPrice,
            'addOns' => $addOns,
            'packagePrice' => $fees['packagePrice'],
            'addOnPrice' => $fees['addOnsPrice'],
            'total' => $fees['total'],
            'actualReceivable' => $fees['actualReceivable'],
        ]);
    }

    public function depositHistory(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = 'Deposit History';
        $emptyMessage = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Deposit Log'],
        ];

        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs', 'breadcrumbs'));
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

        if (! empty($extraArray)) {
            foreach ($extraArray as $value) {
                $extra = ExtraService::find($value);
                if ($extra) {
                    $extraPrice += $extra->price;
                }
            }
        }

        return $extraPrice;
    }

    public function serviceBookingDetailsContract($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = 'Service booking Contract';
        $emptyMessage = 'No data found';

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }
        // Fetch add-ons for this booking (all columns)
        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();
        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');

        return view(
            $this->activeTemplate.'user.seller.service_booking_details_contract',
            [
                'pageTitle' => $pageTitle,
                'emptyMessage' => $emptyMessage,
                'booking' => $booking,
                'paymentProcessingFee' => $fees['paymentProcessingFee'],
                'calculatedSellerServiceFee' => $fees['calculatedSellerServiceFee'],
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'packagePrice' => $fees['packagePrice'],
                'addOnPrice' => $fees['addOnsPrice'],
                'total' => $fees['total'],
                'actualReceivable' => $fees['actualReceivable'],
            ]
        );
    }

    public function serviceBookingDetailsWorkprogress($id)
    {
        $user = Auth::user();
        $pageTitle = 'Service booking Progress';
        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->get();
        $workscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->count();
        $review = ReviewRating::where('data_id', decrypt($id))->where('type', 'job')->first();
        // $review=ReviewRating::where('data_id',decrypt($id))->where('type','service')->first();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();

        return view($this->activeTemplate.'user.seller.service_booking_details_work_in_progress', compact('pageTitle', 'booking', 'extraPrice', 'allworks', 'workscount', 'review', 'dispute_data', 'dispute_data_count'));
    }

    public function serviceBookingDetailsWorkcompleted1($id)
    {
        $user = Auth::user();
        $pageTitle = 'Service booking Completed';
        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $allworks = WorkDelivery::where('booking_id', decrypt($id))->where('status', 6)->where('service_id', $booking->service_id)->get();
        $allworkscount = WorkDelivery::where('booking_id', decrypt($id))->where('status', 6)->where('service_id', $booking->service_id)->count();
        $workscount = WorkDelivery::where('booking_id', decrypt($id))->where('service_id', $booking->service_id)->count();
        $review = ReviewRating::where('data_id', decrypt($id))->where('type', 'service')->first();
        // $review = ReviewRating::where('data_id', decrypt($id))->where('type', 'job')->first();
        $dispute_data = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->first();
        $dispute_data_count = Servicedisputedata::where('order_number', $booking->order_number)->where('service_id', $booking->service_id)->count();

        return view($this->activeTemplate.'user.seller.service_booking_details_work_in_progress_complete', compact('pageTitle', 'booking', 'extraPrice', 'allworks', 'workscount', 'review', 'dispute_data', 'dispute_data_count', 'allworkscount'));
    }

    public function serviceBookingDetailsWorkcompleted($id, PaymentService $paymentService)
    {
        $user = Auth::user();
        $pageTitle = 'Service booking work completed';
        $emptyMessage = 'No data found';

        $booking = Booking::where('id', decrypt($id))->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $extraPrice = 0;
        if ($booking->extra_service) {
            $extraPrice = $this->handleExtraService($booking);
        }

        $addOns = \App\Models\Additionalservice::where('booking_id', $booking->id)->get();
        $fees = $paymentService->calculateFees($booking->payment_session_id, 'service');

        return view(
            $this->activeTemplate.'user.seller.service_booking_details_work_completed',
            [
                'pageTitle' => $pageTitle,
                'emptyMessage' => $emptyMessage,
                'booking' => $booking,
                'paymentProcessingFee' => $fees['paymentProcessingFee'],
                'calculatedSellerServiceFee' => $fees['calculatedSellerServiceFee'],
                'extraPrice' => $extraPrice,
                'addOns' => $addOns,
                'packagePrice' => $fees['packagePrice'],
                'addOnPrice' => $fees['addOnsPrice'],
                'total' => $fees['total'],
                'totalBeforeFee' => $fees['totalBeforeFee'],
                'actualReceivable' => $fees['actualReceivable'],
            ]
        );
    }

    public function salesSoftware()
    {
        $user = Auth::user();
        $pageTitle = 'Software sales list';
        $emptyMessage = 'No data found';
        $salesSoftwares = Booking::whereHas('software', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '!=', '0')->with('user')->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'user.seller.sales_software', compact('pageTitle', 'emptyMessage', 'salesSoftwares'));
    }

    public function jobVacancycompleted()
    {

        $user = Auth::user();
        $pageTitle = 'Job list';
        $emptyMessage = 'No data found';
        $jobVacancys = Userbiding::where('user_id', $user->id)->where('is_completed_seller', 1)->with('user')->paginate(getPaginate());
        $jobVacancys2 = UserOffer::where('pro_id', $user->id)->where('is_completed_seller', 1)->get();

        return view($this->activeTemplate.'user.seller.job_vacancy_completed', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'jobVacancys2'));
    }

    public function jobVacancyoffersdetail($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job offer detail';
        $emptyMessage = 'No data found';
        $singlejob = Job::where('id', decrypt($id))->first();
        $offerdetailcount = UserOffer::where('job_id', decrypt($id))->where('pro_id', $user->id)->count();
        if ($offerdetailcount > 0) {
            $offerdetail = UserOffer::where('job_id', decrypt($id))->where('pro_id', $user->id)->first();
            $data = UserOfferMilestone::where('offer_id', $offerdetail->id)->get();
            $total_count = $data->sum('amount');
        } else {
            $offerdetail = '';
            $total_count = '';
        }

        return view($this->activeTemplate.'user.seller.job_vacancy_offers_detail', compact('pageTitle', 'emptyMessage', 'offerdetail', 'total_count', 'singlejob', 'offerdetailcount'));
    }

    public function jobVacancybids($id)
    {
        $user = Auth::user();
        $pageTitle = 'Job list bids';

        return view('templates.basic.user.seller.job_vacancy_bids', compact('pageTitle'));
    }

    public function jobVacancyworkspace($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job list workspace';
        $emptyMessage = 'No data found';
        $jobVacancys = Userbiding::where('user_id', $user->id)->with('user')->paginate(getPaginate());
        $singlejob = Job::where('id', decrypt($id))->first();
        $bidjob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        $usermembership = null;
        if (! empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_workspace', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'accepteddata', 'singlejob', 'usermembership'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_workspace_offer', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'singlejob', 'accepteddata', 'acceptedoffer', 'usermembership'));
        }
    }

    public function jobVacancyhourlytimesheet($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job list hourlytimesheet';
        $emptyMessage = 'No data found';
        $jobVacancys = Userbiding::where('user_id', $user->id)->with('user')->paginate(getPaginate());
        $bidjob = Job::where('id', decrypt($id))->first();
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $amount = Buyercounterofferdata::where('job_id', decrypt($id))->pluck('amount');
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id->where('amount', $acceptedbid->user_id))->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_hourlytimesheet', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'accepteddata', 'singlejob', 'amount'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_hourlytimesheet_offer', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'singlejob', 'acceptedoffer', 'accepteddata'));
        }
    }

    public function jobVacancyinvoicetab($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job list Accepted Bid';
        $emptyMessage = 'No data found';
        $jobVacancys = Userbiding::where('user_id', $user->id)->with('user')->paginate(getPaginate());
        $bidjob = Job::where('id', decrypt($id))->first();
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_invoicetab', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'accepteddata', 'singlejob'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_invoicetab', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'acceptedoffer', 'accepteddata', 'singlejob'));
        }
    }

    public function jobVacancyratingtab($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job list Accepted Bid';
        $emptyMessage = 'No data found';
        $jobVacancys = Userbiding::where('user_id', $user->id)->with('user')->paginate(getPaginate());
        $bidjob = Job::where('id', decrypt($id))->first();
        $singlejob = Job::where('id', decrypt($id))->first();
        $acceptedbid = Userbiding::where('job_id', decrypt($id))->where('seller_offer_responce', '2')->first();
        $acceptedoffer = UserOffer::where('job_id', decrypt($id))->where('buyer_offer_responce', '2')->first();
        if ($acceptedbid) {
            $accepteddata = Acceptedbid::where('job_id', decrypt($id))->where('bid_id', $acceptedbid->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedbid->user_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_ratingtab', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'accepteddata', 'singlejob'));
        } elseif ($acceptedoffer) {
            $accepteddata = Acceptedoffer::where('job_id', decrypt($id))->where('offer_id', $acceptedoffer->id)->where('buyer_id', $singlejob->user_id)->where('seller_id', $acceptedoffer->pro_id)->first();

            return view($this->activeTemplate.'user.seller.job_vacancy_ratingtaboffer', compact('pageTitle', 'emptyMessage', 'jobVacancys', 'bidjob', 'acceptedbid', 'acceptedoffer', 'accepteddata', 'singlejob'));
        }
    }

    public function submitByProjectWorks(Request $request, $id)
    {
        $data = Buyercounterofferdata::findOrFail($id);

        $buyerId = $data->sender_id;
        $sellerId = $data->reciever_id;

        $jobId = $data->job_id;

        $work = new Jobworkdeliverybid;
        $work->seller_id = $sellerId;
        $work->buyer_id = $buyerId;
        $work->bid_id = $data->bid_id;
        $work->job_id = $jobId;
        $work->data_id = $data->id;
        $work->job_type = 'byproject';
        $work->bid_type = 'fixed';

        // Path where the file will be stored
        $workFile = 'workFiles';  // A folder in storage/app/public

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            try {
                // Store file using public disk
                $filename = $file->store($workFile, 'public');  // This stores in storage/app/public/workFiles
            } catch (\Exception $exp) {
                $notify[] = ['error', 'File could not be uploaded.'];

                return back()->withNotify($notify);
            }

            $work->file = $filename; // Save the relative file path in the database
        }

        $work->cover = $request->details;
        $work->save();

        // Update status and revision count
        $data->milestone_status = '2';
        $data->revisions += 1;
        $data->updated_at = Carbon::now();
        $data->save();

        if($data->req_type == 'seller')
        {
            sendJobEmailWithNotification($buyerId,$sellerId, $jobId, 'PAYMENT_REQUEST_RECEIVED', false, $data->job_id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' requested payment for job '.$data?->job?->title, route('admin.job.details',['id'=>$data->job_id]));
        }
        else
        {
            sendJobEmailWithNotification($sellerId,$buyerId, $jobId, 'PAYMENT_REQUEST_RECEIVED', false, $data->job_id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' requested payment for job '.$data?->job?->title, route('admin.job.details',['id'=>$data->job_id]));
        }

        // Send email notification
        // sendJobEmailWithNotification($sellerId, $buyerId, $jobId, 'PAYMENT_REQUEST_RECEIVED', false, $data->job_id);


        // Success message
        $notify[] = ['success', 'Work has been submitted'];

        return back()->withNotify($notify);
    }

    public function getByProjectWork(Request $request, $dataId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $workDataList = Jobworkdeliverybid::where('data_id', $dataId)->orderBy('created_at', 'desc')->get();

        if ($workDataList->isEmpty()) {
            return response()->json(['error' => 'Work data not found.'], 404);
        }

        // Get associated buyer counter offer data (single record)
        $counterOfferData = Buyercounterofferdata::find($dataId);

        // Prepare the data to pass to the view
        $data = [
            'title' => 'Project Payment',
            'workDataList' => $workDataList->map(function ($workData) {
                return [
                    'workSubmittedOn' => \Carbon\Carbon::parse($workData->created_at)->format('d M Y'),
                    'attachment' => $workData->file,
                    'coverLetter' => $workData->cover,
                    'id' => $workData->id,
                    'reason' => $workData->reason,
                    'status' => $workData->status,
                    'statusText' => $workData->getStatusLabel(),
                ];
            }),
        ];

        // Load a view to display work details
        return view('seller.workspace.byprojectworkdetails', compact('data'));
    }

    public function getByMilestoneWork(Request $request, $dataId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        // Fetch the work delivery record associated with the given ID
        $workDelivery = Jobworkdeliverybid::find($dataId);

        if (! $workDelivery) {
            // Handle the case where no work delivery is found
            return redirect()->back()->withErrors('Work delivery not found.');
        }

        $title = $workDelivery->milestone_title; // Get the title from the work delivery
        $counterOfferId = $workDelivery->data_id; // Get the associated counter offer ID

        // Fetch all work data records associated with the counter offer ID and the milestone title
        $workDataList = Jobworkdeliverybid::where('data_id', $counterOfferId)->get();

        // Get the associated buyer counter offer data (single record)
        $counterOfferData = Buyercounterofferdata::find($counterOfferId);

        // Since we are no longer using JSON, we will handle status and title directly
        $milestoneStatus = $counterOfferData->milestone_status; // Assume this is now a single value
        $milestoneTitle = $counterOfferData->title; // Assume this is a single title now

        // Prepare the data to pass to the view
        $data = [
            'title' => $milestoneTitle,
            'workDataList' => $workDataList->map(function ($workData) {
                return [
                    'workSubmittedOn' => \Carbon\Carbon::parse($workData->created_at)->format('d M Y'),
                    'attachment' => $workData->file,
                    'coverLetter' => $workData->cover,
                    'id' => $workData->id,
                    'reason' => $workData->reason,
                    'status' => $workData->status,
                    'statusText' => $workData->getStatusLabel(),
                ];
            }),
        ];

        // Load a view to display work details
        return view('seller.workspace.byprojectworkdetails', compact('data'));
    }

    public function submitByMileStoneWork(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file|mimes:zip|max:25600', // Only zip files, max size 25MB (25600KB)
            'details' => 'nullable|string', // Add other fields as needed
        ]);

        // Retrieve the specific Buyercounterofferdata record
        $id = $request->work_id;
        $data = Buyercounterofferdata::findOrFail($id);

        // Update milestone status and increment revision count directly
        $data->milestone_status = 2; // Set status to '2' (assuming it signifies completed milestone)
        $data->revisions += 1; // Increment revision count
        $data->updated_at = \Carbon\Carbon::now();
        $data->save();

        $buyerId = $data->sender_id;
        $sellerId = $data->reciever_id;
        // Proceed with saving work details for this specific milestone
        if ($data->req_type == 'seller') {
            $sellerId = $data->sender_id;
            $buyerId = $data->reciever_id;
        }

        $jobId = $data->job_id;

        // Create new Jobworkdeliverybid record
        $work = new Jobworkdeliverybid;
        $work->seller_id = $sellerId;
        $work->buyer_id = $buyerId;
        $work->bid_id = $data->bid_id;
        $work->job_id = $jobId;
        $work->data_id = $data->id;
        $work->job_type = 'bymilestone';
        $work->bid_type = 'fixed';
        $work->milestone_title = $request->milestone_title; // Store the milestone title
        $work->cover = $request->details; // Store additional details

        // Path for file storage
        $workFile = 'workFiles';

        // Handle file upload

        $file = $request->file('file');
        $filename = $file->store($workFile, 'public'); // Store file in 'workFiles' directory
        $work->file = $filename;
        $work->save();
        // Send email notification
        sendJobEmailWithNotification($sellerId, $buyerId, $jobId, 'PAYMENT_REQUEST_RECEIVED', false, $jobId);
        sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' requested a payment for '.$work->job->title, route('admin.job.details',['id'=>$work->job->id]));

        // sendJobEmailWithNotification($sellerId, $buyerId, $jobId, 'PAYMENT_REQUEST_RECEIVED', false, $data->bid_id);

        return back()->with(['success' => 'Work has been submitted successfully']);
    }

    public function sendjobworkoffer(Request $request)
    {
        // $request->validate([
        //           'file' => ['required', new FileTypeValidate(['zip'])],
        //           'details' => 'required|max:1000',
        //           'work_type' => 'required|in:jobfile',
        //       ]);
        $user = Auth::user();
        //         if($request->work_type == "service"){
        //         	$booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function($q) use ($user){
        //         		$q->where('user_id', $user->id);
        // 	        })->first();
        // 			if(!$booking){
        // 				$notify[] = ['error', 'Invalid service booking'];
        // 				return back()->withNotify($notify);
        // 			}
        //         }
        //       if($request->work_type == "jobfile"){
        //       	$booking = UserBiding::where('id', $request->order_number)->first();

        // 	if(!$booking){
        // 		$notify[] = ['error', 'Invalid Job order'];
        // 		return back()->withNotify($notify);
        // 	}
        //       }
        // $booking->working_status = 2;
        // $booking->updated_at = Carbon::now();
        // $booking->save();

        $acceptedbid = Acceptedoffer::findOrFail($request->accepted_id);
        $job = Job::findOrFail($acceptedbid->job_id);
        $seller = User::findOrFail($acceptedbid->seller_id);
        $buyer = User::findOrFail($acceptedbid->buyer_id);
        $bid = Useroffer::findOrFail($acceptedbid->offer_id);
        $data = Sellercounterofferdata::findOrFail($request->dataa_id);

        $work = new Jobworkdeliveryoffer;
        $work->seller_id = $seller->id;
        $work->buyer_id = $buyer->id;
        $work->offer_id = $bid->id;
        $work->job_id = $job->id;
        $work->data_id = $data->id;
        $work->job_type = $job->budgettype;
        $work->bid_type = $bid->job_type;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];

                return back()->withNotify($notify);
            }
            $work->file = $filename;
        }
        $work->cover = $request->details;
        $work->save();

        $data->status = '2';
        $data->revisions += 1;
        $data->updated_at = Carbon::now();
        $data->save();
        $noti = new Notification;
        $noti->reciever_id = $buyer->id;
        $noti->sender_id = $seller->id;
        $noti->type = 'seller_send_work';
        $noti->job_id = $job->id;
        $noti->save();

        sendRealTimeNotification($buyer->id, 'seller_send_work');
        // send email
        $getJobName = DB::table('jobs')->where('id', $job->id)->first();
        $sender = DB::table('users')->where('id', $seller->id)->first();
        $reciever = DB::table('users')->where('id', $buyer->id)->first();
        // echo $getJobName->id;exit;
        sentEmail($reciever, 'PAYMENT_SELLER', [
            // add parameters later
        ]);
        // require base_path("vendor/autoload.php");
        // $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        // try {

        //     // Email server settings
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->Host       = env('MAIL_HOST');
        //     $mail->SMTPAuth   = true;
        //     $mail->Username   = env('MAIL_USERNAME');
        //     $mail->Password   = env('MAIL_PASSWORD');
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //     $mail->Port       = env('MAIL_PORT');
        //     //Recipients
        //     $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        //     $mail->addAddress($reciever->email);
        //     // $mail->addCC($request->emailCc);
        //     // $mail->addBCC($request->emailBcc);

        //     $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

        //     if (isset($_FILES['emailAttachments'])) {
        //         for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
        //             $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
        //         }
        //     }
        //     $work = $request->work;
        //     $type = 'seller_send_work';
        //     $mail->isHTML(true);
        //     $mail->Subject = ' Job Offer Work .';
        //     $contentBody = view('Emails/Job/bidworking', compact('sender', 'reciever', 'getJobName', 'type', 'work'))->render();
        //     // $mail->Body    = $user->ev_code;
        //     $mail->Body    =  $contentBody;

        //     // $mail->AltBody = plain text version of email body;

        //     if (!$mail->send()) {
        //         $notify[] = ['error', 'Email not sent.'];
        //         return redirect()->back()->withNotify($notify);
        //     } else {
        //         $notify[] = ['success', 'Email has been sent'];
        //         return redirect()->back()->withNotify($notify);
        //     }
        // } catch (Exception $e) {
        //     $notify[] = ['error', 'Message could not be sent.'];
        //     return redirect()->back()->withNotify($notify);
        // }
        // send email
        $notify[] = ['success', 'Work has been submitted'];

        return back()->withNotify($notify);
    }

    public function jobListDetails($id)
    {

        $user = Auth::user();
        $pageTitle = 'Job list details';
        $jobListDetails = Userbiding::whereHas('job', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '=', '0')->where('id', decrypt($id))->firstOrFail();

        return view($this->activeTemplate.'user.seller.job_details', compact('pageTitle', 'jobListDetails'));
    }

    public function bookingConfirm(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:bookings,order_number',
            'confirm' => 'required|in:approved,cancel',
        ]);
        $user = Auth::user();
        $general = GeneralSetting::first();
        $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if (! $booking) {
            $notify[] = ['error', 'Invalid service booking'];

            return back()->withNotify($notify);
        }
        if ($request->confirm == 'approved') {
            $booking->working_status = 4;
            $booking->updated_at = Carbon::now();
            $booking->save();

            sendJobEmailWithNotification(
                auth()->id(),                   // sender_id (probably current user)
                $booking->seller_id,           // reciever_id (the seller)
                $booking->service_id,          // job_id (here it's the service)
                'SERVICE_BOOKING_APPROVED',    // notification_type (customize this identifier as needed)
                false,                         // is_internal
                null,                          // bid_id (null if not applicable)
                $booking->id,                  // booking_id (if your function uses it)
                null,                          // custom_data or extra param
                null                           // offer_id or any optional id
            );

            $notify[] = ['success', 'Thanks For Receiving the booking'];

            return back()->withNotify($notify);
        } elseif ($request->confirm == 'cancel') {
            $booking->status = 4;
            $booking->working_status = 3;
            $booking->save();

            $user = User::where('id', $booking->user_id)->firstOrFail();
            $user->balance += $booking->amount;
            $user->save();

            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->amount = $booking->amount;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type = '+';
            $transaction->trx = $booking->order_number;
            $transaction->details = 'Refund Money '.$booking->order_number;
            $transaction->save();

            // send email
            notify($user, 'MONEY_REFUND', [
                'amount' => getAmount($booking->amount),
                'currency' => $general->cur_text,
                'order_number' => $booking->order_number,
                'post_balance' => getAmount($user->balance),
            ]);
            $notify[] = ['success', 'Refund money.'];

            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Invalid booking'];

            return back()->withNotify($notify);
        }
    }

    public function JobworkUpload(Request $request)
    {
        $request->validate([
            'file' => ['required'],
            'details' => 'required|max:1000',
            'work_type' => 'required|in:jobfile',
        ]);
        $user = Auth::user();
        //         if($request->work_type == "service"){
        //         	$booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function($q) use ($user){
        //         		$q->where('user_id', $user->id);
        // 	        })->first();
        // 			if(!$booking){
        // 				$notify[] = ['error', 'Invalid service booking'];
        // 				return back()->withNotify($notify);
        // 			}
        //         }
        if ($request->work_type == 'jobfile') {
            $booking = UserBiding::where('id', $request->order_number)->first();

            if (! $booking) {
                $notify[] = ['error', 'Invalid Job order'];

                return back()->withNotify($notify);
            }
        }
        $booking->status = 1;
        $booking->working_status = 2;
        $booking->updated_at = Carbon::now();
        $booking->save();

        $work = new JobWorkDelivery;
        $work->userbiding_id = $booking->id;
        $work->sender_id = auth()->user()->id;
        $work->receiver_id = $booking->user_id;
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
        $notify[] = ['success', 'Work has been submitted'];

        return back()->withNotify($notify);
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
        if ($request->work_type == 'service') {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('service_id')->whereHas('service', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();
            if (! $booking) {
                $notify[] = ['error', 'Invalid service booking'];

                return back()->withNotify($notify);
            }
        } elseif ($request->work_type == 'jobBiding') {
            $booking = Booking::where('order_number', $request->order_number)->whereNotNull('job_biding_id')->whereHas('biding', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

            if (! $booking) {
                $notify[] = ['error', 'Invalid Job order'];

                return back()->withNotify($notify);
            }
        }
        $booking->working_status = 2;
        $booking->updated_at = Carbon::now();
        $booking->save();

        $work = new WorkDelivery;
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

        $notify[] = ['success', 'Work has been submitted'];

        return back()->withNotify($notify);
    }

    public function userworkservicefileDownload($id)
    {
        $work = WorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->work_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userworkresponseservicefileDownload($id)
    {
        $work = WorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->responce_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function JobworkDeliveryDownload($id)
    {
        $work = JobWorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->work_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function workFileDownload($id)
    {
        $work = WorkDelivery::where('id', decrypt($id))->firstOrFail();
        $file = $work->work_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function follow($id)
    {
        $follow = User::where('id', $id)->firstOrFail();
        $user = Auth()->user();
        if ($follow->id != $user->id) {
            if ($user->following->find($id) == null) {
                $user->following()->attach($id);
                $notify[] = ['success', "Following $follow->username"];
            } else {
                $user->following()->detach($id);
                $notify[] = ['success', "Unfollow $follow->username"];
            }

            return back()->withNotify($notify);
        }
        $notify[] = ['error', "it's You!"];

        return back()->withNotify($notify);
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
        $pageTitle = 'Transaction Log';
        $emptyMessage = 'No data found';
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
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Transaction History'],
        ];

        return view($this->activeTemplate.'user.seller.transactions', compact('pageTitle', 'emptyMessage', 'transactions', 'bidsLog', 'breadcrumbs'));
    }

    public function reportLogs(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = 'Transaction Log';
        $emptyMessage = 'No data found';
        $transactions = Transaction::where('user_id', $user->id)->orderby('created_at', 'desc')->paginate(getPaginate());
        $bidsLog = BidsLog::where('user_id', $user->id)->orderby('created_at', 'desc')->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Reports Log'],
        ];

        return view($this->activeTemplate.'user.seller.report-logs', compact('pageTitle', 'breadcrumbs', 'emptyMessage', 'transactions', 'bidsLog'));
    }

    public function jobVacancybidsacceptresponce(Request $request)
    {
        // echo "string";exit;
        $id = $request->acceptresponce_id;

        $dec = Userbiding::find($id);
        $dec->seller_offer_responce = '2';
        $dec->priorty = 1;
        $submit = $dec->save();
        if ($submit) {
            $ab = new Acceptedbid;
            $ab->bid_id = $request->acceptresponce_id;
            $ab->buyer_id = $request->buyer_id;
            $ab->seller_id = $request->seller_id;
            $ab->job_id = $request->job_id;
            $ab->status = '2';
            $ab->save();
        }

        $job = Job::where('id', $dec->job_id)->first();

        // send approval notification of admin to BUYER
        sendJobEmailWithNotification($dec->user_id, $job->user_id, $job->id, 'SELLER_NOTIFY_BUYER_OFFER_ACCEPT');

        // $noti = new Notification();
        // $noti->reciever_id = $job->user_id;
        // $noti->sender_id = $dec->user_id;
        // $noti->type = 'seller_offer_accepted';
        // $noti->job_id = $job->id;
        // $noti->save();

        // sendRealTimeNotification($job->user_id, 'seller_offer_accepted');
        // send email
        // $getJobName = DB::table('jobs')->where('id', $job->id)->first();
        // $sender = DB::table('users')->where('id', $dec->user_id)->first();
        // $reciever = DB::table('users')->where('id', $job->user_id)->first();

        // require base_path("vendor/autoload.php");
        // $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        // try {

        //     // Email server settings
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->Host       = env('MAIL_HOST');
        //     $mail->SMTPAuth   = true;
        //     $mail->Username   = env('MAIL_USERNAME');
        //     $mail->Password   = env('MAIL_PASSWORD');
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //     $mail->Port       = env('MAIL_PORT');
        //     //Recipients
        //     $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        //     $mail->addAddress($reciever->email);
        //     // $mail->addCC($request->emailCc);
        //     // $mail->addBCC($request->emailBcc);

        //     $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

        //     if (isset($_FILES['emailAttachments'])) {
        //         for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
        //             $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
        //         }
        //     }

        //     $type = 'seller_accept_bid_counter_offer';
        //     $mail->isHTML(true);
        //     $mail->Subject = ' Job Bid Counter Offer Decline.';
        //     $contentBody = view('Emails/Job/bid', compact('sender', 'reciever', 'getJobName', 'type'))->render();
        //     // $mail->Body    = $user->ev_code;
        //     $mail->Body    =  $contentBody;

        //     // $mail->AltBody = plain text version of email body;

        //     if (!$mail->send()) {
        //         $notify[] = ['error', 'Email not sent.'];
        //         return redirect()->back()->withNotify($notify);
        //     } else {
        //         $notify[] = ['success', 'Email has been sent'];
        //         return redirect()->back()->withNotify($notify);
        //     }
        // } catch (Exception $e) {
        //     $notify[] = ['error', 'Message could not be sent.'];
        //     return redirect()->back()->withNotify($notify);
        // }
        // send email

        $notify[] = ['success', 'Offer successfully accepted'];

        return redirect()->back()->withNotify($notify);
    }

    public function jobVacancybidssellercancelbid(Request $request)
    {
        $id = $request->cancelbid_id;
        $dec = Userbiding::find($id);
        $dec->is_cancel_bid = '1';
        $dec->save();
        $job = Job::where('id', $dec->job_id)->first();

        if (! $job) {
            $notify[] = ['error', 'Invalid Job'];

            return redirect()->back()->withNotify($notify);
        }

        sendJobEmailWithNotification($dec->user_id, $job->user_id, $dec->job_id, 'PROPOSAL_WITHDRAWN');

        $notify[] = ['success', 'Bid successfully cancelled'];

        return redirect()->back()->withNotify($notify);
    }

    public function userbidsentfileDownload($id)
    {
        // $work = Userbiding::where('id', decrypt($id))->firstOrFail();
        // $file = $work->file;
        // $path = imagePath()['workFile']['path'];
        // $full_path = $path . '/' . $file;
        // $ext = pathinfo($file, PATHINFO_EXTENSION);
        // $mimetype = mime_content_type($full_path);
        // header('Content-Disposition: softwareFile; filename="' . $file . '.' . $ext . '";');
        // header("Content-Type: " . $mimetype);
        // return readfile($full_path);

        $work = Userbiding::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;

        if (! file_exists($full_path)) {
            abort(404, 'File not found.');
        }

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.basename($file).'";');
        header('Content-Type: '.$mimetype);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.filesize($full_path));

        // Clear the output buffer and read the file
        ob_clean();
        flush();
        readfile($full_path);
        exit;
    }

    public function userbidofferfileDownload($id)
    {
        $work = Buyercounteroffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function jobvacancyoffersoffer($id)
    {
        $user = Auth::user();
        $pageTitle = 'Send Offer Responce';
        $emptyMessage = 'No data found';
        $usermembership = null;
        if (! empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }
        }
        $offerdetail = UserOffer::where('id', decrypt($id))->first();

        return view($this->activeTemplate.'user.seller.job_vacancy_send_offer_responce', compact('pageTitle', 'emptyMessage', 'offerdetail', 'usermembership'));
    }

    public function jobvacancyofferscanceloffer(Request $request)
    {
        // echo $request->canceloffer_id;exit;
        $bid = UserOffer::find($request->canceloffer_id);
        $bid->is_cancel_offer = '1';
        $bid->save();
        $job = Job::find($bid->job_id);
        $noti = new Notification;
        $noti->reciever_id = $bid->buyer_id;
        $noti->sender_id = $bid->pro_id;
        $noti->type = 'seller_offeronjob_cancel';
        $noti->job_id = $job->id;
        $noti->save();

        sendRealTimeNotification($bid->buyer_id, 'seller_offeronjob_cancel');
        $notify[] = ['success', 'Offer successfully canceled'];

        return redirect()->back()->withNotify($notify);
    }

    public function jobvacancyofferscancelresponce(Request $request)
    {
        $id = $request->sendoffer_id;
        // echo $id;exit;
        $counterofferid = DB::table('seller_counter_offers')->where('offer_id', $id)->where('job_id', @$request->sendofferjob_id)->where('sender_id', @$request->sendoffersender_id)->where('reciever_id', @$request->sendofferrec_id)->first();
        $counterdata = Sellercounterofferdata::where('counter_offer_id', @$counterofferid->id)->get();
        foreach ($counterdata as $data) {
            $datadel = Sellercounterofferdata::find($data->id);
            $datadel->delete();
        }
        $offerdel = Sellercounteroffer::find($counterofferid->id);
        $offerdel->delete();
        // Buyercounterofferdata::where('bid_id',$id)->where('job_id',$request->sendofferjob_id)->where('sender_id',$request->sendoffersender_id)->delete->where('reciever_id',$request->sendofferrec_id)->delete();
        $sent = UserOffer::find($request->sendoffer_id);
        $sent->is_seller_offer_sent = '0';
        $sent->save();

        $noti = new Notification;
        $noti->reciever_id = $request->sendofferrec_id;
        $noti->sender_id = $request->sendoffersender_id;
        $noti->type = 'seller_offer_responce_cancel';
        $noti->job_id = $request->sendofferjob_id;
        $noti->save();

        sendRealTimeNotification($request->sendofferrec_id, 'seller_offer_response_cancel');

        $notify[] = ['success', 'Offer Responce successfully canceled'];

        return redirect()->back()->withNotify($notify);
    }

    public function jobvacancyofferssubmitresponce(Request $request)
    {

        if ($request->job_type == 'per_hour') {
            $this->validate($request, [
                'coverletter' => 'required',
            ]);
        } elseif ($request->job_type == 'fixed') {
            $this->validate($request, [
                'coverletter' => 'required',
            ]);
        }

        $offer = new Sellercounteroffer;
        $offer->job_id = $request->job_id;
        $offer->type = $request->job_type;
        $offer->offer_id = $request->bid_id;
        $offer->sender_id = $request->sender;
        $offer->reciever_id = $request->reciever;
        $offer->description = html_entity_decode(nl2br(e(@$request->coverletter)));
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];

                return back()->withNotify($notify);
            }
            $offer->file = $filename;
        }

        $done = $offer->save();
        if ($done) {
            $noti = new Notification;
            $noti->reciever_id = $request->reciever;
            $noti->sender_id = $request->sender;
            $noti->type = 'seller_responded_on_offer';
            $noti->job_id = $request->job_id;
            $noti->save();

            sendRealTimeNotification($request->reciever, 'seller_responded_on_offer');
            if ($request->job_type == 'per_hour') {
                $offerdata = new Sellercounterofferdata;
                $offerdata->counter_offer_id = $offer->id;
                $offerdata->job_id = $request->job_id;
                $offerdata->offer_id = $request->bid_id;
                $offerdata->sender_id = $request->sender;
                $offerdata->reciever_id = $request->reciever;
                $offerdata->days = $request->hours_weekly;
                $offerdata->amount = $request->hourly_amount;
                $offerdata->title = $request->h_title;
                $offerdata->save();
            } elseif ($request->job_type == 'fixed') {
                $title = $request->ms_title;
                $amount = $request->ms_amount;
                $days = $request->ms_days;
                foreach ($days as $key => $new) {
                    $offerdata = new Sellercounterofferdata;
                    $offerdata->counter_offer_id = $offer->id;
                    $offerdata->job_id = $request->job_id;
                    $offerdata->offer_id = $request->bid_id;
                    $offerdata->sender_id = $request->sender;
                    $offerdata->reciever_id = $request->reciever;
                    $offerdata->days = $days[$key];
                    $offerdata->amount = $amount[$key];
                    $offerdata->title = $title[$key];
                    $offerdata->save();
                }
            }
        }
        $sent = UserOffer::find($request->bid_id);
        $sent->is_seller_offer_sent = '1';
        if ($sent->buyer_offer_responce != '') {
            $sent->buyer_offer_responce = '0';
        }
        $sent->save();
        $single_bid_id = $request->job_id;

        // send email
        $getJobName = DB::table('jobs')->where('id', $request->job_id)->first();
        $reciever = DB::table('users')->where('id', $request->reciever)->first();
        $sender = DB::table('users')->where('id', $request->sender)->first();
        // print_r($sender);exit;
        require base_path('vendor/autoload.php');
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
            // Recipients
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

            $type = 'seller_offer_responce';
            $mail->isHTML(true);
            $mail->Subject = 'Offer Responce';
            $contentBody = view('Emails/Job/bid', compact('sender', 'reciever', 'getJobName', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Response of offer successfully sent'];

        return redirect()->route('user.job.vacancy.offers.detail', [encrypt($single_bid_id)])->withNotify($notify);
    }

    public function jobvacancyofferfiledownload($id)
    {
        $work = UserOffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function jobvacancyofferresponcefiledownload($id)
    {
        $work = Sellercounteroffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userworksentfileDownload($id)
    {
        $work = Jobworkdeliverybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userworksentofferfileDownload($id)
    {
        $work = Jobworkdeliveryoffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userworksentcounterfileDownload($id)
    {
        $work = Jobworkdeliverybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->reason_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userworksentcounterfileofferDownload($id)
    {
        $work = Jobworkdeliveryoffer::where('id', decrypt($id))->firstOrFail();
        $file = $work->reason_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function requestmsjobworkbid(Request $request)
    {
        // for single due date
        // $days_from_today = 0;
        // $due_date = Carbon::now()->format('Y-m-d');
        // if(count($request->milestone) > 0)
        // {
        //     $due_date = $request->milestone[0]['due_date'];
        //     $days_from_today = $request->milestone[0]['days_from_today'];
        // }

        // Decrypt the job ID and bid ID
        $jobId = decrypt($request->job_id);
        $user = Auth::user();
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('seller_id', $user->id)->first();
        // Fetch the offer
        $offer = Buyercounteroffer::where('job_id', $jobId)->where('status', 1)->firstOrFail();
        // $milestoneData = [];
        // foreach ($request->milestone as $milestone) {
        //     $milestoneRow [] = [
        //         'counter_offer_id' => $offer->id,
        //         'job_id' => $jobId,
        //         'sender_id' => $user->id,
        //         'reciever_id' => $offer->sender_id,
        //         'status' => '0',
        //         'milestone_status' => '8',
        //         'req_type' => 'seller',
        //         'days' => $milestone['days_from_today'],
        //         'due_date' => $milestone['due_date'],
        //         'amount' => $milestone['amount'],
        //         'title' => $milestone['title'],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];

        //     if ($acceptedBid->bid_id)
        //         $milestoneRow['bid_id'] = $acceptedBid->bid_id;
        //     $milestoneData[] = $milestoneRow;
        // }
        // dd($milestoneData);
        // Buyercounterofferdata::insert($milestoneData);
        // sendJobEmailWithNotification($user->id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_SELLER_REQUESTED', false, $offer->bid_id, jobType: $offer->type);
        // $notify[] = ['success', 'Milestone successfully requested'];
        // return redirect()->back()->withNotify($notify);

        // code for single due date in add more
        $buyer = User::find($offer->sender_id);
        $milestoneData = [];

        foreach ($request->milestone as $milestone) {
            $milestoneRow = [
                'counter_offer_id' => $offer->id,
                'job_id' => $jobId,
                'sender_id' => $user->id,
                'reciever_id' => $offer->sender_id,
                'status' => '0',
                'milestone_status' => '8',
                'req_type' => 'seller',
                'days' => $milestone['days_from_today'],
                'due_date' => $milestone['due_date'] ?? Carbon::now()->format('Y-m-d'),
                'amount' => $milestone['amount'],
                'title' => $milestone['title'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (! empty($acceptedBid->bid_id)) {
                $milestoneRow['bid_id'] = $acceptedBid->bid_id;
            }

            $milestoneData[] = $milestoneRow;
        }

        Buyercounterofferdata::insert($milestoneData);
        sendJobEmailWithNotification($user->id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_SELLER_REQUESTED', false, $offer->job_id, jobType: $offer->type);
        sendAdminRealTimeNotification(Auth::user(), 'Requested milestone to '.$buyer?->username, route('admin.job.details', ['id' => $offer->job_id]));

        $notify[] = ['success', 'Milestone successfully requested'];

        return redirect()->back()->withNotify($notify);
    }

    public function requestmsjobworkoffer(Request $request)
    {
        $offer = Sellercounteroffer::where('job_id', $request->job_id)->where('offer_id', $request->bid_id)->first();
        $title = $request->milestone_title;
        $amount = $request->milestone_amount;
        $days = $request->milestone_due_date;
        foreach ($days as $key => $new) {
            $offerdata = new Sellercounterofferdata;
            $offerdata->counter_offer_id = $offer->id;
            $offerdata->job_id = $request->job_id;
            $offerdata->offer_id = $request->bid_id;
            $offerdata->sender_id = $offer->sender_id;
            $offerdata->reciever_id = $offer->reciever_id;
            $offerdata->milestone_status = '0';
            $offerdata->req_type = 'seller';
            $offerdata->days = $days[$key];
            $offerdata->amount = $amount[$key];
            $offerdata->title = $title[$key];
            $offerdata->save();
        }

        $noti = new Notification;
        $noti->reciever_id = $offer->reciever_id;
        $noti->sender_id = $offer->sender_id;
        $noti->type = 'seller_request_milestone_bid';
        $noti->job_id = $request->job_id;
        $noti->save();

        sendRealTimeNotification($offer->reciever_id, 'seller_request_milestone_bid');

        $notify[] = ['success', 'Milestone successfully requested'];

        return redirect()->back()->withNotify($notify);
    }

    public function acceptextramsworkbid(Request $request)
    {
        $offer = Buyercounterofferdata::find(decrypt($request->milestone_accept_work_id));
        $offer->status = 1;
        $offer->milestone_status = '0';
        $offer->requested_milestone_status = 1;
        $offer->save();

        $buyer = User::find($offer->sender_id);
        if($buyer->balance < $offer->amount) {
            $notify[] = ['error', 'You have insufficient balance to accept this milestone. Please add funds to your wallet.'];
            return redirect()->back()->withNotify($notify);
        }
        $buyer->balance -= $offer->amount;
        $buyer->save();

        try {
            sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_APP_BY_SELLER', false, $offer->job_id, jobType: $offer->buyerCounterOffer->type);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' accepted additional milestone of job '.$offer?->job?->title, route('admin.job.details',['id'=>$offer->job_id]));
        } catch (\Exception $emailException) {
            // Log the email error but do not rollback the transaction
            \Log::error('Email failed to send: '.$emailException->getMessage());
        }

        $notify[] = ['success', 'Requested Milestone successfully accepted'];

        return redirect()->back()->withNotify($notify);
    }

    public function rejectextramsworkbid(Request $request)
    {
        $offer = Buyercounterofferdata::find(decrypt($request->milestone_reject_work_id));
        $offer->req_responce_status = 'reject';
        $offer->milestone_status = '9';
        $offer->save();

        try {
            // sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_APP_BY_SELLER', false, $offer->bid_id, jobType: $offer->buyerCounterOffer->type);
            sendJobEmailWithNotification($offer->reciever_id, $offer->sender_id, $offer->job_id, 'ADDITIONAL_MILESTONE_REJ_BY_SELLER', false, $offer->job_id, jobType: $offer->buyerCounterOffer->type);
        } catch (\Exception $emailException) {
            // Log the email error but do not rollback the transaction
            \Log::error('Email failed to send: '.$emailException->getMessage());
        }

        $notify[] = ['success', 'Requested Milestone successfully rejected'];

        return redirect()->back()->withNotify($notify);
    }

    public function acceptextramsworkoffer(Request $request)
    {
        $offer = Sellercounterofferdata::find($request->work_id);
        $offer->milestone_status = '1';
        $offer->save();

        sendRealTimeNotification($offer->sender_id, 'seller_accept_request_milestone_bid');

        $notify[] = ['success', 'Requested Milestone successfully accepted'];

        return redirect()->back()->withNotify($notify);
    }

    public function rejectextramsworkoffer(Request $request)
    {
        $offer = Sellercounterofferdata::find($request->work_id);

        $offer->req_responce_status = 'reject';
        $offer->milestone_status = '2';
        $offer->save();

        $noti = new Notification;
        $noti->reciever_id = $offer->sender_id;
        $noti->sender_id = $offer->reciever_id;
        $noti->type = 'seller_reject_request_milestone_bid';
        $noti->job_id = $offer->job_id;
        $noti->save();

        sendRealTimeNotification($offer->sender_id, 'seller_reject_request_milestone_bid');

        $notify[] = ['success', 'Requested Milestone successfully rejected'];

        return redirect()->back()->withNotify($notify);
    }

    public function getAmount() {}

    public function sendtimesheetworkbid(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'memo' => 'required|string|max:255',
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'total_hours' => 'required|integer|min:0',
                'total_minutes' => 'required|integer|min:0',
                'file' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:2048',
            ], [
                'memo.required' => 'The memo field is required.',
                'memo.string' => 'The memo must be a valid string.',
                'memo.max' => 'The memo may not be greater than 255 characters.',
                'date.required' => 'The date field is required.',
                'date.date' => 'Please provide a valid date.',
                'start_time.required' => 'Start time is required.',
                'start_time.date_format' => 'Start time must be in HH:MM format.',
                'end_time.required' => 'End time is required.',
                'end_time.date_format' => 'End time must be in HH:MM format.',
                'total_hours.required' => 'Total hours are required.',
                'total_hours.integer' => 'Total hours must be a number.',
                'total_hours.min' => 'Total hours cannot be negative.',
                'total_minutes.required' => 'Total minutes are required.',
                'total_minutes.integer' => 'Total minutes must be a number.',
                'total_minutes.min' => 'Total minutes cannot be negative.',
                'file.file' => 'The uploaded file must be valid.',
                'file.mimes' => 'Allowed file types: pdf, doc, docx, zip, jpg, jpeg, png.',
                'file.max' => 'File size must not exceed 2MB.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $id = decrypt($request->buyerCounterOfferDataId);

            $datawith = Buyercounterofferdata::findOrFail($id);
            $buyerCounterOffer = Buyercounteroffer::find($datawith->counter_offer_id);
            $accepted = Acceptedbid::where('bid_id', $datawith->bid_id)->first();

            $weeklyHoursLimit = $datawith->days ?? null;

            if ($weeklyHoursLimit !== null && $weeklyHoursLimit > 0) {
                
                $weekStart = Carbon::parse($request->date)->startOfWeek()->toDateString(); // Monday
                $weekEnd   = Carbon::parse($request->date)->endOfWeek()->toDateString();   // Sunday

                $alreadyUsed = Jobworkdeliveryhourlybid::where('seller_id', $accepted->seller_id)
                    ->where('buyer_id', $accepted->buyer_id)
                    ->where('bid_id', $datawith->bid_id)
                    ->where('job_id', $datawith->job_id)
                    ->whereBetween('date_start', [$weekStart, $weekEnd])
                    ->where('status', '!=', 2) // Deleted entries ko exclude karo
                    ->selectRaw('COALESCE(SUM(time), 0) as total_hours, COALESCE(SUM(minutes), 0) as total_minutes')
                    ->first();
                $usedTotalMinutes = ($alreadyUsed->total_hours * 60) + $alreadyUsed->total_minutes;
                
                $weeklyLimitMinutes = $weeklyHoursLimit * 60;
                
                $remainingMinutes = $weeklyLimitMinutes - $usedTotalMinutes;
                
                $newEntryMinutes = ($request->total_hours * 60) + $request->total_minutes;

                if ($newEntryMinutes > $remainingMinutes) {
                    
                    $remainingHours   = (int) floor($remainingMinutes / 60);
                    $remainingMins    = $remainingMinutes % 60;
                    
                    if ($remainingMinutes <= 0) {
                        $notify[] = ['error', "You have used all your weekly hours ({$weeklyHoursLimit}h). No more timesheet entries can be submitted for this week."];
                    } else {
                        $notify[] = ['error', "Weekly limit is {$weeklyHoursLimit} hours. Only {$remainingHours}h {$remainingMins}m remaining this week. You cannot log more than the remaining hours."];
                    }

                    return redirect()->back()->withNotify($notify);
                }
            }

            $utcStartTime = Carbon::createFromFormat('H:i', $request->start_time, $request->timezone)->setTimezone('UTC');
            $utcEndTime   = Carbon::createFromFormat('H:i', $request->end_time, $request->timezone)->setTimezone('UTC');

            $existingInterval = DB::table('job_work_intervals')
                ->join('job_work_delivery_houly_bid', 'job_work_intervals.hourly_bid_id', '=', 'job_work_delivery_houly_bid.id')
                ->where('job_work_delivery_houly_bid.date_start', $request->date)
                ->where('job_work_intervals.is_manual', 0)
                ->where('job_work_intervals.status', '!=', 10)
                ->where(function ($query) use ($utcStartTime, $utcEndTime) {
                    $query->where(function ($subQuery) use ($utcStartTime) {
                        $subQuery->where('job_work_intervals.interval_end_time', '=', '00:00:00')
                            ->where('job_work_intervals.interval_start_time', '<=', $utcStartTime->toTimeString());
                    })->orWhere(function ($subQuery) use ($utcStartTime, $utcEndTime) {
                        $subQuery->where('job_work_intervals.interval_start_time', '<', $utcEndTime->toTimeString())
                            ->where('job_work_intervals.interval_end_time', '>=', $utcStartTime->toTimeString());
                    });
                })
                ->exists();

            if ($existingInterval) {
                $notify[] = ['error', 'You cannot log manual timesheet entries while the tracker is on for the same time period.'];
                return redirect()->back()->withNotify($notify);
            }

            $existingHourlyBid = Jobworkdeliveryhourlybid::where('seller_id', $accepted->seller_id)
                ->where('buyer_id', $accepted->buyer_id)
                ->where('bid_id', $accepted->bid_id)
                ->where('job_id', $accepted->job_id)
                ->where('date_start', $request->date)
                ->where(function ($query) use ($utcStartTime, $utcEndTime) {
                    $query->where(function ($subQuery) use ($utcStartTime) {
                        $subQuery->whereTime('end_time', '>', $utcStartTime->toTimeString())
                            ->whereTime('start_time', '<=', $utcStartTime->toTimeString());
                    })->orWhere(function ($subQuery) use ($utcStartTime, $utcEndTime) {
                        $subQuery->whereTime('start_time', '<', $utcEndTime->toTimeString())
                            ->whereTime('end_time', '>', $utcStartTime->toTimeString());
                    });
                })
                ->first();

            $blockCost = round(
                $datawith->amount * $request->total_hours +
                ($request->total_minutes / 60 * $datawith->amount),
                2
            );

            if ($existingHourlyBid) {
                if ($existingHourlyBid->status == 2) {
                    $existingHourlyBid->status     = 1;
                    $existingHourlyBid->time       = $request->total_hours;
                    $existingHourlyBid->minutes    = $request->total_minutes;
                    $existingHourlyBid->total_cost = $blockCost;
                    $existingHourlyBid->start_time = $utcStartTime->toTimeString();
                    $existingHourlyBid->end_time   = $utcEndTime->toTimeString();
                    $existingHourlyBid->memo       = $request->memo;

                    if ($request->hasFile('file')) {
                        $path = $request->file('file')->store('manual_work', 'public');
                        $existingHourlyBid->file = $path;
                    }

                    $existingHourlyBid->save();
                    JobworkIntervalEntry::where('hourly_bid_id', $existingHourlyBid->id)->delete();
                } else {
                    $existingHourlyBid->time       += $request->total_hours;
                    $existingHourlyBid->minutes    += $request->total_minutes;
                    $existingHourlyBid->total_cost += $blockCost;
                    $existingHourlyBid->save();
                }

                $intervals = $this->generateIntervals($request->start_time, $request->end_time);
                foreach ($intervals as $interval) {
                    JobworkIntervalEntry::create([
                        'hourly_bid_id'         => $existingHourlyBid->id,
                        'interval_start_time'   => $interval['start'],
                        'interval_end_time'     => $interval['end'],
                        'status'                => 1,
                        'is_manual'             => 1,
                    ]);
                }
            } else {
                $hourlyBid                = new Jobworkdeliveryhourlybid;
                $hourlyBid->seller_id     = $accepted->seller_id;
                $hourlyBid->buyer_id      = $accepted->buyer_id;
                $hourlyBid->bid_id        = $datawith->bid_id;
                $hourlyBid->job_id        = $datawith->job_id;
                $hourlyBid->status        = 1;
                $hourlyBid->data_id       = $datawith->id;
                $hourlyBid->date_start    = $request->date;
                $hourlyBid->time          = $request->total_hours;
                $hourlyBid->minutes       = $request->total_minutes;
                $hourlyBid->start_time    = $utcStartTime->toTimeString();
                $hourlyBid->end_time      = $utcEndTime->toTimeString();
                $hourlyBid->total_cost    = $blockCost;
                $hourlyBid->per_hour_rate = $datawith->amount;
                $hourlyBid->memo          = $request->memo;

                if ($request->hasFile('file')) {
                    $path = $request->file('file')->store('manual_work', 'public');
                    $hourlyBid->file = $path;
                }

                $hourlyBid->save();

                $intervals = $this->generateIntervals($request->start_time, $request->end_time);
                foreach ($intervals as $interval) {
                    JobworkIntervalEntry::create([
                        'hourly_bid_id'       => $hourlyBid->id,
                        'interval_start_time' => $interval['start'],
                        'interval_end_time'   => $interval['end'],
                        'status'              => 1,
                        'is_manual'           => 1,
                    ]);
                }
            }

            $buyer = User::find($accepted->buyer_id);
            sendJobEmailWithNotification($accepted->seller_id, $accepted->buyer_id, $accepted->job_id, 'SELLER_SEND_TIMESHEET', false, null, null, null, $accepted->job_id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()->username . ' send manual timesheet to ' . $buyer?->username, route('admin.job.details', ['id' => $accepted->job_id]));

            DB::commit();
            $notify[] = ['success', 'Timesheet successfully sent!'];
            return redirect()->back()->withNotify($notify);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $notify[] = ['error', $e->errors()];
            return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'An error occurred: ' . $e->getMessage()];
            return redirect()->back()->withNotify($notify);
        }
    }
    
    // public function sendtimesheetworkbid(Request $request)
    // {
    //     dd($request->all());
    //     try {
    //         DB::beginTransaction();
    //         $validator = Validator::make($request->all(), [
    //             'memo' => 'required|string|max:255',
    //             'date' => 'required|date',
    //             'start_time' => 'required|date_format:H:i',
    //             'end_time' => 'required|date_format:H:i',
    //             'total_hours' => 'required|integer|min:0',
    //             'total_minutes' => 'required|integer|min:0',
    //             'file' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:2048',
    //         ], [
    //             'memo.required' => 'The memo field is required.',
    //             'memo.string' => 'The memo must be a valid string.',
    //             'memo.max' => 'The memo may not be greater than 255 characters.',

    //             'date.required' => 'The date field is required.',
    //             'date.date' => 'Please provide a valid date.',

    //             'start_time.required' => 'Start time is required.',
    //             'start_time.date_format' => 'Start time must be in HH:MM format.',

    //             'end_time.required' => 'End time is required.',
    //             'end_time.date_format' => 'End time must be in HH:MM format.',

    //             'total_hours.required' => 'Total hours are required.',
    //             'total_hours.integer' => 'Total hours must be a number.',
    //             'total_hours.min' => 'Total hours cannot be negative.',

    //             'total_minutes.required' => 'Total minutes are required.',
    //             'total_minutes.integer' => 'Total minutes must be a number.',
    //             'total_minutes.min' => 'Total minutes cannot be negative.',

    //             'file.file' => 'The uploaded file must be valid.',
    //             'file.mimes' => 'Allowed file types: pdf, doc, docx, zip, jpg, jpeg, png, gif.',
    //             'file.max' => 'File size must not exceed 2MB.',
    //         ]);

    //         if ($validator->fails()) {
    //             return redirect()
    //                 ->back()
    //                 ->withErrors($validator)
    //                 ->withInput();
    //         }

    //         $utcStartTime = Carbon::createFromFormat('H:i', $request->start_time, $request->timezone)->setTimezone('UTC');
    //         $utcEndTime = Carbon::createFromFormat('H:i', $request->end_time, $request->timezone)->setTimezone('UTC');

    //         // Check for existing auto-tracked intervals on the same date
    //         $existingInterval = DB::table('job_work_intervals')
    //             ->join('job_work_delivery_houly_bid', 'job_work_intervals.hourly_bid_id', '=', 'job_work_delivery_houly_bid.id')
    //             ->where('job_work_delivery_houly_bid.date_start', $request->date) // Same date
    //             ->where('job_work_intervals.is_manual', 0) // Auto-tracked interval
    //             ->where('job_work_intervals.status', '!=', 10) // Not marked as deleted
    //             ->where(function ($query) use ($utcStartTime, $utcEndTime) {
    //                 $query->where(function ($subQuery) use ($utcStartTime) {
    //                     $subQuery->where('job_work_intervals.interval_end_time', '=', '00:00:00') // Ongoing tracker
    //                         ->where('job_work_intervals.interval_start_time', '<=', $utcStartTime->toTimeString()); // Started before or at manual log start
    //                 })->orWhere(function ($subQuery) use ($utcStartTime, $utcEndTime) {
    //                     $subQuery->where('job_work_intervals.interval_start_time', '<', $utcEndTime->toTimeString())
    //                         ->where('job_work_intervals.interval_end_time', '>=', $utcStartTime->toTimeString()); // Overlapping intervals
    //                 });
    //             })
    //             ->exists();

    //         if ($existingInterval) {
    //             $notify[] = ['error', 'You cannot log manual timesheet entries while the tracker is on for the same time period.'];

    //             return redirect()->back()->withNotify($notify);
    //         }

    //         // Decrypt buyerCounterOfferDataId and find necessary data
    //         $id = decrypt($request->buyerCounterOfferDataId);
    //         $datawith = Buyercounterofferdata::findOrFail($id);
    //         $buyerCounterOffer = Buyercounteroffer::find($datawith->counter_offer_id);
    //         $accepted = Acceptedbid::where('bid_id', $datawith->bid_id)->first();

    //         // Check if an overlapping entry exists in Jobworkdeliveryhourlybid
    //         $existingHourlyBid = Jobworkdeliveryhourlybid::where('seller_id', $accepted->seller_id)
    //             ->where('buyer_id', $accepted->buyer_id)
    //             ->where('bid_id', $accepted->bid_id)
    //             ->where('job_id', $accepted->job_id)
    //             ->where('date_start', $request->date)
    //             ->where(function ($query) use ($utcStartTime, $utcEndTime) {
    //                 $query->where(function ($subQuery) use ($utcStartTime) {
    //                     $subQuery->whereTime('end_time', '>', $utcStartTime->toTimeString()) // Use `>` instead of `>=` for non-overlap
    //                         ->whereTime('start_time', '<=', $utcStartTime->toTimeString());
    //                 })->orWhere(function ($subQuery) use ($utcStartTime, $utcEndTime) {
    //                     $subQuery->whereTime('start_time', '<', $utcEndTime->toTimeString())
    //                         ->whereTime('end_time', '>', $utcStartTime->toTimeString()); // Use `>` instead of `>=` for non-overlap
    //                 });
    //             })
    //             ->first();

    //         // Calculate the total cost for this block
    //         $blockCost = round(
    //             $datawith->amount * $request->total_hours +
    //             ($request->total_minutes / 60 * $datawith->amount),
    //             2
    //         );

    //         if ($existingHourlyBid) {
    //             if ($existingHourlyBid->status == 2) {
    //                 // Reset all fields for a deleted (status = 2) entry
    //                 $existingHourlyBid->status = 1; // Reactivate the entry
    //                 $existingHourlyBid->time = $request->total_hours;
    //                 $existingHourlyBid->minutes = $request->total_minutes;
    //                 $existingHourlyBid->total_cost = $blockCost;
    //                 $existingHourlyBid->start_time = $utcStartTime->toTimeString(); // Reset start time
    //                 $existingHourlyBid->end_time = $utcEndTime->toTimeString();     // Reset end time
    //                 $existingHourlyBid->memo = $request->memo;

    //                 if ($request->hasFile('file')) {
    //                     $path = $request->file('file')->store('manual_work', 'public');
    //                     $existingHourlyBid->file = $path;
    //                 }

    //                 $existingHourlyBid->save();

    //                 // Remove any existing intervals associated with the deleted bid
    //                 JobworkIntervalEntry::where('hourly_bid_id', $existingHourlyBid->id)->delete();
    //             } else {
    //                 // Update the existing Jobworkdeliveryhourlybid
    //                 $existingHourlyBid->time += $request->total_hours;
    //                 $existingHourlyBid->minutes += $request->total_minutes;
    //                 $existingHourlyBid->total_cost += $blockCost;
    //                 $existingHourlyBid->save();
    //             }
    //             // Add intervals for the existing hourly bid
    //             $intervals = $this->generateIntervals($request->start_time, $request->end_time);
    //             foreach ($intervals as $interval) {
    //                 JobworkIntervalEntry::create([
    //                     'hourly_bid_id' => $existingHourlyBid->id,
    //                     'interval_start_time' => $interval['start'],
    //                     'interval_end_time' => $interval['end'],
    //                     'status' => 1, // Active
    //                     'is_manual' => 1,
    //                 ]);
    //             }
    //         } else {

    //             // Create a new Jobworkdeliveryhourlybid
    //             $hourlyBid = new Jobworkdeliveryhourlybid;
    //             $hourlyBid->seller_id = $accepted->seller_id;
    //             $hourlyBid->buyer_id = $accepted->buyer_id;
    //             // $hourlyBid->bid_id = $accepted->bid_id;
    //             // $hourlyBid->job_id = $accepted->job_id;

    //             $hourlyBid->bid_id = $datawith->bid_id;
    //             $hourlyBid->job_id = $datawith->job_id;

    //             $hourlyBid->status = 1; // Active
    //             $hourlyBid->data_id = $datawith->id;
    //             $hourlyBid->date_start = $request->date;
    //             $hourlyBid->time = $request->total_hours;
    //             $hourlyBid->minutes = $request->total_minutes;

    //             $hourlyBid->start_time = $utcStartTime->toTimeString(); // Save the start time in UTC
    //             $hourlyBid->end_time = $utcEndTime->toTimeString();     // Save the end time in UTC

    //             $hourlyBid->total_cost = $blockCost;
    //             $hourlyBid->per_hour_rate = $datawith->amount;
    //             $hourlyBid->memo = $request->memo;

    //             if ($request->hasFile('file')) {
    //                 $path = $request->file('file')->store('manual_work', 'public');
    //                 $hourlyBid->file = $path;
    //             }

    //             $hourlyBid->save();

    //             // Add intervals for the new hourly bid
    //             $intervals = $this->generateIntervals($request->start_time, $request->end_time);
    //             foreach ($intervals as $interval) {
    //                 JobworkIntervalEntry::create([
    //                     'hourly_bid_id' => $hourlyBid->id,
    //                     'interval_start_time' => $interval['start'],
    //                     'interval_end_time' => $interval['end'],
    //                     'status' => 1, // Active
    //                     'is_manual' => 1,
    //                 ]);
    //             }
    //         }

    //         $buyer = User::find($accepted->buyer_id);
    //         sendJobEmailWithNotification($accepted->seller_id, $accepted->buyer_id,  $accepted->job_id, 'SELLER_SEND_TIMESHEET', false, null, null, null, $accepted->job_id);
    //         sendAdminRealTimeNotification(Auth::user(), Auth::user()->username.' send manual timesheet to '.$buyer?->username, route('admin.job.details', ['id' => $accepted->job_id]));
            
    //         DB::commit();
    //         $notify[] = ['success', 'Timesheet successfully sent!'];

    //         return redirect()->back()->withNotify($notify);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         $notify[] = ['error', $e->errors()];

    //         return redirect()->back()->withNotify($notify);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $notify[] = ['error', 'An error occurred: '.$e->getMessage()];

    //         return redirect()->back()->withNotify($notify);
    //     }
    // }

    // private function generateIntervals($startTime, $endTime)
    // {
    //     $intervals = [];

    //     // Parse the start and end times
    //     $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
    //     $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);

    //     // Generate intervals
    //     while ($start->lt($end)) {
    //         $intervalStart = $start->copy(); // Interval start time
    //         $intervalEnd = $start->addMinutes(10)->copy(); // Add 10 minutes for the interval end time

    //         // Ensure the end time does not exceed the provided end time
    //         if ($intervalEnd->gt($end)) {
    //             $intervalEnd = $end->copy();
    //         }

    //         $intervals[] = [
    //             'start' => $intervalStart->format('H:i'),
    //             'end' => $intervalEnd->format('H:i'),
    //         ];

    //         // Move to the next interval start time
    //         $start = $intervalEnd->copy();
    //     }

    //     return $intervals;
    // }

    private function generateIntervals($startTime, $endTime)
    {
        $intervals = [];

        $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);

        // ✅ Handle overnight case (end is next day)
        if ($end->lte($start)) {
            $end->addDay();
        }

        while ($start->lt($end)) {

            $intervalStart = $start->copy();
            $intervalEnd = $start->copy()->addMinutes(10);

            if ($intervalEnd->gt($end)) {
                $intervalEnd = $end->copy();
            }

            $intervals[] = [
                'start' => $intervalStart->format('Y-m-d H:i'),
                'end'   => $intervalEnd->format('Y-m-d H:i'),
            ];

            $start = $intervalEnd;
        }

        return $intervals;
    }

    public function sendtimesheetworkoffer(Request $request)
    {
        $data = Sellercounteroffer::where('id', $request->dataa_id)->first();
        $datawith = Sellercounterofferdata::where('counter_offer_id', $data->id)->first();
        $accepted = Acceptedoffer::where('id', $request->accepted_id)->first();
        $ts = new Jobworkdeliveryhourlyoffer;
        $ts->seller_id = $accepted->seller_id;
        $ts->buyer_id = $accepted->buyer_id;
        $ts->offer_id = $accepted->offer_id;
        $ts->job_id = $accepted->job_id;
        $ts->status = '2';
        $ts->data_id = $datawith->id;
        $ts->date_start = $request->date_start;
        $ts->date_end = $request->date_end;
        $ts->time = $request->time;
        $ts->minutes = $request->minutes;
        $ts->total_cost = $datawith->amount * $request->time.'.'.$request->minutes;
        $ts->per_hour_rate = $datawith->amount;
        $ts->title = $request->title;
        $ts->details = $request->details;
        $workFile = imagePath()['workFile']['path'];
        if ($request->hasFile('file')) {
            $file = $request->file;
            try {
                $filename = uploadFile($file, $workFile);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Zip could not be uploaded.'];

                return back()->withNotify($notify);
            }
            $ts->file = $filename;
        }
        $ts->save();

        $noti = new Notification;
        $noti->reciever_id = $accepted->buyer_id;
        $noti->sender_id = $accepted->seller_id;
        $noti->type = 'seller_send_worksheet_bid';
        $noti->job_id = $accepted->job_id;
        $noti->save();

        sendRealTimeNotification($accepted->buyer_id, 'seller_send_worksheet_bid');

        // send email
        $getJobName = DB::table('jobs')->where('id', $accepted->job_id)->first();
        $sender = DB::table('users')->where('id', $accepted->seller_id)->first();
        $reciever = DB::table('users')->where('id', $accepted->buyer_id)->first();
        // echo $getJobName->id;exit;
        require base_path('vendor/autoload.php');
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
            // Recipients
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
            $work = $request->title;
            $type = 'seller_send_work';
            $mail->isHTML(true);
            $mail->Subject = ' Job Bid Work .';
            $contentBody = view('Emails/Job/bidworking', compact('sender', 'reciever', 'getJobName', 'type', 'work'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Timesheet successfully sent'];

        return redirect()->back()->withNotify($notify);
    }

    public function usertimesheetbidfileDownload($id)
    {
        $work = Jobworkdeliveryhourlybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function usertimesheetbidresponsefileDownload($id)
    {
        $work = Jobworkdeliveryhourlybid::where('id', decrypt($id))->firstOrFail();
        $file = $work->response_file;
        $path = imagePath()['workFile']['path'];
        $full_path = $path.'/'.$file;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: softwareFile; filename="'.$file.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }

    public function userbidviewdispute(Request $request, $type, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $activeTab = $request->get('tab', 'case'); // Default tab
        $user = Auth::user();
        $pageTitle = 'View Dispute';
        $emptyMessage = 'No data found';
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
                return view('templates.basic.user.seller.dispute_case', compact(
                    'disputedetail',
                    'booking',
                    'settledDispute',
                    'type'
                ));
            }

            if ($request->has('tab') && $request->tab === 'negotiation') {
                return view('templates.basic.user.seller.dispute_negotiation', compact(
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
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.seller.dispute')],
            ['title' => 'Offered Jobs', 'url' => url('user/seller/job/list/ajax/offers')],
            ['title' => 'View Dispute Identifier'],
        ];

        // ✅ Full Page Load
        return view($this->activeTemplate.'user.seller.view_dispute', compact(
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

    public function acceptworkbiddisputeoffer(Request $request)
    {
        $type = $request->input('type');
        $workId = $request->input('work_id');

        if ($type === 'job') {
            // For Job Dispute
            $disputedata = Jobdisputebiddata::findOrFail($workId);
            $disputedata->status = '1';
            $disputedata->save();

            $dispute = Jobdisputebid::findOrFail($disputedata->dispute_id);
            $dispute->status = '1';
            $dispute->save();

            // Notify Both Parties
            sendJobEmailWithNotification(
                $dispute->seller_id,
                $dispute->buyer_id,
                $dispute->job_id,
                'SELLER_ACCEPT_DISPUTE',
                jobDisputeBidId: $dispute->id,
                is_job_service: 'job',
            );
        } elseif ($type === 'service') {
            // For Service Dispute
            $disputedata = Servicedisputeoffers::findOrFail($workId);
            $disputedata->status = '1';
            $disputedata->save();

            $dispute = Servicedisputedata::findOrFail($disputedata->dispute_id);
            $dispute->status = '1';
            $dispute->save();

            // Notify Both Parties
            // You can create a similar notification function for service if not exists
            // sendServiceEmailWithNotification(
            //     $dispute->seller_id,
            //     $dispute->buyer_id,
            //     $dispute->service_id,
            //     'SELLER_ACCEPT_DISPUTE',
            //     serviceDisputeId: $dispute->id
            // );

        } else {
            abort(404, 'Invalid dispute type.');
        }

        $notify[] = ['success', 'Dispute offer has been accepted'];

        return back()->withNotify($notify);
    }

    public function loadDisputeCaseIdentification(Request $request, $jobDisputeId)
    {
        $pageTitle = 'Case Identification';
        $disputedetail = Jobdisputebid::where('id', decrypt($jobDisputeId))->first();
        $booking = Booking::where('job_id', $disputedetail->job_id)->first();
        $settledDispute = Jobdisputebiddata::where('dispute_id', $disputedetail->id)->where('status', 1)->first();
        if ($request->ajax()) {
            return view('templates.basic.user.seller.dispute_case', compact('disputedetail', 'booking', 'settledDispute'));
        }

        return view($this->activeTemplate.'user.seller.job_vacancy_bid-view-dispute', compact('pageTitle', 'disputedetail', 'booking', 'jobDisputeId', 'settledDispute'));
    }

    public function loadDisputeNegotiation(Request $request, $jobDisputeId)
    {
        // Decrypt the jobDisputeId
        $decryptedId = decrypt($jobDisputeId);
        $pageTitle = 'Negotiation';
        // Fetch data from job_dispute_bid table (assuming it has multiple related records in job_dispute_bid_data)
        $disputedetail = JobDisputeBid::where('id', $decryptedId)->with('bidData')->first();
        $booking = Booking::where('job_id', $disputedetail->job_id)->first();
        // Prepare data array for the view
        $data = [
            'negotiations' => $disputedetail->bidData,  // get related JobDisputeBidData entries
        ];
        $settledDispute = Jobdisputebiddata::where('dispute_id', $disputedetail->id)->where('status', 1)->first();
        if ($request->ajax()) {
            return view('templates.basic.user.seller.dispute_negotiation', compact('data', 'booking', 'disputedetail', 'settledDispute'));
        }

        return view($this->activeTemplate.'user.seller.job_vacancy_bid-view-dispute-nego', compact('pageTitle', 'data', 'disputedetail', 'booking', 'jobDisputeId', 'settledDispute'));
    }

    public function rejectworkbiddisputeoffer(Request $request)
    {
        $disputedata = Jobdisputebiddata::findOrFail($request->work_id);
        $disputedata->status = '3';
        $disputedata->show_send = '1';
        $disputedata->save();

        $dispute = Jobdisputebid::findOrFail($disputedata->dispute_id);

        sendJobEmailWithNotification($dispute->seller_id, $dispute->buyer_id, $dispute->job_id, 'SELLER_DECLINE_DISPUTE', jobDisputeBidId: $dispute->id, is_job_service: 'job');
        $notify[] = ['success', 'Dispute offer has been declined'];

        return back()->withNotify($notify);
    }

    public function jobworkbiddisputeoffer(Request $request)
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
            // Initialize an empty array to hold the errors
            $errors = [];

            // Loop through validation errors and structure them by field name
            foreach ($validator->errors()->messages() as $field => $messages) {
                $errors[$field] = $messages; // Field name as key, messages as value
            }

            // Return the errors as a JSON response
            return response()->json([
                'status' => 'error',
                'errors' => $errors, // Return structured validation errors
            ], 422);
        }

        // Fetch the dispute
        $dispute = Jobdisputebid::where('id', $request->dispute_id)->first();

        // Validate that the total amount matches the dispute amount
        $dispute_amount = $dispute->dispute_amount;
        $buyer_amount = $request->buyer_amount;
        $seller_amount = $request->seller_amount;
        $amountequal = $buyer_amount + $seller_amount;
        if ($amountequal != $dispute_amount) {
            // Return a specific error for amount mismatch
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'amount' => ['Offered amount must be equal to the dispute amount'],
                ],
            ], 422);
        }

        // Create new Jobdisputebiddata record
        $disputedata = new Jobdisputebiddata;
        $disputedata->seller_amount = $request->seller_amount;
        $disputedata->seller_message = $request->message;
        $disputedata->buyer_amount = $request->buyer_amount;
        $disputedata->dispute_id = $dispute->id;
        $disputedata->status = '0';
        $disputedata->send_by = 'seller';
        $disputedata->show_send = '0';
        $disputedata->save();

        sendJobEmailWithNotification($dispute->seller_id, $dispute->buyer_id, $dispute->job_id, 'SEND_DISPUTE_MSG_TO_BUYER', jobDisputeBidId: $dispute->id);

        // If everything went well, return success
        return response()->json([
            'status' => 'success',
            'message' => 'Dispute offer has been sent successfully!',
        ]);
    }

    public function jobworkbiddisputesendtoadmin(Request $request)
    {
        $dispute = Jobdisputebid::findOrFail($request->dispute_id);
        $dispute->status = '2';
        $dispute->sent_to_admin_by = 'Seller';
        $dispute->save();

        $disputeDataRecords = Jobdisputebiddata::where('dispute_id', $dispute->id)
            ->where('status', '0')  // Only select records with status 0
            ->get();

        foreach ($disputeDataRecords as $disputeData) {
            $disputeData->status = '2';
            $disputeData->save();
        }
        // send email
        $getJobName = DB::table('jobs')->where('id', $dispute->job_id)->first();
        $sender = DB::table('users')->where('id', $dispute->seller_id)->first();
        $reciever = DB::table('users')->where('id', $dispute->buyer_id)->first();
        // echo $getJobName->id;exit;
        // require base_path("vendor/autoload.php");
        // $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        // try {

        //     // Email server settings
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->Host = env('MAIL_HOST');
        //     $mail->SMTPAuth = true;
        //     $mail->Username = env('MAIL_USERNAME');
        //     $mail->Password = env('MAIL_PASSWORD');
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //     $mail->Port = env('MAIL_PORT');
        //     //Recipients
        //     $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        //     $mail->addAddress($reciever->email);
        //     // $mail->addCC($request->emailCc);
        //     // $mail->addBCC($request->emailBcc);

        //     $mail->addReplyTo(env('MAIL_REPLY_ADDRESS'), env('MAIL_FROM_NAME'));

        //     if (isset($_FILES['emailAttachments'])) {
        //         for ($i = 0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
        //             $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
        //         }
        //     }
        //     $type = 'seller_dispute_admin_job';
        //     $mail->isHTML(true);
        //     $mail->Subject = '  Dispute Send To Admin .';
        //     $contentBody = view('Emails/Job/dispute_seller', compact('sender', 'reciever', 'getJobName', 'type'))->render();
        //     // $mail->Body    = $user->ev_code;
        //     $mail->Body = $contentBody;

        //     // $mail->AltBody = plain text version of email body;

        //     if (!$mail->send()) {
        //         $notify[] = ['error', 'Email not sent.'];
        //         return redirect()->back()->withNotify($notify);
        //     } else {
        //         $notify[] = ['success', 'Email has been sent'];
        //         return redirect()->back()->withNotify($notify);
        //     }
        // } catch (Exception $e) {
        //     \Log::info("Dispute Send to Admin ".$e->getMessage());
        //     $notify[] = ['error', 'Message could not be sent.'];
        //     return redirect()->back()->withNotify($notify);
        // }
        // send email

        $notify[] = ['success', 'Dispute has been sent to admin'];

        return back()->withNotify($notify);
    }

    public function ratingreplyservice(Request $request)
    {
        $rev = ReviewRating::findOrFail($request->review_id);
        $rev->reply = $request->reply;
        $rev->save();
        $notify[] = ['success', 'Review replied successfully'];
        sendJobEmailWithNotification($rev?->pro_id, $rev->user_id, $rev->data_id, 'REPLY_ON_JOB_REVIEW', false, $rev->data_id);

        return redirect()->back()->withNotify($notify);
    }

    public function addedtocompletedseller(Request $request)
    {

        $user = Auth::user();
        $job = Userbiding::where('id', $request->id)->firstOrFail();
        $job->is_completed_seller = 1;
        $job->save();
        $notify[] = ['success', 'Job has been added to completed.'];

        return back()->withNotify($notify);
    }

    public function addedtocompletedtwoseller(Request $request)
    {

        $user = Auth::user();
        $job = UserOffer::where('id', $request->id)->firstOrFail();
        $job->is_completed_seller = 1;
        $job->save();
        $notify[] = ['success', 'Job has been added to completed.'];

        return back()->withNotify($notify);
    }

    public function viewdisputeoffer(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $pageTitle = 'Service booking dispute detail';
        $emptyMessage = 'No data found';

        $dispute_data = Servicedisputedata::where('id', decrypt($id))->first();
        $dispute_data_count = Servicedisputedata::where('id', decrypt($id))->count();
        $show_send = Servicedisputeoffers::where('dispute_id', $dispute_data->id)->where('show_send', 0)->count();
        $dispute_offers = Servicedisputeoffers::where('dispute_id', $dispute_data->id)->get();
        $booking = Booking::where('id', $dispute_data->booking_id)->whereHas('service', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        $allworks = WorkDelivery::where('booking_id', $booking->id)->where('service_id', $booking->service_id)->get();

        return view($this->activeTemplate.'user.seller.service_booking_view_dispute', compact('pageTitle', 'emptyMessage', 'dispute_data', 'dispute_offers', 'booking', 'dispute_data_count', 'allworks', 'show_send'));
    }

    public function acceptdisputeoffer(Request $request)
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
        $dis = DB::table('service_dispute_data')->where('id', $disputedata->dispute_id)->first();
        $data = DB::table('bookings')->where('id', $dis->booking_id)->first();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $data->user_id)->first();
        $service_user = DB::table('users')->where('id', $service_single->user_id)->first();

        require base_path('vendor/autoload.php');
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
            // Recipients
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
            $type = 'dispute_offer_seller_accept';
            $mail->isHTML(true);
            $mail->Subject = ' Dispute Offer Accepted.';
            $contentBody = view('Emails/Service/servicebookapprove', compact('service_single', 'service_user', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute offer has been accepted'];

        return back()->withNotify($notify);
    }

    public function declinedisputeoffer(Request $request)
    {

        $disputedata = Servicedisputeoffers::findOrFail($request->work_id);
        $disputedata->status = '3';
        $disputedata->show_send = '1';
        $disputedata->save();

        $dis = DB::table('service_dispute_data')->where('id', $disputedata->dispute_id)->first();
        $data = DB::table('bookings')->where('id', $dis->booking_id)->first();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $data->user_id)->first();
        $service_user = DB::table('users')->where('id', $service_single->user_id)->first();

        require base_path('vendor/autoload.php');
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
            // Recipients
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
            $type = 'dispute_offer_seller_decline';
            $mail->isHTML(true);
            $mail->Subject = 'Dispute Offer declined.';
            $contentBody = view('Emails/Service/servicebookapprove', compact('service_single', 'service_user', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute offer has been declined'];

        return back()->withNotify($notify);
    }

    public function newdisputeoffer(Request $request)
    {

        $disputedata = new Servicedisputeoffers;
        $disputedata->dispute_id = $request->dispute_id;
        $disputedata->seller_amount = $request->seller_amount;
        $disputedata->buyer_amount = $request->buyer_amount;
        $disputedata->message = $request->message;
        $disputedata->status = '0';
        $disputedata->show_send = '0';
        $disputedata->send_by = 'seller';
        $disputedata->save();

        $dis = DB::table('service_dispute_data')->where('id', $request->dispute_id)->first();
        $data = DB::table('bookings')->where('id', $dis->booking_id)->first();

        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $data->user_id)->first();
        $service_user = DB::table('users')->where('id', $service_single->user_id)->first();

        require base_path('vendor/autoload.php');
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
            // Recipients
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
            $type = 'new_offer_disputr_seller';
            $mail->isHTML(true);
            $mail->Subject = 'New Dispute Offer.';
            $contentBody = view('Emails/Service/servicebookapprove', compact('service_single', 'service_user', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email

        $notify[] = ['success', 'Dispute counter offer has been sent'];

        return back()->withNotify($notify);
    }

    public function sendadmindisputeoffer(Request $request)
    {
        // $disputedata=Servicedisputeoffers::findOrFail($request->dispute_id);
        //      $disputedata->status = '2';
        //         $disputedata->save();
        $dispute = Servicedisputedata::findOrFail($request->dispute_id);
        $dispute->status = '2';
        $dispute->save();

        $data = Booking::findOrFail($dispute->booking_id);
        $data->status = '7';
        $data->working_status = '6';
        $data->admin_reason = $request->reason;
        $data->save();
        // send email
        $service_single = DB::table('services')->where('id', $data->service_id)->first();
        $emailuser = DB::table('users')->where('id', $data->user_id)->first();
        $service_user = DB::table('users')->where('id', $service_single->user_id)->first();

        require base_path('vendor/autoload.php');
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
            // Recipients
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
            $contentBody = view('Emails/Service/servicebookapprove', compact('service_single', 'service_user', 'order_number', 'type'))->render();
            // $mail->Body    = $user->ev_code;
            $mail->Body = $contentBody;

            // $mail->AltBody = plain text version of email body;

            if (! $mail->send()) {
                $notify[] = ['error', 'Email not sent.'];

                return redirect()->back()->withNotify($notify);
            } else {
                $notify[] = ['success', 'Email has been sent'];

                return redirect()->back()->withNotify($notify);
            }
        } catch (Exception $e) {
            $notify[] = ['error', 'Message could not be sent.'];

            return redirect()->back()->withNotify($notify);
        }
        // send email
        $notify[] = ['success', 'Dispute has been sent to admin'];

        return back()->withNotify($notify);
    }

    public function exportTransactions()
    {
        $response = Excel::download(new TransactionExport, 'transactions_'.time().'.xlsx');
        $response->headers->set('X-Test', 'value');

        return $response;
    }
}
