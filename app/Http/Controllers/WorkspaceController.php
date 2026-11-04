<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use App\Models\Booking;
use App\Models\Acceptedbid;
use App\Models\Transaction;
use App\Models\ReviewRating;
use Illuminate\Http\Request;
use App\Models\Buyercounteroffer;
use App\Services\WorkspaceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Buyercounterofferdata;
use Illuminate\Support\Facades\Crypt;
use App\Models\Jobworkdeliveryhourlybid;
use App\Models\Employermembershiptransactions;

class WorkspaceController extends Controller
{
    protected $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    public function overview(Request $request, $encryptedId)
    {
        $user = Auth::user();
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $jobId = Crypt::decrypt($encryptedId);
        $pageTitle = "Overview";
        // Fetch the user bidding data along with related models
        $acceptedBid = Acceptedbid::with(['job', 'seller', 'userBiding'])->where('job_id', $jobId)->where('buyer_id', $user->id)->first();
        if (!$acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        [$totalBudget, $pendingPayment, $paidAmount, $bonusAmount, $remainingAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('status', 1)->first();
        $job_type = $buyerCounterOffer->type;
        $job = $acceptedBid->job;
        $budgetType = $job->budgettype;
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $isCompleted = $job->show_completed;

        $startDate = Carbon::parse($acceptedBid->created_at)->format('Y-m-d');
        $status = $job->booking->working_status_text ?? 'N/A';
        $pageTitle = "Workroom";
        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Offered Jobs', 'url' => '/user/buyer/hire/offered-jobs'],
        //     ['title' => 'Contract Overview', 'url' => ''],
        // ];

        $encUserBidingId = encrypt($acceptedBid->bid_id);
        $buyerId = $acceptedBid->buyer_id;
        $sellerId = $acceptedBid->seller_id;
        $encJobId = encrypt($job->id);
        $seller =  $acceptedBid->seller;

        $emp_mem_trans = Employermembershiptransactions::with('employerMembership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = 0;
        if(!is_null($emp_mem_trans))
            $platform_fee = (float)$emp_mem_trans?->employerMembership?->fee;

        if ($request->ajax()) {
            return view('workspace.overview', compact('job_type','platform_fee', 'totalBudget', 'startDate', 'status', 'pendingPayment', 'paidAmount', 'bonusAmount', 'remainingAmount'));
        }


        $contract_details = [];
        $payment_details = [];
        $req_type = null;
        if ($job_type == "byproject")
        {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->latest()->first();
            $req_type = $buyerCounterOfferData->req_type;
            if(is_null($req_type))
            {
                $contract_details = [
                    'Buyer' => Auth::user()->fullname,
                    'Seller' => User::find($sellerId)->fullname,
                    'Project Value' => '$'.$buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Delivery Date' => Carbon::parse($buyerCounterOfferData->due_date)->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status)
                ];
                $payment_details = [
                    'receive_payment' => $totalBudget,
                    'total_budget'=>$totalBudget,
                    'total_accepted_bid_amount'=>number_format($totalBudget,2)
                ];
            }else {
                $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->first();
                $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status',1)->get(['id','title','amount','due_date']);
                $contract_details = [
                    'Buyer' => Auth::user()->fullname,
                    'Seller' => User::find($sellerId)->fullname,
                    'Budget' => $job->amount,
                    'Bid Amount'=>$buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status)
                ];
                $payment_details = [
                    'milestones'=>$milestones->toArray(),
                    'receive_payment' => $paidAmount,
                    'total_budget'=>$totalBudget,
                    'total_accepted_bid_amount'=>number_format($totalBudget,2)
                ];
            }
        }
        else if ($job_type == "hourly")
        {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->first();
            $Jobworkdeliveryhourlybid = Jobworkdeliveryhourlybid::where('job_id', $job->id)
                ->where('status',1)
                ->orderBy('date_start','asc')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->date_start)->format('o-W');
                });

            $contract_details = [
                'Buyer' => Auth::user()->fullname,
                'Seller' => User::find($sellerId)->fullname,
                'Hourly Rate' => '$'.number_format($buyerCounterOfferData->amount,2),
                'Weekly Limit' => $buyerCounterOfferData->days,
                'Estimated Weekly Hours' => $buyerCounterOfferData->days,
                'Billing Cycle' => "Weekly",
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status)
            ];

            $payment_details = [];
            $weekIndex = 1;

            foreach ($Jobworkdeliveryhourlybid as $weeklyBids) {

                $start_date = Carbon::parse($weeklyBids->first()->date_start);
                $end_date   = Carbon::parse($weeklyBids->last()->date_end);

                $start_formatted_date = $start_date->format('d M Y');
                $end_formatted_date   = $end_date->format('d M Y');

                $totalMinutes = 0;
                $totalCost = 0;

                foreach ($weeklyBids as $bid) {
                    $totalMinutes += ($bid->time * 60) + $bid->minutes;
                    $totalCost += $bid->total_cost ?? 0;
                }

                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;

                $payment_details[] = [
                    'Week' => "Week $weekIndex ( $start_formatted_date - $end_formatted_date )",
                    'Hours' => "$hours hour - $minutes minutes",
                    'Amount' => round($totalCost,2),
                    'raw_hours' => $hours,
                    'raw_minutes' => $minutes
                ];

                $weekIndex++;
            }

            $payment_details['weekly_hours'] = $job->weekly_hours;
            $payment_details['hourly_amount'] = (int)$totalBudget;
        }
        else if ($job_type == "bymilestone"){
            // $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->whereNull('req_type')->get();
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status',1)->where('status',1)->whereNull('req_type')->get();
            // $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status',1)->get(['id','title','amount','due_date']);
            $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status',1)->where('status',1)->get(['id','title','amount','due_date']);

            $buyerCounterOfferDataNullReqType = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status',1)->where('status',1)->get();

            //is waja sy status 1 nahi leye hamein sara k sara milestones chaheye workroom me show krny k leye
            $contract_details = [
                'Buyer' => Auth::user()->fullname,
                'Seller' => User::find($sellerId)->fullname,
                'Budget' => '$'.$job->amount,
                'Bid Amount'=>'$'.number_format($buyerCounterOfferData->sum('amount'),2),
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status)
            ];
            $payment_details = [
                'milestones'=>$milestones->toArray(),
                'receive_payment' => $paidAmount,
                'total_budget'=>$totalBudget,
                'total_accepted_bid_amount'=>number_format($buyerCounterOfferDataNullReqType->sum('amount'),2)
            ];
        }
        return view('templates.basic.user.buyer.workroom_list', compact(
            'seller',
            'status',
            'pageTitle',
            'job_type',
            'jobTitle',
            'jobDescription',
            'bonusAmount',
            'startDate',
            'totalBudget',
            'pendingPayment',
            'paidAmount',
            'remainingAmount',
            'encUserBidingId',
            'buyerId',
            'sellerId',
            'isCompleted',
            'encJobId',
            'contract_details',
            'payment_details',
            'req_type',
            'platform_fee'
        ));
    }

    protected function jobStatus($status)
    {
        $status = match ($status) {
            0 => 'Pending',
            1 => 'Accepted',
            2 => 'Rejected',
        };
        return $status;
    }

    // private function calculateBudgetAndPayments($acceptedBid)
    // {
    //     $totalBudget = 0;
    //     $pendingPayment = 0;
    //     $paidAmount = 0;
    //     $remainingAmount = 0;
    //     $bonusAmount = 0;
    //     $nonBonusPaidAmount = 0;
    //     $commissionAmount = 0;
    //     $days = 0;
    //     $budget_type = Buyercounteroffer::where('job_id',(int)$acceptedBid->job_id)->value('type');
    //     $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->whereNotNull('req_type')->get();
    //     if ($budget_type == "hourly") {
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->first();
    //         $totalBudget = $offerData->amount;
    //         $days = $offerData->days;
    //     } else if($budget_type=='byproject'){
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->latest()->get();
    //         if($offerData[0]->req_type == null) {
    //             $totalBudget = $offerData->sum('amount');
    //             $days = 0;
    //         }else {
    //             $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->whereNull('req_type')->first();
    //             $totalBudget = $offerData->amount;
    //             $days = $offerData->days;
    //         }
    //     }else if($budget_type=='bymilestone'){
    //         // $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('requested_milestone_status',1)->get(); // q k hamein pory job ki total cost chaheye jo workroom me
    //         //show hoga na k wo amount to approved hai
    //         $totalBudget = $offerData->sum('amount');
    //         $days = $offerData->sum('days');

    //     }
    //     $booking = $acceptedBid->job->booking;

    //     if ($booking) {
    //         // Fetch transactions related to the booking
    //         $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->where('status',1)->get();
    //         // continue from herebilawal
    //         // Calculate paid amount
    //         if ($transactions->isNotEmpty()) {
    //             $regularTransactions = $transactions->filter(function ($transaction) {
    //                 return strpos($transaction->details, 'Platform commission') === false;
    //             });

    //             $commissionTransactions = $transactions->filter(function ($transaction) {
    //                 return strpos($transaction->details, 'Platform commission') !== false;
    //             });

    //             $paidAmount = $transactions->sum('amount'); // Sum transaction amounts

    //             // Calculate commission amount
    //             $commissionAmount = $commissionTransactions->sum('amount');

    //             //new code
    //             $bonusAmount = $transactions->where('is_bonus', 1)->sum('amount');
    //             $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount') - $transactions->sum('charge');
    //         }
    //     }

    //     // $pendingPayment = max(0, $totalBudget - $paidAmount);
    //     // $remainingAmount = max(0, $totalBudget - $paidAmount);
    //     if ($budget_type == "hourly") {
    //         //new code added
    //        $transactions = $booking->transactions()
    //             ->where('user_id', Auth::id())
    //             ->where('status',1)
    //             ->where('details', 'NOT LIKE', '%platform commission%')
    //             ->get();

    //         $bonusAmount = $transactions->where('is_bonus', 1)->sum('amount');
    //         // $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount') - $transactions->sum('charge');
    //         $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount') + $transactions->sum('charge');

    //         //old code
    //         $pendingPayment = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //         // dd($pendingPayment,$remainingAmount);
    //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //     } else if($budget_type=='byproject') {
    //         $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
    //         $totalBudget = $buyerCounterOfferData->sum('amount');
    //         $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //         // if(count($buyerCounterOfferData)>0) {
    //         //     if($buyerCounterOfferData[0]->req_type == null) {
    //         //         $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         //         $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //         //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //         //     }else {
    //         //         $paid_amount = $buyerCounterOfferData->sum('amount');
    //         //         $t_budget = $totalBudget-$paid_amount;
    //         //         $pendingPayment = max(0, $t_budget - $nonBonusPaidAmount);
    //         //         $remainingAmount = max(0, $t_budget - $nonBonusPaidAmount);
    //         //         return [$totalBudget, $pendingPayment, $paid_amount, $bonusAmount, $remainingAmount];
    //         //     }
    //         // }else {
    //         //     $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         //     $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //         //     return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //         // }
    //     }else if($budget_type == 'bymilestone') {
    //         $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
    //         $totalBudget = $buyerCounterOfferData->sum('amount');
    //         $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //     }
    // }

    private function calculateBudgetAndPayments($acceptedBid)
    {
        $totalBudget = 0;
        $pendingPayment = 0;
        $remainingAmount = 0;
        $bonusAmount = 0;
        $nonBonusPaidAmount = 0;
        $nonBonusPaidAmount1 = 0;
        $commissionAmount = 0;
        $days = 0;
        $budget_type = Buyercounteroffer::where('job_id', (int) $acceptedBid->job_id)->value('type');

        if ($budget_type == 'hourly') {
            $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->first();
            $totalBudget = $offerData->amount;
            $days = $offerData->days;
        } elseif ($budget_type == 'byproject') {
            $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->latest()->get();
            if ($offerData && count($offerData) > 0 && $offerData[0]->req_type == null) {
                $totalBudget = $offerData->sum('amount');
                $days = 0;
            } else {
                $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->whereNull('req_type')->first();
                $totalBudget = $offerData->amount;
                $days = $offerData->days;
            }
        } elseif ($budget_type == 'bymilestone') {
            $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('requested_milestone_status', 1)->get();
            $totalBudget = $offerData->sum('amount');
            $days = $offerData->sum('days');
        }

        $booking = $acceptedBid->job->booking;

        if ($booking) {
            $transactions = $booking->transactions()
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->get();

            if ($transactions->isNotEmpty()) {
                $regularTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') === false;
                });

                $commissionTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') !== false;
                });

                $platformCommissionTransactions = $transactions->filter(function ($transaction) {
                    return str_contains($transaction->details, 'Platform Fee') ||
                        str_contains($transaction->details, 'Platform commission');
                });

                $commissionAmount = $platformCommissionTransactions->sum('amount') + $platformCommissionTransactions->sum('charge');

                // 27june db saving 5.2 having .2 is platform fee also create new entry of .2 for platform fee
                $bonusAmount = $regularTransactions->where('is_bonus', 1)->where('status', 1)->sum('amount') - $regularTransactions->where('status', 1)->where('is_bonus', 1)->sum('charge');
                $nonBonusPaidAmount = $regularTransactions->where('is_bonus', 0)->where('status', 1)->sum('amount') - $regularTransactions->where('status', 1)->where('is_bonus', 0)->sum('charge');
                $nonBonusPaidAmount1 = $regularTransactions->where('is_bonus', 0)->where('status', 1)->sum('amount');
            }
        }

        if ($budget_type == 'hourly') {
            $pendingPayment = max(0, $totalBudget * $days - $nonBonusPaidAmount);
            $remainingAmount = max(0, $totalBudget * $days - $nonBonusPaidAmount);
        } elseif ($budget_type == 'byproject') {
            $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
            $totalBudget = $buyerCounterOfferData->sum('amount');
            $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
            $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
        } elseif ($budget_type == 'bymilestone') {
            $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
            $totalBudget = $buyerCounterOfferData->sum('amount');
            $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
            $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
        }

        return [$totalBudget, $pendingPayment, $nonBonusPaidAmount1, $bonusAmount, $remainingAmount, $commissionAmount];
    }

    public function timesheet(Request $request, $userBidingId)
    {
        // Call the getAcceptedMilestones method to get both milestones and type
        $result = $this->workspaceService->getAcceptedMilestones($userBidingId);

        // Extract milestones and type from the result
        $milestones = $result['milestones'];
        $type = $result['type'];

        // Pass both milestones and type to the view
        return view('workspace.timesheet', compact('milestones', 'type'));
    }

    public function workroomTimesheet(Request $request, $encryptedId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $jobId = Crypt::decrypt($encryptedId);
        $user = Auth::user();
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('buyer_id', $user->id)->first();
        if (!$acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller; // Using the relationship
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('status', 1)->first();
        $job_type = $buyerCounterOffer->type;
        $buyerCounterOfferDataId = $buyerCounterOffer->offerdata->id;
        $encBuyerCounterOfferDataId = encrypt($buyerCounterOfferDataId);
        $allowManualTime = $buyerCounterOffer->allow_manual_time;
        $isManulaLogAccepted = $buyerCounterOffer->is_manual_log_accepted;
        $requestDescription = $buyerCounterOffer->request_description;
        $date = $request->query('date', now()->format('Y-m-d'));
        $job = $acceptedBid->job;

        $jobWorkBids = Jobworkdeliveryhourlybid::whereIn('status', [0, 1, 6])->whereHas('intervals', function ($query) {
            $query->whereIn('status', [1, 5, 6, 3]); // Ensure at least one interval has status 1, 2, or 3
        })->with(['intervals' => function ($query) {
            $query->whereIn('status', [1, 5, 6, 3]); // Load only intervals with status 1, 2, or 3
        }])->where('data_id', $buyerCounterOfferDataId)->whereDate('date_start', $date)->get();
        $encWorkId = encrypt(optional($jobWorkBids->first())->id);
        foreach ($jobWorkBids as $bid) {
            $hourlyBlocks = [];

            foreach ($bid->intervals as $interval) {
                // Calculate the dispute window for this interval
                $submittedDate = $interval->interval_start_time; // Use interval start time
                $startOfDisputeWindow = \Carbon\Carbon::parse($submittedDate)->next(\Carbon\Carbon::MONDAY);
                $endOfDisputeWindow = $startOfDisputeWindow->copy()->addDays(4); // Friday of the same week

                // Check if the current time falls within the dispute window
                $canDispute = now()->between($startOfDisputeWindow, $endOfDisputeWindow);

                // Add this information to the interval for the frontend
                $interval->can_dispute = $canDispute;
            }

            // Group intervals by hour
            $intervalsGroupedByHour = $bid->intervals->groupBy(function ($interval) {
                $intervalStartTime = \Carbon\Carbon::createFromFormat('H:i:s', $interval->interval_start_time);

                // Ensure correct grouping for :00 intervals
                return $intervalStartTime->format('g A');
            });
            // Process each hour
            foreach ($intervalsGroupedByHour as $hour => $intervalsForHour) {
                $hourStart = \Carbon\Carbon::createFromFormat('g A', $hour);
                $intervals = [];
                $isManual = $intervalsForHour->first()->is_manual;

                // Adjust the hourly block label to show the previous hour for :00 intervals
                $displayHour = $hourStart->copy();
                // Generate intervals in 10-minute increments
                for ($minute = 0; $minute < 60; $minute += 10) {
                    $intervalTime = $hourStart->copy()->addMinutes($minute);
                    // Stop adding intervals if the minute is 0 and it's the next hour
                    if ($minute == 0 && $intervalTime->hour > $hourStart->hour) {
                        break; // Stop processing if it moves to the next hour
                    }

                    $matchingInterval = $intervalsForHour->first(function ($interval) use ($intervalTime) {
                        $intervalStartTime = \Carbon\Carbon::createFromFormat('H:i:s', $interval->interval_start_time);
                        // Log comparison for debugging
                        Log::info("Comparing Interval Start Time: {$intervalStartTime->format('H:i')} === {$intervalTime->format('H:i')}");

                        // Match intervals by time only (ignoring seconds)
                        return $intervalStartTime->format('H:i') === $intervalTime->format('H:i');
                    });

                    if ($matchingInterval) {
                        // Fetch activity logs related to this interval
                        $activityLogs = $matchingInterval->activityLogs ?: collect();
                        $actualMouseClicks = $activityLogs->sum('mouse_activity');
                        $actualKeystrokes = $activityLogs->sum('keyboard_activity');
                        $filteredActivityLogs = $activityLogs->filter(function ($log) {
                            return $log->keyboard_activity > 0 || $log->mouse_activity > 0;
                        });


                        $activityLevel = $matchingInterval->activity_level;

                        // Add the interval with data
                        $intervals[] = [
                            'id' => $matchingInterval->id,
                            'time' => $intervalTime->format('g:i A'),
                            'screen_shot' => $matchingInterval->screenshot_path,
                            'status' => $matchingInterval->status,
                            'has_data' => true,
                            'activity_level' => $activityLevel,
                            'mouse_clicks' => $actualMouseClicks,
                            'keystrokes' => $actualKeystrokes,
                            'is_manual' => $matchingInterval->is_manual,
                            'cost' => $matchingInterval->cost,
                            'countActivityLogs' => count($filteredActivityLogs),
                            'is_manual' => $interval->is_manual,
                            'status' => $matchingInterval->status,
                            'activities' => $activityLogs->map(function ($log) {
                                return [
                                    'time' => \Carbon\Carbon::parse($log->log_time)->format('h:i A'),
                                    'mouse_clicks' => $log->mouse_activity,
                                    'keystrokes' => $log->keyboard_activity,
                                ];
                            })
                        ];
                    } else {
                        // No data for this interval, mark has_data as false
                        $intervals[] = [
                            'time' => $intervalTime->format('g:i A'),
                            'has_data' => false,
                            'can_dispute' => collect($intervals)->every('can_dispute') && $isManual,
                        ];
                    }
                }

                // Only add this hourly block if it has intervals with data
                if (count($intervals) > 0) {
                    // Display the previous hour for intervals at :00
                    $hourlyBlocks[] = [
                        'hour' => $displayHour->format('g A'), // Show previous hour in the label
                        'intervals' => $intervals,
                    ];
                }
            }
            $bid->hourlyBlocks = $hourlyBlocks;
        }
        $pageTitle = "Timesheet";
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $timezone = $request->query('timezone', 'UTC');
        $isCompleted =  $job->show_completed;
        $buyerId = $buyerCounterOffer->sender_id;
        $sellerId = $buyerCounterOffer->reciever_id;
        $encJobId = encrypt($jobId);


        if ($request->ajax()) {
            return view('workspace.timesheet', compact('jobWorkBids', 'date', 'allowManualTime','isManulaLogAccepted', 'timezone', 'job_type', 'encJobId', 'encBuyerCounterOfferDataId','requestDescription'));
        }

        return view('workspace.full_timesheet', compact('pageTitle', 'encWorkId', 'job_type','isManulaLogAccepted', 'jobWorkBids', 'date', 'allowManualTime', 'jobTitle', 'jobDescription', 'seller', 'isCompleted', 'buyerId', 'sellerId', 'encJobId','requestDescription', 'encBuyerCounterOfferDataId'));
    }

    public function messages(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        return view('workspace.messages');
    }

    public function rating(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $jobId = decrypt($id);
        $user = Auth::user();
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('buyer_id', $user->id)->first();


        if (!$acceptedBid) {
            $notify[] = ['error', 'Bidding not found.'];
            return redirect()->back()->withNotify($notify);
        }
        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller; // Using the relationship
        if (!$seller) {
            $notify[] = ['error', 'Seller not found.'];
            return redirect()->back()->withNotify($notify);
        }
        // Retrieve Userbiding based on `job_id` and `seller_offer_responce`
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('status', 1)->first();
        $rating = ReviewRating::where('data_id', $jobId)->latest()->first();

        $job = $acceptedBid->job; // Access job directly from the relationship
        $encJobId = encrypt($job->id);
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $isCompleted = $job->show_completed;
        $pageTitle = "Rating";
        $buyerId = $job->user_id;
        $sellerId = $seller->id;
        $job_type = $buyerCounterOffer->type;

        if ($request->ajax()) {
            return view('workspace.rating', compact('rating', 'buyerId', 'sellerId', 'seller', 'pageTitle', 'encJobId', 'job_type'));
        }

        return view('workspace.full_rating', compact('rating', 'buyerId', 'sellerId', 'seller', 'pageTitle', 'jobTitle', 'jobDescription', 'isCompleted', 'encJobId', 'job_type'));
    }

    public function invoice(Request $request, $encJobId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Invoice";
        $jobId = decrypt($encJobId);
        $errorMessage = null;

        // Fetch the related booking
        $booking = Booking::where('job_id', $jobId)->first();

        $buyer_counter_offer = Buyercounteroffer::where('job_id', $jobId)->first();

        // Retained variables
        $invoices = [];
        $jobTitle = $booking->job->title ?? 'Unknown Job';
        $jobDescription = $booking->job->description ?? 'No Description';
        $job_type = $booking->booking_type ?? 'Unknown';
        $seller = $booking->seller ?? null;
        $buyer = $booking->buyer ?? null;
        $isCompleted = $booking->job->show_completed ?? false;
        $buyerId = $buyer->id ?? null;
        $sellerId = $seller->id ?? null;
        $encUserBidingId = encrypt($booking->job->buyerCounterOffer->bid_id) ?? null;

        // Fetch transactions related to the booking for the logged-in user
        $transactions = Transaction::where('booking_id', $booking->id)
            ->where('user_id', auth()->id())
            ->where('status',1)
            ->orderBy('created_at','desc')
            // ->where('trx_type','+')
            ->get();

        // Handle no transactions found
        if ($transactions->isEmpty()) {
            $errorMessage = 'No transactions found for the given booking.';
        }

        // Group transactions by invoice number
        $groupedTransactions = $transactions->groupBy(function ($transaction) {
            return $transaction->trx; // Group by transaction number
        });

        $sellerName = $seller->username ?? 'Unknown Seller';
        $orderNumber = $booking->order_number ?? 'N/A';

        // Prepare invoice data
        $invoices = $groupedTransactions->map(function ($transactions, $invoiceNumber) use ($sellerName, $orderNumber) {
            $amount = $transactions->sum('amount');
            $invoiceDate = $transactions->first()->created_at->format('d-m-Y');

            return [
                'invoiceNumber' => $invoiceNumber,
                'sellerName' => $sellerName,
                'orderNumber' => $orderNumber,
                'details' => $transactions->first()->details ?? '',
                'amount' => $amount,
                'invoiceDate' =>  Carbon::parse($invoiceDate)->format('d M, Y'),
                'transactions' => $transactions,
            ];
        });

        if (request()->ajax()) {
            return view('workspace.invoice', compact('invoices', 'errorMessage'));
        }

        return view('workspace.full_invoice', compact('errorMessage', 'invoices', 'pageTitle', 'jobTitle', 'jobDescription', 'job_type', 'seller', 'buyer', 'isCompleted', 'buyerId', 'sellerId', 'encUserBidingId', 'encJobId'));
    }
    public function handleManualLogResponse(Request $request)
    {
        $validated = $request->validate([
            'encBuyerCounterOfferDataId' => 'required|string',
            'response' => 'required|in:accept,reject',
        ]);

        try {
            // Decrypt the encBuyerCounterOfferDataId
            $buyerCounterOfferDataId = decrypt($validated['encBuyerCounterOfferDataId']);

            // Fetch the buyer_counter_offers_data record
            $buyerCounterOfferData = Buyercounterofferdata::find($buyerCounterOfferDataId);

            if (!$buyerCounterOfferData) {
                $notify[] = ['error', 'No eligible record found.'];
                return redirect()->back()->withNotify($notify);
            }

            // Fetch the buyer_counter_offers record
            $buyerCounterOffer = Buyercounteroffer::find($buyerCounterOfferData->counter_offer_id);

            if (!$buyerCounterOffer) {
                $notify[] = ['error', 'No corresponding counter offer found.'];
                return redirect()->back()->withNotify($notify);
            }

            if ($validated['response'] === 'accept') {
                // Update is_manual_log_accepted to 1
                $buyerCounterOffer->is_manual_log_accepted = 1; // Set to 1 for accepted
                $buyerCounterOffer->save();
                sendJobEmailWithNotification($buyerCounterOffer->sender_id, $buyerCounterOffer->reciever_id, $buyerCounterOffer->job_id, 'MANUAL_LOG_ACCEPTED', false, null, null, null, $buyerCounterOffer->id);

                $notify[] = ['success', 'Manual log permission has been accepted.'];
                return redirect()->back()->withNotify($notify);
            } elseif ($validated['response'] === 'reject') {
                // Reject the request
                $buyerCounterOffer->is_manual_log_accepted = 2; // Set to 2 for rejected
                $buyerCounterOffer->request_description = null; // Clear the request description
                $buyerCounterOffer->save();

                sendJobEmailWithNotification($buyerCounterOffer->sender_id, $buyerCounterOffer->reciever_id, $buyerCounterOffer->job_id, 'MANUAL_LOG_REJECTED', false, null, null, null, $buyerCounterOffer->id);

                $notify[] = ['success', 'Manual log request has been rejected.'];
                return redirect()->back()->withNotify($notify);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while processing your response.']);
        }
    }

    public function storeJobBonus(Request $request)
    {
        $request->validate([
            'bonus_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);
        try {
            DB::beginTransaction();

            $job_id = Crypt::decrypt($request->job_id);

            $job = Job::find($job_id);
            $accepted_bid = Acceptedbid::where('job_id', $job_id)->where('buyer_id', Auth::id())->first();

            $sender = Auth::user();
            $seller = User::find( $accepted_bid->seller_id );

            $bonus_amount =  $request->input('bonus_amount');

            $sender_platform_fee_percentage = (getMembershipFee($sender->id));
            $seller_platform_fee_percentage = (getMembershipFee($seller->id));

            $seller_platform_fee_amount = ($bonus_amount * $seller_platform_fee_percentage) / 100;
            $sender_platform_fee_amount = ($bonus_amount * $sender_platform_fee_percentage) / 100;

            $sender->balance -= number_format($bonus_amount + $sender_platform_fee_amount,2);
            $sender->save();

            $seller->balance += number_format($bonus_amount - $seller_platform_fee_amount,2);
            $seller->save();

            $reciever_id = $accepted_bid->seller_id;

            $transaction = new Transaction();
            $transaction->user_id = $sender->id;
            $transaction->booking_id = $accepted_bid?->job?->booking?->id;
            $transaction->to_user = $reciever_id;
            $transaction->amount = number_format($bonus_amount + $sender_platform_fee_amount, 2);
            $transaction->charge = number_format($sender_platform_fee_amount, 2);
            $transaction->post_balance = number_format($sender->balance, 2, '.', '');
            $transaction->trx_type = "-";
            $transaction->trx = getTrx();
            $transaction->status = 1;
            $transaction->details = "Bonus Amount deducted for job: " . $accepted_bid->job->title;
            $transaction->is_bonus = "1";
            $transaction->save();

            $transaction = new Transaction();
            $transaction->user_id = $sender->id;
            $transaction->booking_id = $accepted_bid?->job?->booking?->id;
            $transaction->to_user = $reciever_id;
            $transaction->amount = number_format($sender_platform_fee_amount, 2);
            $transaction->charge = 0;
            $transaction->post_balance = number_format($sender->balance, 2, '.', '');
            $transaction->trx_type = "-";
            $transaction->trx = getTrx();
            $transaction->status = 1;
            $transaction->details = "Platform Fee deducted on Bonus Amount for job: " . $accepted_bid->job->title;
            $transaction->is_bonus = "1";
            $transaction->save();

            $transaction = new Transaction();
            $transaction->user_id = $reciever_id;
            $transaction->booking_id = $accepted_bid?->job?->booking?->id;
            $transaction->to_user = $sender->id;
            $transaction->amount = number_format($bonus_amount - $seller_platform_fee_amount,2);
            $transaction->charge = number_format($seller_platform_fee_amount, 2);
            $transaction->post_balance = number_format($seller->balance, 2, '.', '');
            $transaction->trx_type = "+";
            $transaction->trx = getTrx();
            $transaction->details = "Bonus Amount Received for job: " . $accepted_bid->job->title;
            $transaction->status = 1;
            $transaction->is_bonus = "1";
            $transaction->save();

            $transaction = new Transaction();
            $transaction->user_id = $reciever_id;
            $transaction->booking_id = $accepted_bid?->job?->booking?->id;
            $transaction->to_user = $sender->id;
            $transaction->amount = number_format($seller_platform_fee_amount,2);
            $transaction->charge = 0;
            $transaction->post_balance = number_format($seller->balance, 2, '.', '');
            $transaction->trx_type = "-";
            $transaction->trx = getTrx();
            $transaction->details = "Platform Fee deducted on Bonus Amount for job: " . $accepted_bid->job->title;
            $transaction->status = 1;
            $transaction->is_bonus = "1";
            $transaction->save();

            $notify[] = ['success', 'Bonus has been sent successfully'];
            sendJobEmailWithNotification($job->user_id, $reciever_id, $job->id, 'NOTIFY_SELLER_RECIEVED_BONUS', false, null, null, null, null);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()?->username.' Bonus added for job '.$job?->title, route('admin.job.details',['id'=>$job->id]));

            DB::commit();

            return redirect()->back()->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error storing job bonus: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while processing your request.']);
        }
    }

    public function printBuyerInvoice($invoiceNo)
    {
        $transaction = Transaction::with('user:id,firstname,lastname,address', 'toUser:id,firstname,lastname,address', 'booking:id,qty,job_id', 'booking.job:id,budgettype')
            ->where('trx', $invoiceNo)
            ->first();
        $invoice = [
            'invoiceNumber' => $invoiceNo,
            'date' => now()->toDateString(),
            'seller' => $transaction?->user?->firstname . " " . $transaction?->user?->lastname,
            'seller_address' => optional($transaction->user->address)->address . ' ' .
                optional($transaction->user->address)->city . ' ' .
                optional($transaction->user->address)->state,
            'employeer' => $transaction?->toUser?->firstname . " " . $transaction?->toUser?->lastname,
            'employeer_address' => $transaction?->toUser?->address
                ? $transaction->toUser->address->address . ' ' .
                $transaction->toUser->address->city . ' ' .
                $transaction->toUser->address->state
                : '',
            'amount' => $transaction?->amount ?? 0,
            'qty' => $transaction?->booking?->qty,
            'budgettype' => $transaction?->booking?->job?->budgettype,
            'charge' => $transaction?->charge ?? 0,
            'created_at' => $transaction?->created_at,
            'details'=>$transaction?->details
        ];
        $pdf = \Pdf::loadView('workspace.print', compact('invoice'));

        // Show the PDF in browser (not download)
        return $pdf->stream("invoice_{$invoiceNo}.pdf");
        // return view('seller.workspace.print');
    }
}
