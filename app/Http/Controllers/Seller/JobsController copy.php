<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Acceptedbid;
use App\Models\BidsLog;
use App\Models\Booking;
use App\Models\Buyercounteroffer;
use App\Models\Buyercounterofferdata;
use App\Models\Employermembershiptransactions;
use App\Models\Employerservicescount;
use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancerservicescount;
use App\Models\Job;
use App\Models\JobAttachements;
use App\Models\JobBiding;
use App\Models\Jobskill;
use App\Models\Jobworkdeliverybid;
use App\Models\Jobworkdeliveryhourlybid;
use App\Models\JobworkIntervalEntry;
use App\Models\ReviewRating;
use App\Models\Skill;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Userbiding;
use App\Models\UserMilestone;
use App\Models\UserProfileSkill;
use App\Models\UserTimesheet;
use App\Services\EscrowService;
use App\Services\MembershipService;
use App\Services\UserBidService;
use App\Traits\UbPriceStrategyTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    use UbPriceStrategyTrait;

    //
    private $activeTemplate;

    public function __construct(private MembershipService $membershipService, private UserBidService $userBidService, protected EscrowService $escrowService)
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index()
    {
        $user = Auth::user();
        $pageTitle = 'Job Proposals';
        $emptyMessage = 'No data found';
        $jobsapplieds = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')
            ->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')
            ->where('user_biding.user_id', $user->id)
            ->select('user_biding.*', 'user_biding.id as user_biding_id', 'user_biding.user_id as freelancer_id', 'jobs.*')
            ->orderBy('user_biding.id', 'desc')
            ->paginate(getPaginate());

        return view('templates.basic.user.seller.jobs.index', compact('pageTitle', 'emptyMessage', 'jobsapplieds'));
    }

    public function current_jobs()
    {
        $user = Auth::user();
        $pageTitle = 'Current Jobs';
        $emptyMessage = 'No data found';
        $jobsapplieds = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('user_biding.user_id', $user->id)->where('user_biding.status', 1)->select('user_biding.*', 'user_biding.user_id as freelancer_id', 'jobs.*')->orderby('user_biding.id', 'desc')->paginate(getPaginate());

        return view('templates.basic.user.seller.jobs.currentjobs', compact('pageTitle', 'emptyMessage', 'jobsapplieds'));
    }

    public function completed_jobs()
    {
        $user = Auth::user();
        $pageTitle = 'Current Jobs';
        $emptyMessage = 'No data found';
        $jobsapplieds = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')->join('users as freelancer', 'user_biding.user_id', '=', 'freelancer.id')->where('user_biding.user_id', $user->id)->where('user_biding.status', 2)->select('user_biding.*', 'user_biding.user_id as freelancer_id', 'jobs.*')->orderby('user_biding.id', 'desc')->paginate(getPaginate());

        return view('templates.basic.user.seller.jobs.currentjobs', compact('pageTitle', 'emptyMessage', 'jobsapplieds'));
    }

    public function bidcancelbid(Request $request)
    {

        // Buyercounterofferdata::where('bid_id',$id)->where('job_id',$request->sendofferjob_id)->where('sender_id',$request->sendoffersender_id)->delete->where('reciever_id',$request->sendofferrec_id)->delete();
        $sent = Userbiding::find($request->cancelbid_id);
        if (! $sent) {
            return redirect()->back();
        }

        // $noti = new Notification();
        // $noti->reciever_id = $request->sendofferrec_id;
        // $noti->sender_id = $request->sendoffersender_id;
        // $noti->type = 'buyer_offer_cancel';
        // $noti->job_id = $request->sendofferjob_id;
        // $noti->save();
        // sendRealTimeNotification($request->sendofferrec_id, 'buyer_offer_cancel');

        // send email to bidders that offers are canceled
        sendEmail($sent->job->user, 'PROPOSAL_WITHDRAWN', [
            'sender_name' => $sent->user->username,
            'withdrawal_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'project' => $sent->job->title,
        ]);

        $sent->delete();

        // Vikash Change
        $notify[] = ['success', 'Offer successfully canceled'];

        return redirect()->back()->withNotify($notify);
    }

    public function acceptBuyerFixedBid(Request $request, $encUserBidId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        try {
            DB::beginTransaction();
            $id = decrypt($encUserBidId);

            $buyerCounterOffer = Buyercounteroffer::find($id);
            $job = Job::where('id',$buyerCounterOffer->job_id)->where('job_status',0)->first();
            if(is_null($job)) {
                return redirect()->back()->withNotify(['error' => 'Job has been Expired'])->withInput();
            }else {
                $job->job_status = 1;
                $job->save();
            }

            if (! $buyerCounterOffer) {
                return redirect()->back()->withNotify(['error' => 'Offer  not found.'])->withInput();
            }
            $booking = Booking::where('job_id', $buyerCounterOffer->job_id)->first();

            $buyer = User::find($buyerCounterOffer->sender_id);
            $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $buyer->id)->get();
            if(count($usermembership1) == 0) {
                return redirect()->back()->withNotify(['error' => 'Membership  not found.']);
            }
            $service_platform_fee = $usermembership1[0]->service_platform_fee;

            // Calculate platform fee amount from booking amount
            $platform_fee_amount = ($booking->amount * $service_platform_fee) / 100;

            // Calculate total deduction (booking amount + platform fee)
            $total_deduction = $booking->amount + $platform_fee_amount;

            $bidId = $buyerCounterOffer->bid_id;
            if ($bidId) {
                $bid = Userbiding::where('id', $bidId)->first();
                if (! $bid && ! $buyerCounterOffer->invite_professional) {
                    return redirect()->back()->withNotify(['error' => 'Bid not found.'])->withInput();
                }
            }
            // if (! $buyerCounterOffer->invite_professional) {
            $bid->seller_offer_responce = '2';
            $bid->bid_status = 'seller_offer_accepted';
            if (! $bid->save()) {
                return redirect()->back()->withNotify(['error' => 'Failed to accept the offer. Please try again.'])->withInput();
            }
            // }
            $buyerCounterOffer->status = 1;
            $buyerCounterOffer->save();

            Acceptedbid::updateOrCreate(
                [
                    'job_id' => $buyerCounterOffer->job_id,
                    'bid_id' => $bid->id ?? null,
                ],
                [
                    'buyer_id' => $buyerCounterOffer->sender_id,
                    'seller_id' => $buyerCounterOffer->reciever_id,
                    'is_endcontract' => 0,
                    'status' => 1,
                ]
            );

            // if($buyerCounterOffer->invite_professional == 1) {
                Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status', 0)->get();
                if(isset($buyerCounterOffer->buyerCounterOfferData) && $buyerCounterOffer->buyerCounterOfferData->isNotEmpty()) {
                    foreach($buyerCounterOffer->buyerCounterOfferData as $data) {
                        $data->status = 1;
                        $data->save();
                    }
                }

            // }
            //old code
            // else {
            //     $buyercounterofferdata = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status', 0)->first();
            //     $buyercounterofferdata->status = 1;
            //     $buyercounterofferdata->save();

            // }

            $booking->status = 1;
            $booking->working_status = 4;
            $booking->updated_at = Carbon::now();

            $booking->status_updated_at = Carbon::now();
            $booking->save();

            // $platform_fee_amount = ($booking->amount * $service_platform_fee) / 100;

            if($job->budgettype != 'hourly')
            {
                $buyer->balance -= $total_deduction;
                $buyer->save();

                //enable transaction again
                $trans_acc1 = [
                    'user_id' => $buyer->id,
                    'to_user' => $buyerCounterOffer->reciever_id,
                    'booking_id' => $booking->id,
                    'amount' => $booking->amount + $platform_fee_amount,
                    'trx_type' => '-',
                    'details' => 'Job approval received, and the following amount relates to the approved job',
                    'post_balance' => $buyer->balance,
                    'charge'=>$platform_fee_amount,
                    'trx' => getTrx(),
                    'status'=>0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $trans_acc2 = [
                    'user_id' => $buyer->id,
                    'to_user' => $buyerCounterOffer->reciever_id,
                    'booking_id' => $booking->id,
                    'amount' => $platform_fee_amount,
                    'trx_type' => '-',
                    'details' => 'Platform commission have been deducted / levied as a fee.',
                    'post_balance' => $buyer->balance,
                    'charge'=>0,
                    'trx' => getTrx(),
                    'status'=>0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                Transaction::create($trans_acc1);
                Transaction::create($trans_acc2);
            }

            DB::commit();
            sendJobEmailWithNotification($booking->seller_id, $booking->buyer_id, $booking->job_id, 'NOTIFY_BUYER_OFFER_ACCEPT', false, null, null, null, $buyerCounterOffer->id);
            sendAdminRealTimeNotification(Auth::user(), Auth::user()->username.' accepted the offer of '.$buyer?->username, route('admin.job.details', ['id' => $booking->job_id]));

            return redirect()->back()->withNotify(['success' => 'Offer accepted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in accepting Offer offer: '.$e->getMessage());

            return redirect()->back()->withNotify(['error' => $e->getMessage()]);
        }
    }

    public function declineBuyerFixedBid(Request $request, $encUserBidId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        // Decrypt the encrypted bid ID
        $id = decrypt($encUserBidId);

        // Validate the request input
        $request->validate([
            'decline_reason' => 'required|string|max:255', // Ensure the reason is required and is a string
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            $counterOffer = Buyercounteroffer::findOrFail($id);
            $bidId = $counterOffer->bid_id;
            $bid = Userbiding::where('id', $bidId)->firstOrFail();

            // Update bid attributes
            $bid->seller_offer_responce = '1';
            $bid->is_buyer_offer_sent = '0';
            $bid->seller_offer_responce_reason = $request->decline_reason; // Use the validated reason
            $bid->check_tag = 1;
            $bid->bid_status = 'seller_offer_decline';
            $bid->save();

            // Retrieve the related job
            $job = Job::where('id', $bid->job_id)->firstOrFail();

            // Update related counter offer and its data
            // $counterOfferId = DB::table('buyer_counter_offers')
            //     ->where('bid_id', $id)
            //     ->where('job_id', $job->id)
            //     ->where('sender_id', $job->user_id)
            //     ->where('reciever_id', Auth::user()->id)
            //     ->value('id'); // Fetch only the ID

            // $counterOffer = Buyercounteroffer::findOrFail($counterOfferId);
            $counterOffer->status = 2; // 2 here shows Rejected from seller
            $counterOffer->rejected_reason = $request->decline_reason;
            $counterOffer->save();

            Buyercounterofferdata::where('counter_offer_id', $counterOffer->id)->update(['status' => 2]);

            // Send notification email to the buyer
            sendJobEmailWithNotification($bid->user_id, $job->user_id, $job->id, 'NOTIFY_BUYER_JOB_OFFER_DECLINED', false, null, null, null, $counterOffer->id);

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return redirect()->back()->withNotify(['success' => 'Offer declined successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error (optional)
            Log::error('Error in declining offer: '.$e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withNotify(['error' => 'Failed to decline the offer. Please try again.']);
        }
    }

    public function declineBuyerInviteFixedBid(Request $request, $encUserBidId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        // Decrypt the encrypted bid ID
        $id = decrypt($encUserBidId);

        // Validate the request input
        $request->validate([
            'decline_reason' => 'required|string|max:255', // Ensure the reason is required and is a string
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            $counterOffer = Buyercounteroffer::findOrFail($id);
            $sender = User::find($counterOffer->sender_id);

            // Update related counter offer and its data
            // $counterOfferId = DB::table('buyer_counter_offers')
            //     ->where('bid_id', $id)
            //     ->where('job_id', $job->id)
            //     ->where('sender_id', $job->user_id)
            //     ->where('reciever_id', Auth::user()->id)
            //     ->value('id'); // Fetch only the ID

            // $counterOffer = Buyercounteroffer::findOrFail($counterOfferId);
            $counterOffer->status = 2; // 2 here shows Rejected from seller
            $counterOffer->rejected_reason = $request->decline_reason;
            $counterOffer->save();

            $buyercounterofferdata = Buyercounterofferdata::where('counter_offer_id', $counterOffer->id)->first();
            $buyercounterofferdata->status = 2;
            $buyercounterofferdata->save();

            $booking = Booking::where('job_id',$counterOffer->job_id)->first();

            // Send notification email to the buyer
            sendJobEmailWithNotification($buyercounterofferdata->reciever_id, $buyercounterofferdata->sender_id, $counterOffer->job_id, 'NOTIFY_BUYER_JOB_OFFER_DECLINED', false, null, null, null, $counterOffer->id);
            sendAdminRealTimeNotification(Auth::user(), 'Rejected the offer of '.$sender?->username, route('admin.job.details', ['id' => $booking->job_id]));

            // Commit the transaction
            DB::commit();

            // Redirect back with a success message
            return redirect()->back()->withNotify(['success' => 'Offer declined successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error (optional)
            Log::error('Error in declining offer: '.$e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withNotify(['error' => 'Failed to decline the offer. Please try again.']);
        }
    }

    public function jobListView(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('user.authorization');
        }

        $pageTitle = 'Bids';
        $statusArray = ['pending', 'buyer_offer_received', 'seller_offer_decline', 'seller_offer_accepted'];
        $data = $this->getUserBiddingData($request, $statusArray);

        $status = $user->status;
        if ($request->ajax()) {
            return view('templates.basic.user.seller.ajax_job_list', compact('pageTitle', 'data'))->render();
        }

        return view('templates.basic.user.seller.job_list', compact('pageTitle', 'data', 'status'));
    }

    public function jobBids(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('user.authorization');
        }

        $pageTitle = 'Bids';
        $statusArray = ['pending', 'buyer_offer_received', 'seller_offer_decline', 'seller_offer_accepted'];
        $data = $this->getUserBiddingData($request, $statusArray);
        if ($request->ajax()) {
            return view('templates.basic.user.seller.ajax_bids', compact('pageTitle', 'data'))->render();
        }

        return view('templates.basic.user.seller.job_list', compact('pageTitle', 'data'));
    }

    public function jobOffers(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $pageTitle = 'Offers';
        $statusArray = ['1', '2', '3'];
        $userId = auth()->id(); // Get the authenticated user's ID

        // Fetch offers for the authenticated user from Buyercounteroffer
        $paginatedJobs = Buyercounteroffer::whereHas('buyerCounterOfferData', function ($q) {
            $q->whereNotNull('amount'); // Ensure the related data has a valid amount
        })
            ->whereHas('job',function($jb) use($request){
                $jb->where('title','like','%'.$request->search.'%');
            })
            ->where('reciever_id', $userId) // Check for the user_id in Buyercounteroffer
            ->with('buyerCounterOfferData', 'job') // Eager load related data and job
            ->orderBy('created_at', 'desc')
            ->paginate(); // Retrieve all results (as collection)
        if ($request->ajax()) {
            return view('templates.basic.user.seller.ajax_offers', compact('pageTitle', 'paginatedJobs'))->render();
        }

        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
        //     ['title' => 'Offers', 'url' => ''],
        // ];

        return view('templates.basic.user.seller.job_list', compact('pageTitle', 'paginatedJobs'));
    }

    /**
     * A common method to handle the job query and data preparation for multiple functions.
     *
     * @param  array  $statusArray
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getJobData(Request $request, $status = null, $showCompletedFlag = false)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified

        // Build the query to fetch job data
        $query = BuyerCounterOffer::with([
            'buyerCounterOfferData',
            'job' => function ($q) use ($showCompletedFlag) {
                $q->select('id', 'title', 'budgettype', 'show_completed');
                if (! $showCompletedFlag) {
                    $q->where('show_completed', null);
                }
            },
        ])->select('id', 'bid_id', 'job_id', 'created_at', 'status') // Fetch main fields for BuyerCounterOffer
            ->where('reciever_id', $user->id)->whereIn('status', $status)
            ->whereHas('job', function ($q) use ($showCompletedFlag) {
                if (! $showCompletedFlag) {
                    $q->where('show_completed', null);
                }
            });
        // If a search keyword is provided, filter the results by job title
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }
        $query->orderBy('created_at', 'desc');
        // Paginate the query with 10 results per page
        $data = $query->paginate(10, ['*'], 'page', $page);
        // Modify the paginated data collection to add additional attributes and calculations
        $data->getCollection()->transform(function ($item) {
            $item->buyer_id = Crypt::encrypt($item->id);
            $item->user_bidding_id = Crypt::encrypt($item->bid_id);
            $item->job_title = $item->job->title ?? 'N/A';
            $item->job_type = $item->job->job_type;
            $item->buyerCounterOfferStatus = $item->status;
            // Calculate the total offer amount by parsing stringified arrays
            $totalAmount = 0;
            if ($item->buyerCounterOfferData->isNotEmpty()) {
                foreach ($item->buyerCounterOfferData as $offerData) {
                    $amounts = json_decode($offerData->amount, true); // Convert the stringified array to a PHP array
                    if (is_array($amounts)) {
                        $totalAmount += array_sum($amounts); // Sum the array elements
                    } else {
                        $totalAmount += (float) $offerData->amount; // If not an array, treat it as a single float amount
                    }
                }
            }

            $item->offer_amount = $totalAmount; // Set the total offer amount
            $item->offer_date = $item->created_at ? $item->created_at->format('d-M-Y') : 'N/A';

            // Set the status label and handle null status cases
            $item->status_label = $item->buyerCounterOfferData->isNotEmpty()
                ? $item->buyerCounterOfferData->first()->getStatusLabel()
                : 'Unknown';

            return $item;
        });

        return $data;
    }

    private function getUserBiddingData(Request $request, array $statusArray)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified

        // Build the query to fetch job data
        $query = Userbiding::where('user_id', $user->id)
            ->whereIn('bid_status', $statusArray)
            ->whereHas('job') // Exclude records where the job relationship is null
            ->orderBy('created_at', 'desc')
            ->with(['user', 'job']);

        // If a search keyword is provided, filter the results by job title
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Paginate the query with 10 results per page
        $data = $query->paginate(10, ['*'], 'page', $page)->withQueryString();

        // Fetch the platform fee for the user
        $platformFee = $this->membershipService->getMembershipFee($user->id);

        // Modify the paginated data collection to add additional attributes and calculations
        $data->getCollection()->transform(function ($item) use ($platformFee) {
            // Calculate prices, platform fee percentage, and other necessary formatting
            $item = $this->calculatePriceAndFormat($item);
            $item = $this->calculatePlatformfeePercentage($item, $platformFee);

            // Encrypt job ID and bid ID for security
            $item->encrypted_job_id = Crypt::encrypt($item->job_id);
            $item->encrypted_id = Crypt::encrypt($item->id);

            // Format the creation date
            $item->created_date = Carbon::parse($item->created_at)->format('d M, Y');

            $item->status_label = $item?->buyerCounterOffer
                ? $item->buyerCounterOffer->first()->getStatusLabel()
                : 'Unknown';

            return $item;
        });

        return $data;
    }

    public function jobVacancyactive(Request $request)
    {

        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = 'Active Jobs';

        // Define the statuses for active jobs
        $statusArray = [1];

        // Call the common method for fetching job data based on status
        $data = $this->getJobData($request, $statusArray);

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return view('templates.basic.user.seller.ajax_active_jobs', compact('pageTitle', 'data'))->render();
        }

        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
        //     ['title' => 'Active Jobs', 'url' => ''],
        // ];
        // For non-AJAX requests, return a full view
        return view('templates.basic.user.seller.job_list', compact('pageTitle', 'data'));
    }

    public function jobVacancycompleted(Request $request)
    {

        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = 'Completed Jobs';
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified
        $jobs = Job::where('show_completed', 1)->select('id', 'title', 'budgettype')->get();
        // Prepare an array to store valid job IDs
        $validJobIds = $jobs->pluck('id');
        $query = Buyercounteroffer::whereIn('job_id', $validJobIds)->where('reciever_id', $user->id)->where('status', 1);
        // Apply search filter if provided
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }
        // Order by created date in descending order
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $data = $query->paginate(10, ['*'], 'page', $page); // Adjust the number 10 to however many results per page you want

        // Transform the collection to include the necessary data
        $data->getCollection()->transform(function ($item) {
            $item->encrypted_job_id = Crypt::encrypt($item->job_id);
            $item->created_date = Carbon::parse($item->created_at)->format('Y-m-d');

            $totalAmount = 0;
            if ($item->buyerCounterOfferData->isNotEmpty()) {
                foreach ($item->buyerCounterOfferData as $offerData) {
                    $amounts = json_decode($offerData->amount, true); // Convert the stringified array to a PHP array
                    if (is_array($amounts)) {
                        $totalAmount += array_sum($amounts); // Sum the array elements
                    } else {
                        $totalAmount += (float) $offerData->amount; // If not an array, treat it as a single float amount
                    }
                }
            }

            $item->offer_amount = $totalAmount;
            $item->job_title = $item->job->title; // Only set if job exists
            $item->job_type = $item->job->job_type; // Assuming job_type is defined in the Job model
            $item->encUserBiddingId = encrypt($item->id);

            $item->username = optional($item->user)->name ?? 'N/A'; // Use optional() to avoid errors

            return $item;
        });

        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
        //     ['title' => 'Completed Jobs', 'url' => ''],
        // ];

        // Handle AJAX response
        if ($request->ajax()) {
            return view('templates.basic.user.seller.ajax_completed_jobs', compact('pageTitle', 'data'))->render();
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.seller.job_list', compact('pageTitle', 'data'));
        }
    }

    public function jobVacancyBidList(Request $request, $encrypted_job_id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $pageTitle = 'Bid Details';
        $user = Auth::user();
        $id = decrypt($encrypted_job_id);  // Decrypt the job ID

        $search = $request->input('search');

        // Fetch the single job bid record
        $query = Userbiding::where('job_id', $id)
            ->where('user_id', $user->id)
            ->with(['user', 'biddingStatus', 'job']);  // Remove 'milestones' relationship

        // Apply search filter if search is provided
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Fetch the record
        $data = $query->first();

        // If no record is found, handle it (optional)
        if (! $data) {
            return abort(404, 'Bid not found.');
        }

        $platformFeePercentage = $this->membershipService->getMembershipFee($user->id);
        $jobPosterName = $data->job->user->username;

        // Initialize expected total price and milestones
        $expected_total_price = 0;
        $milestones = collect(); // Initialize as an empty collection

        // Check job type and calculate accordingly
        if ($data->job_type == 'hourly') {
            // For hourly jobs, calculate total based on bid price and hours worked
            $expected_total_price = $data->job->amount; // hourly rate x hours
        } elseif ($data->job_type == 'byproject') {
            // For 'byproject' jobs, use bid price directly from the Userbidding table
            $expected_total_price = $data->bid_price;
        } elseif ($data->job_type == 'bymilestone') {
            // For 'bymilestone' jobs, calculate total based on milestones
            $milestones = UserMilestone::where('bid_id', $data->id)->get(); // Fetch milestones from the user_milestone table

            // Calculate total amount of milestones
            $expected_total_price = $milestones->sum('amount');
        }

        // Calculate platform fee
        $platform_fee = $expected_total_price * ($platformFeePercentage / 100);

        // Calculate total after platform fee deduction
        $total_after_fee = $expected_total_price - $platform_fee;

        // Encrypt job ID
        $encrypted_job_id = Crypt::encrypt($data->job_id);
        $status = $data->seller_offer_responce ?? 'Pending';

        // Return the view with data and platform fee information
        return view('templates.basic.user.seller.offered_details', compact(
            'pageTitle',
            'data',
            'platformFeePercentage',
            'status',
            'expected_total_price',
            'platform_fee',
            'total_after_fee',
            'encrypted_job_id',
            'milestones',
            'jobPosterName'
        ));
    }

    public function jobBiddingfixed(Request $request, $slug, $id)
    {
        // check if bidId is coming in params
        $data = $request->all();
        $bidId = isset($data['bidId']) ? $data['bidId'] : null;

        $user = Auth::user();
        if (! $user) {
            return redirect()->back();
        }

        if (! $bidId) {
            $flag = $this->membershipService->isUserBiddingAvailable($user->id);
            if (! $flag) {
                $notify[] = ['error', 'Bid Limit Reached'];

                return back()->withNotify($notify);
            }
        }

        $pageTitle = 'Job Bidding';

        $job = Job::where('status', 1)->where('id', decrypt($id))->first();

        if (! $job) {
            return redirect()->back();
        }

        // if ($usermembership && $usermembership[0] && $usermembership[0]->jobbidcap != 'all') {
        //     if ($job->amount > $usermembership[0]->jobbidcap) {
        //         $notify[] = ['error', 'You cannot bid on this job'];
        //         return redirect()->back()->withNotify($notify);
        //     }
        // }

        // $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
        $jobSkills = Jobskill::where('job_id', $job->id)->pluck('skill_id')->toArray();
        $matchingSkills = UserProfileSkill::whereIn('skill_id', $jobSkills)->where('user_id', $user->id)->pluck('skill_id')->toArray();
        $matchingSkills = Skill::whereIn('id', $matchingSkills)->get();

        $membershipFee = $this->membershipService->getMembershipFee($user->id);

        $job->exp_level = '';
        if ($job->exp_type == '1') {
            $job->exp_level = 'Entry level';
        }

        if ($job->exp_type == '2') {
            $job->exp_level = 'Intermediate';
        }

        if ($job->exp_type == '3') {
            $job->exp_level = 'Expert';
        }

        $job->budget_type = '';
        if ($job->budgettype == 'fixed') {
            $job->budget_type = 'Fixed';
        }

        if ($job->budgettype == 'per_hour') {
            $job->budget_type = 'Per Hour';
        }

        $job->budget_value = '';
        $job->budget_value_text = '';
        if ($job->budgettype == 'per_hour') {
            $job->budget_value = $job->maxBudget;
            $job->budget_value_text = '$'.$job->minBudget.'- $'.$job->maxBudget.'/hour';
        }

        if ($job->budgettype == 'fixed') {
            $job->budget_value = $job->amount;
            $job->budget_value_text = '$'.$job->amount;
        }
        $bidw = null;

        if ($bidId) {
            $id = decrypt($bidId);
            $bid = Userbiding::with('userMilestones')->where('id', $id)->first();

            if ($bid) {
                $bidw = $bid;
            }
        }

        return view('templates.basic.job_bidding_fixed', compact('membershipFee', 'pageTitle', 'job', 'user', 'matchingSkills', 'bidw'));
    }

    public function jobreviseBiddingproject($id1, $id2)
    {

        $user = Auth::user();
        $pageTitle = 'Project Bidding Revise';
        $job = Job::where('id', decrypt($id2))->first();
        $bid = UserBiding::where('job_id', decrypt($id2))->where('id', decrypt($id1))->first();
        $jobBidings = JobBiding::where('job_id', $job->id)->with('user')->inRandomOrder()->paginate(5);
        $usermembership = null;
        if (! empty($user)) {
            if ($user->is_freelancer == 1) {
                $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            }
        }

        return view($this->activeTemplate.'job_revisebidding_project', compact('pageTitle', 'bid', 'job', 'usermembership', 'jobBidings'));
    }

    public function addBiding(Request $request)
    {
        DB::beginTransaction();
        try {

            if($request->invite_professional == 1) {

                $user = Auth::user();
                $employerServiceCount = Employerservicescount::where('user_id', $user->id)->first();
                $invites = $employerServiceCount?->invites ?? 0;
                if($invites == 0) {
                    $notify[] = ['error', 'Your invites are already at 0 , Please update your membership'];
                    return back()->withNotify($notify)->withInput();
                }

                $data = $request->all();

                // Separate validation for project methods
                if ($request->projectmethod == 'byproject') {
                    $this->validateProjectMethod($request);
                } elseif ($request->projectmethod == 'bymilestone') {
                    $this->validateMilestoneMethod($request);
                }

                if($request->projectmethod == 'hourly') {

                    $binding = new Userbiding();
                    $binding->job_id = $request->job_id;
                    $binding->user_id = $request->reciever;
                    $binding->job_type = 'hourly';
                    $binding->bid_price = $request->hourly_amount;
                    $binding->title = $request->title;
                    $binding->proposal = $request->proposal;
                    $binding->cutout = $request->cutfix;
                    $binding->order_number = getTrx();
                    $binding->created_date = date('Y-m-d');
                    $binding->created_time = date('H:i:s');
                    $binding->check_tag = 0;
                    $binding->seller_offer_responce = 0;
                    $workFile = imagePath()['workFile']['path'];
                    if ($request->hasFile('file')) {
                        // Handle the file upload
                        $binding->file = uploadFile($request->file('file'), 'user_bidding');
                    }
                    $binding->save();

                    $reciever_user = User::find($request->reciever);
                    $job = Job::find($request->job_id);

                    $offer =  new Buyercounteroffer();

                    $offer->fill([
                        'job_id' => $request->job_id,
                        'type' => 'hourly',
                        'status' => 0,
                        'bid_id' => $binding->id,
                        'sender_id' => Auth::id(),
                        'reciever_id' => $request->reciever,
                        'description' => $request->title,
                        'allow_manual_time' => $request->manual_time_logging === 'on' ? 1 : 0,
                        'is_manual_log_accepted' => $request->manual_time_logging === 'on' ? 1 : 0,
                        'close_job' => $request->has('close_project') ? true : false, // Set close_job based on checkbox
                        'invite_professional' => 1,
                    ]);
                    $offer->save();

                    $counterOfferData = new Buyercounterofferdata();
                    $counterOfferData->fill([
                        'counter_offer_id' => $offer->id,
                        'amount' => $request->hourly_amount,
                        'days' => $request->weekly_hours,
                        'sender_id' => Auth::id(),
                        'reciever_id' => $request->reciever,
                        'status' => 0,
                        'requested_milestone_status'=>0,
                        'milestone_status'=>0,
                        'job_id' => $request->job_id,
                        'bid_id' => $binding->id,
                    ]);

                    Booking::create([
                        'buyer_id' => Auth::id(),
                        'service_id' => $offer->id,
                        'seller_id' => $request->reciever,
                        'job_id' => $request->job_id,
                        'qty' => 1,
                        'amount' => $request->hourly_amount * $request->weekly_hours,
                        'order_number' => getTrx(),
                        'status' => 0,
                        'booking_type' => $request->job_type,
                        'updated_at' => now(),
                        'status_updated_at' => now(),
                    ]);
                    $counterOfferData->save();


                    sendJobEmailWithNotification($user->id, $reciever_user->id, $job->id, 'INVITE_FREELANCER', false, $request->bid_id,null,null, $offer->id);
                    sendAdminRealTimeNotification(Auth::user(), 'Invite freelancer on job '.$binding?->job?->title, route('admin.job.details', ['id' => $binding?->job_id]));
                    DB::commit();

                }else if($request->projectmethod == 'byproject' || $request->projectmethod == 'bymilestone') {
                    $user = Auth::user();
                    $binding = new Userbiding();
                    $binding->job_id = $request->job_id;
                    $binding->title = $request->title ?? '';
                    $binding->job_type = $request->projectmethod == 'fixed' ? 'byproject' : $request->projectmethod;
                    $binding->due_date = $request->due_date ?? null;
                    $binding->user_id = $request->reciever;
                    if ($request->projectmethod == 'byproject') {
                        $binding->bid_price = $request->bid_price;
                    } elseif ($request->projectmethod == 'bymilestone') {
                        $milestoneAmounts = array_map('floatval', $request->milestone_amount ?? []);
                        $binding->bid_price = array_sum($milestoneAmounts); // Sum of milestone amounts
                    }
                    $binding->cutout = $request->projectmethod == 'bymilestone' ? $request->cutmile : $request->cutfix;
                    $binding->order_number = getTrx();
                    $binding->proposal = $request->proposal;
                    $binding->seller_offer_responce = 0;
                    $binding->created_date = date('Y-m-d');
                    $binding->created_time = date('H:i:s');
                    $binding->is_buyer_offer_sent = 1;
                    $binding->save();

                    $acceptedBid = new Acceptedbid();
                    $acceptedBid->job_id = $request->job_id;
                    $acceptedBid->buyer_id = $user->id;
                    $acceptedBid->seller_id = $request->reciever;
                    $acceptedBid->status = 0;
                    $acceptedBid->bid_id = $binding->id;
                    $acceptedBid->save();

                    if ($request->hasFile('attachment')) {
                        $this->handleAttachments($request, $user, $binding);
                    }
                    $job = Job::find($request->job_id);
                    $reciever_user = User::find($request->reciever);

                    $offer =  new Buyercounteroffer();

                    $offer->fill([
                        'job_id' => $request->job_id,
                        'type' => $request->projectmethod == 'fixed' ? 'byproject' : $request->projectmethod,
                        'status' => 0,
                        'bid_id' => $request->bid_id ?? $binding->id,
                        'sender_id' => Auth::id(),
                        'reciever_id' => $request->reciever,
                        'description' => $request->title,
                        'allow_manual_time' => $request->manual_time_logging === 'on' ? 1 : 0,
                        'is_manual_log_accepted' => $request->manual_time_logging === 'on' ? 1 : 0,
                        'close_job' => $request->has('close_project') ? true : false, // Set close_job based on checkbox
                        'invite_professional' => $request->has('invite_professional') ? true : false,
                    ]);
                    $offer->save();

                    if($request->projectmethod == 'bymilestone') {
                        $titles = $request->milestone_title;
                        $amounts = $request->milestone_amount;
                        $dueDates = $request->milestone_due_date;


                        foreach ($titles as $key => $title) {
                            $milestoneDays = (int) $dueDates[$key];
                            $new_due_date = Carbon::now()->addDays($milestoneDays)->format('Y-m-d');

                            Buyercounterofferdata::create([
                                'counter_offer_id' => $offer->id,
                                'job_id' => $request->job_id,
                                'bid_id' => $binding->id,
                                'sender_id' => $user->id,
                                'reciever_id' => $request->reciever,
                                'days' =>  $milestoneDays,
                                'status'=>0,
                                'amount' => $amounts[$key],
                                'title' => $title,
                                'due_date' => $new_due_date,
                                'milestone_status' => 0,
                            ]);

                            UserMilestone::create([
                                'bid_id' => $binding->id,
                                'title' => $title,
                                'due_date' => $new_due_date,
                                'amount' => $amounts[$key],
                            ]);
                        }
                    }else {
                        $counterOfferData = new Buyercounterofferdata();
                        $counterOfferData->fill([
                            'counter_offer_id' => $offer->id,
                            'amount' => $binding->bid_price,
                            'milestone_amount' => $request->projectmethod == 'bymilestone' ? json_encode($request->milestone_amount) : null,
                            'days' => $request->due_date,
                            'description' => $request->proposal,
                            'sender_id' => Auth::id(),
                            'reciever_id' => $request->reciever,
                            'status' => 0,
                            'requested_milestone_status'=>0,
                            'milestone_status'=>0,
                            'job_id' => $request->job_id,
                            'bid_id' => $binding->id,
                        ]);
                        $counterOfferData->save();
                    }

                    Booking::create([
                        'buyer_id' => Auth::id(),
                        'service_id' => $offer->id,
                        'seller_id' => $request->reciever,
                        'job_id' => $request->job_id,
                        'qty' => 1,
                        'amount' => $binding->bid_price,
                        'order_number' => getTrx(),
                        'status' => 0,
                        'booking_type' => $request->job_type,
                        'updated_at' => now(),
                        'status_updated_at' => now(),
                    ]);

                    sendJobEmailWithNotification($user->id, $reciever_user->id, $job->id, 'INVITE_FREELANCER', false, $request->bid_id,null,null, $offer->id);
                    sendAdminRealTimeNotification(Auth::user(), 'Invite freelancer on job '.$binding?->job?->title, route('admin.job.details', ['id' => $binding?->job_id]));
                    DB::commit();
                }

                $employerServiceCount->invites -= 1;
                $employerServiceCount->save();

                $notify[] = ['success', 'Invited Professional Sccessfully '.('submitted')];

                return redirect(url('/user/buyer/hire/open'))->withNotify($notify);
            }else {

                $data = $request->all();
                $bidId = isset($data['bidId']) ? $data['bidId'] : null;
                $isEdit = $bidId !== null;

                // Separate validation for project methods
                if (in_array($request->projectmethod, ['byproject', 'hourly'])) {
                    $this->validateProjectMethod($request);
                } elseif ($request->projectmethod == 'bymilestone') {
                    $this->validateMilestoneMethod($request);
                }

                $user = Auth::user();
                $binding = $isEdit ? Userbiding::find(decrypt($bidId)) : new Userbiding;
                $this->saveBidData($binding, $request, $user, $isEdit);

                if ($request->hasFile('attachment')) {
                    $this->handleAttachments($request, $user, $binding);
                }
                sendAdminRealTimeNotification(Auth::user(), 'Bid placed on job '.$binding?->job?->title, route('admin.job.details', ['id' => $binding?->job_id]));

                DB::commit(); // Commit transaction

                $notify[] = ['success', 'Proposal successfully '.($isEdit ? 'updated' : 'submitted')];

                return redirect(url('/user/seller/job/list'))->withNotify($notify);
            }


        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error('Error in addBiding: '.$e->getMessage());
            $notify[] = ['error', 'Something went wrong. Please try again.'];

            return redirect()->back()->withNotify($notify);
        }
    }

    protected function validateProjectMethod(Request $request)
    {
        $this->validate($request, [
            'due_date' => 'required',
            'bid_price' => 'required',
            'proposal' => 'nullable',
            'attachment' => 'sometimes|array|max:5',
            'attachment.*' => 'file|mimes:png,jpg,pdf,doc,docx|max:2048',
        ]);
    }

    protected function validateMilestoneMethod(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'proposal' => 'required',
        ]);

        $total = array_sum($request->milestone_amount);
        if ($total > (int) $request->job_amount) {
            $validator->after(function ($validator) {
                $validator->errors()->add('total', 'Bid amount should be equal to or less than job budget');
            });
        }
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    }

    protected function saveBidData($binding, $request, $user, $isEdit)
    {
        try {
            $binding->job_id = $request->job_id;
            $binding->title = $request->title ?? '';
            $binding->job_type = $request->projectmethod;
            $binding->due_date = $request->due_date ?? null;
            $binding->user_id = $user->id;
            if ($request->projectmethod == 'byproject') {
                $binding->bid_price = $request->bid_price;
            } elseif ($request->projectmethod == 'bymilestone') {
                $milestoneAmounts = array_map('floatval', $request->milestone_amount ?? []);
                $binding->bid_price = array_sum($milestoneAmounts); // Sum of milestone amounts
            }
            $binding->cutout = $request->projectmethod == 'bymilestone' ? $request->cutmile : $request->cutfix;
            $binding->order_number = getTrx();
            $binding->proposal = $request->proposal;
            $binding->seller_offer_responce = 0;
            $binding->created_date = date('Y-m-d');
            $binding->created_time = date('H:i:s');
            $binding->save();

            $this->handleBidsLogAndDecrement($user, $isEdit, $request->job_id);

            if ($request->projectmethod == 'bymilestone') {
                $this->saveMilestones($binding, $request, $isEdit);
            }
        }
        catch(Exception $e) {
            Log::info('Error in saveBidData: '.$e->getMessage());
            throw $e; // Rethrow the exception to be caught in the calling method
        }
    }

    protected function handleBidsLogAndDecrement($user, $isEdit, $jobId)
    {
        $job = Job::find($jobId);
        if (! $isEdit) {
            if ($job->priority == 1) {
                Freelancerservicescount::where('user_id', $user->id)->decrement('bids', 1);
            } else {
                Freelancerservicescount::where('user_id', $user->id)->decrement('featured_job_bids', 1);
            }
            $getbids = Freelancerservicescount::where('user_id', $user->id)->first();
            BidsLog::insert([
                'user_id' => $user->id,
                'bids' => 1,
                'tr_op' => '-',
                'post_balance' => $job->priority == 1 ? $getbids->bids : $getbids->featured_job_bids,
                'details' => $job->priority == 1
                ? 'Bid on '.$job->title
                : 'Featured Bid on '.$job->title,
            ]);
        }

        // Send Notification but ignore any errors
        try {
            sendJobEmailWithNotification($user->id, $job->user_id, $jobId, 'NEW_PROPOSAL_RECEIVED');
        } catch (\Exception $e) {
            Log::info('Notification error: '.$e->getMessage());
        }
    }

    protected function handleAttachments($request, $user, $binding)
    {
        foreach ($request->file('attachment') as $attachment) {
            try {

                $attachmentFileName = uniqid().'.'.$attachment->getClientOriginalName();
                $path = $attachment->storeAs('job_attachments', $attachmentFileName, 'public');

                JobAttachements::create([
                    'user_id' => $user->id,
                    'job_id' => $binding->job_id,
                    'image_url' => $path,
                ]);
            } catch (\Exception $e) {
                Log::error('File attachment error: '.$e->getMessage());
            }
        }
    }

    protected function saveMilestones($binding, $request, $isEdit)
    {
        if ($isEdit) {
            UserMilestone::where('bid_id', $binding->id)->delete();
        }
        foreach ($request->milestone_title as $key => $title) {
            UserMilestone::create([
                'bid_id' => $binding->id,
                'title' => $title,
                'due_date' => $request->milestone_due_date[$key],
                'amount' => $request->milestone_amount[$key],
            ]);
        }
    }

    public function workroom(Request $request, $encryptedId)
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
        // Fetch the user bidding data
        $acceptedBid = Acceptedbid::with(['job', 'seller', 'userBiding'])->where('job_id', $jobId)->where('seller_id', $user->id)->first();
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }

        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller;
        $buyer = $acceptedBid->buyer;
        if (! $seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }

        // Calculate total budget and payment details
        [$totalBudget, $pendingPayment, $paidAmount, $bonusAmount, $remainingAmount, $commissionAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        // [$totalBudget, $pendingPayment, $paidAmount, $remainingAmount,$commissionAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        // Prepare data for the view
        $job_type = $buyerCounterOffer?->type;
        $jobTitle = $acceptedBid?->job->title;
        $jobDescription = $acceptedBid?->job->description;
        $startDate = Carbon::parse($acceptedBid?->created_at)->format('Y-m-d');
        $status = $acceptedBid?->job->booking->working_status_text ?? 'N/A';
        $pageTitle = 'Workroom';
        // $encUserBidingId = Crypt::encrypt($userBidingId);

        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Projects', 'url' => '/user/seller/job/list/ajax/bids'],
        //     ['title' => 'Contract Overview', 'url' => ''],
        // ];

        $encJobId = encrypt($acceptedBid->job_id);
        // Details Start

        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee; // percentage
        $contract_details = [];
        $payment_details = [];
        $seller = $acceptedBid->seller;
        $sellerId = $acceptedBid->seller_id;
        $buyerId = $acceptedBid->buyer_id;
        $buyer = User::find($buyerId);
        $job = $acceptedBid->job;
        $isCompleted = $job->show_completed;
        $req_type = null;
        $user_biding = Userbiding::where('job_id', $jobId)->where('user_id', Auth::user()->id)->first();
        $cutout = 0;
        if ($user_biding) {
            $cutout = $user_biding->cutout;
        }
        if ($job_type == 'byproject') {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->latest()->first();
            $req_type = $buyerCounterOfferData?->req_type;

            $platform_fee_amount = number_format(($totalBudget * $platform_fee) / 100, 2);

            // Calculate final amount after subtracting platform fee
            // $final_amount = number_format($totalBudget - $platform_fee_amount, 2);
            $final_amount = number_format($totalBudget, 2);

            // If you need rounded values
            // $final_amount = number_format($totalBudget - $platform_fee_amount, 2);
            if (is_null($req_type)) {
                $contract_details = [
                    'Buyer' => User::find($buyerId)->fullname,
                    'Seller' => Auth::user()->fullname,
                    'Project Value' => '$'.$buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Delivery Date' => Carbon::parse($buyerCounterOfferData->due_date)->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status),
                ];
                $payment_details = [
                    'receive_payment' => $final_amount,
                    'total_budget' => $totalBudget,
                ];
            } else {
                $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->whereNull('req_type')->first();
                $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->whereNotNull('req_type')->where('status', 1)->get(['id', 'title', 'amount', 'due_date']);
                $contract_details = [
                    'Buyer' => User::find($buyerId)->fullname,
                    'Seller' => Auth::user()->fullname,
                    'Budget' => $job->amount,
                    'Bid Amount' => $buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status),
                ];
                $payment_details = [
                    'milestones' => $milestones->toArray(),
                    'receive_payment' => $totalBudget - $cutout,
                    'total_budget' => $totalBudget,
                ];
            }
        } elseif ($job_type == 'hourly') {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->first();
            $Jobworkdeliveryhourlybid = Jobworkdeliveryhourlybid::where('job_id', $job->id)->orderBy('id', 'desc')->where('status', 1)->get();
            $contract_details = [
                'Buyer' => User::find($buyerId)->fullname,
                'Seller' => Auth::user()->fullname,
                'Hourly Rate' => '$'.number_format($buyerCounterOfferData->amount, 2),
                'Weekly Limit' => $job->weekly_hours,
                'Estimated Weekly Hours' => $buyerCounterOfferData->days,
                'Billing Cycle' => 'Weekly',
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status),
            ];

            // foreach ($Jobworkdeliveryhourlybid as $index => $job_work_delivery_hourly_bid) {
            //     $start_date = Carbon::parse($job_work_delivery_hourly_bid?->date_start);
            //     $start_formatted_date = $start_date->format('d M Y');

            //     $end_date = Carbon::parse($job_work_delivery_hourly_bid?->date_end);
            //     $end_formatted_date = $end_date->format('d M Y');

            //     $payment_details[$index]['Week'] = "Week " . ($index + 1) . " ( " . $start_formatted_date . " - " . $end_formatted_date . " )";
            //     $payment_details[$index]['Hours'] = $job_work_delivery_hourly_bid['time'] . ' hour - ' . $job_work_delivery_hourly_bid['minutes'] . ' minutes';
            //     $payment_details[$index]['Amount'] = $job_work_delivery_hourly_bid['total_cost'];
            //     $payment_details[$index]['raw_hours'] = $job_work_delivery_hourly_bid['time'];
            //     $payment_details[$index]['raw_minutes']=  $job_work_delivery_hourly_bid['minutes'];
            // }
            // $payment_details['weekly_hours'] = $job->weekly_hours;
            // $payment_details['hourly_amount'] = $totalBudget;
            $grouped = [];
            foreach ($Jobworkdeliveryhourlybid as $job_work_delivery_hourly_bid) {
                $start_date = $job_work_delivery_hourly_bid->date_start;

                // Initialize group if not exists
                if (! isset($grouped[$start_date])) {
                    $grouped[$start_date] = [
                        'date_start' => $job_work_delivery_hourly_bid->date_start,
                        'date_end' => $job_work_delivery_hourly_bid->date_end,
                        'time' => 0,
                        'minutes' => 0,
                        'total_cost' => 0,
                    ];
                }

                // Sum up time, minutes, and cost
                $grouped[$start_date]['time'] += $job_work_delivery_hourly_bid->time;
                $grouped[$start_date]['minutes'] += $job_work_delivery_hourly_bid->minutes;
                $grouped[$start_date]['total_cost'] += $job_work_delivery_hourly_bid->total_cost;
            }

            // Adjust minutes > 60 → convert into hours
            foreach ($grouped as &$item) {
                if ($item['minutes'] >= 60) {
                    $extra_hours = floor($item['minutes'] / 60);
                    $item['time'] += $extra_hours;
                    $item['minutes'] = $item['minutes'] % 60;
                }
            }

            // Now format data for output
            $payment_details = [];
            foreach (array_values($grouped) as $index => $job_work_delivery_hourly_bid) {
                $start_date = Carbon::parse($job_work_delivery_hourly_bid['date_start']);
                $end_date = Carbon::parse($job_work_delivery_hourly_bid['date_end']);

                $start_formatted_date = $start_date->format('d M Y');
                $end_formatted_date = $end_date->format('d M Y');

                $payment_details[$index]['Week'] = 'Week '.($index + 1).' ( '.$start_formatted_date.' - '.$end_formatted_date.' )';
                $payment_details[$index]['Hours'] = $job_work_delivery_hourly_bid['time'].' hour - '.$job_work_delivery_hourly_bid['minutes'].' minutes';
                $payment_details[$index]['Amount'] = $job_work_delivery_hourly_bid['total_cost'];
                $payment_details[$index]['raw_hours'] = $job_work_delivery_hourly_bid['time'];
                $payment_details[$index]['raw_minutes'] = $job_work_delivery_hourly_bid['minutes'];
            }

            $payment_details['weekly_hours'] = $job->weekly_hours;
            $payment_details['hourly_amount'] = $totalBudget;

        } elseif ($job_type == 'bymilestone') {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status', 1)->whereNull('req_type')->get();
            $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status', 1)->get(['id', 'title', 'amount', 'due_date']);
            $contract_details = [
                'Buyer' => User::find($buyerId)->fullname,
                'Seller' => Auth::user()->fullname,
                'Budget' => '$'.$job->amount,
                'Bid Amount' => '$'.number_format($buyerCounterOfferData->sum('amount'), 2),
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status),
            ];
            $payment_details = [
                'milestones' => $milestones->toArray(),
                'receive_payment' => $totalBudget,
                'total_budget' => $totalBudget,
                'total_accepted_bid_amount' => number_format($buyerCounterOfferData->sum('amount'), 2),
            ];
        }

        return view('templates.basic.user.seller.workroom_list', compact(
            'commissionAmount',
            'status',
            'pageTitle',
            'job_type',
            'jobTitle',
            'jobDescription',
            'bonusAmount',
            // 'userBidingId',
            'seller',
            'startDate',
            'totalBudget',
            'pendingPayment',
            'paidAmount',
            'remainingAmount',
            // 'encUserBidingId',
            'encJobId',
            'buyer',
            'payment_details',
            'contract_details',
            'req_type',
            'isCompleted',
            'platform_fee'
        ));
    }

    public function workroomOverview(Request $request, $encryptedId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $user = Auth::user();
        $jobId = Crypt::decrypt($encryptedId);
        $job = Job::find($jobId);
        $pageTitle = 'Overview';
        // Fetch the user bidding data along with related models
        $acceptedBid = Acceptedbid::with(['job', 'seller', 'userBiding'])->where('job_id', $jobId)->where('seller_id', $user->id)->first();
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        [$totalBudget, $pendingPayment, $paidAmount, $bonusAmount, $remainingAmount, $commissionAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        // [$totalBudget, $pendingPayment, $paidAmount, $remainingAmount, $commissionAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        // dd($totalBudget, $pendingPayment, $paidAmount, $remainingAmount, $commissionAmount);
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        $startDate = Carbon::parse($acceptedBid->created_at)->format('Y-m-d'); // Adjust to your needs
        $status = $acceptedBid->job->booking->working_status_text;
        $job_type = $buyerCounterOffer->type;
        $jobTitle = $acceptedBid->job->title;
        $jobDescription = $acceptedBid->job->description;
        $encUserBidingId = encrypt($acceptedBid->bid_id);
        $seller = $acceptedBid->seller;
        $sellerId = $acceptedBid->seller_id;
        $buyerId = $acceptedBid->buyer_id;
        $buyer = User::find($buyerId);
        $encJobId = encrypt($acceptedBid->job->id);
        $isCompleted = $job->show_completed;
        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
        //     ['title' => 'Offered Jobs', 'url' => '/user/seller/hire/offered-jobs'],
        //     ['title' => 'Contract Overview', 'url' => ''],
        // ];
        if ($request->ajax()) {
            return view('seller.workspace.overview', compact('startDate', 'platform_fee', 'status', 'totalBudget', 'pendingPayment', 'paidAmount', 'bonusAmount', 'remainingAmount', 'commissionAmount', 'pageTitle', 'jobTitle', 'jobDescription', 'job_type', 'encUserBidingId'));
        }

        // Details Start

        $emp_mem_trans = Freelancermembershiptransactions::with('membership')->where('user_id', Auth::id())->where('status', 1)->first();
        $platform_fee = (float) $emp_mem_trans->membership->fee;
        $contract_details = [];
        $payment_details = [];
        $seller = $acceptedBid->seller;
        $sellerId = $acceptedBid->seller_id;
        $buyerId = $acceptedBid->buyer_id;
        $buyer = User::find($buyerId);
        $job = $acceptedBid->job;
        $isCompleted = $job->show_completed;
        $req_type = null;
        $user_biding = Userbiding::where('job_id', $job->id)->where('user_id', Auth::user()->id)->first();
        if ($job_type == 'byproject') {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->latest()->first();
            $req_type = $buyerCounterOfferData->req_type;
            if (is_null($req_type)) {
                $contract_details = [
                    'Buyer' => User::find($buyerId)->fullname,
                    'Seller' => Auth::user()->fullname,
                    'Project Value' => '$'.$buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Delivery Date' => Carbon::parse($buyerCounterOfferData->due_date)->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status),
                ];
                $payment_details = [
                    // 'receive_payment' => $totalBudget - $commissionAmount,
                    'receive_payment' => $totalBudget,
                    'total_budget' => $totalBudget,
                ];

            } else {
                $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->first();
                $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('status', 1)->get(['id', 'title', 'amount', 'due_date']);
                $contract_details = [
                    'Buyer' => User::find($buyerId)->fullname,
                    'Seller' => Auth::user()->fullname,
                    'Budget' => $job->amount,
                    'Bid Amount' => $buyerCounterOfferData->amount,
                    'Job ID' => $job?->booking?->order_number,
                    'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                    'Status' => $this->jobStatus($buyerCounterOffer->status),
                ];
                $payment_details = [
                    'milestones' => $milestones->toArray(),
                    'receive_payment' => $totalBudget ?? 0,
                    'total_budget' => $totalBudget,
                ];
            }
        } elseif ($job_type == 'hourly') {
            $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->first();
            $Jobworkdeliveryhourlybid = Jobworkdeliveryhourlybid::where('job_id', $job->id)
                ->where('status', 1)
                ->orderBy('date_start', 'asc')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->date_start)->format('o-W');
                });

            $contract_details = [
                'Buyer' => Auth::user()->fullname,
                'Seller' => User::find($sellerId)->fullname,
                'Hourly Rate' => '$'.$buyerCounterOfferData->amount,
                'Weekly Limit' => $job->weekly_hours,
                'Estimated Weekly Hours' => 20,
                'Billing Cycle' => 'Weekly',
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status),
            ];

            $payment_details = [];
            $weekIndex = 1;

            foreach ($Jobworkdeliveryhourlybid as $weeklyBids) {

                $start_date = Carbon::parse($weeklyBids->first()->date_start);
                $end_date = Carbon::parse($weeklyBids->last()->date_end);

                $start_formatted_date = $start_date->format('d M Y');
                $end_formatted_date = $end_date->format('d M Y');

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
                    'Amount' => round($totalCost, 2),
                    'raw_hours' => $hours,
                    'raw_minutes' => $minutes,
                ];

                $weekIndex++;
            }

            $payment_details['weekly_hours'] = $job->weekly_hours;
            $payment_details['hourly_amount'] = (int) $totalBudget;
        } elseif ($job_type == 'bymilestone') {
                $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status', 1)->where('status',1)->get();
                $milestones = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status', 1)->where('status',1)->get(['id', 'title', 'amount', 'due_date']);
            $buyerCounterOfferDataNullReqType = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)->where('requested_milestone_status', 1)->where('status',1)->get();

            $contract_details = [
                'Buyer' => User::find($buyerId)->fullname,
                'Seller' => Auth::user()->fullname,
                'Budget' => '$'.$job->amount,
                'Bid Amount' => '$'.number_format($buyerCounterOfferData->sum('amount'), 2),
                'Job ID' => $job?->booking?->order_number,
                'Start Date' => $acceptedBid->created_at->format('d-m-Y'),
                'Status' => $this->jobStatus($buyerCounterOffer->status),
            ];
            $payment_details = [
                'milestones' => $milestones->toArray(),
                'receive_payment' => $totalBudget,
                'total_budget' => $totalBudget,
            ];
        }

        // Passing data to the view
        return view('templates.basic.user.seller.workroom_list', compact(
            'status',
            'bonusAmount',
            'pageTitle',
            'job_type',
            'jobTitle',
            'jobDescription',
            'seller',
            'startDate',
            'totalBudget',
            'pendingPayment',
            'paidAmount',
            'remainingAmount',
            'encUserBidingId',
            'encJobId',
            'commissionAmount',
            'buyer',
            'isCompleted',
            'payment_details',
            'contract_details',
            'req_type',
            'platform_fee'
        ));
    }

    // old code before aug
    //  private function calculateBudgetAndPayments($acceptedBid)
    // {
    //     $totalBudget = 0;
    //     $pendingPayment = 0;
    //     $paidAmount = 0;
    //     $remainingAmount = 0;

    //     $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
    //     $totalBudget = $offerData->sum('amount');
    //     $booking = $acceptedBid->job->booking;

    //     if ($booking) {
    //         // Fetch transactions related to the booking
    //         $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->get();

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
    //             $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount');
    //         }
    //     }

    //     $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //     $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //     // $pendingPayment = max(0, $totalBudget - $paidAmount);
    //     // $remainingAmount = max(0, $totalBudget - $paidAmount);

    //     return [$totalBudget, $pendingPayment, $nonBonusPaidAmount,$bonusAmount, $remainingAmount,$commissionAmount];
    // }

    // 11 sept code
    // private function calculateBudgetAndPayments($acceptedBid)
    // {
    //     $totalBudget = 0;
    //     $pendingPayment = 0;
    //     $paidAmount = 0;
    //     $remainingAmount = 0;
    //     $commissionAmount = 0;
    //     $bonusAmount = 0;
    //     $nonBonusPaidAmount = 0;
    //     $days = 0;

    //     $budget_type = Job::find($acceptedBid->job_id)->budgettype;
    //     $offerData = BuyerCounterOfferData::where('job_id', $acceptedBid->job_id)->where('status', 1)->get();

    //     if ($budget_type == "hourly") {
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->first();
    //         $totalBudget = $offerData->amount;
    //         $days = $offerData->days;
    //     } else {
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->first();
    //         $totalBudget = $offerData->amount;
    //         $days = 0;
    //     }
    //     // $totalBudget = $offerData->sum('amount');
    //     $booking = $acceptedBid->job->booking;
    //     if ($booking) {
    //         // Fetch transactions related to the booking
    //         $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->get();
    //         // Calculate paid amount
    //         if ($transactions->isNotEmpty()) {
    //             $regularTransactions = $transactions->filter(function ($transaction) {
    //                 return strpos($transaction->details, 'Platform commission') === false;
    //             });

    //             $commissionTransactions = $transactions->filter(function ($transaction) {
    //                 return strpos($transaction->details, 'Platform commission') !== false;
    //             });

    //             $paidAmount = $transactions->sum('amount'); // Sum transaction amounts

    //             $bonusAmount = $transactions->where('is_bonus', 1)->sum('amount');
    //             $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount');

    //             // Calculate commission amount
    //             $commissionAmount = $commissionTransactions->sum('amount');
    //         }
    //     }
    //     if ($budget_type == "hourly") {
    //         $pendingPayment = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //     }else {
    //         $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //     }

    //     return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount, $commissionAmount];
    // }

    // 7 Oct
    private function calculateBudgetAndPayments($acceptedBid)
    {
        $totalBudget = 0;
        $pendingPayment = 0;
        $remainingAmount = 0;
        $bonusAmount = 0;
        $nonBonusPaidAmount = 0;
        $commissionAmount = 0;
        $grossNonBonusPaid = 0;
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
            $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('requested_milestone_status', 1)->whereNull('req_type')->get();
            $totalBudget = $offerData->sum('amount');
            $days = $offerData->sum('days');
        }

        $booking = $acceptedBid->job->booking;
        if ($booking) {
            $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->get();

            if ($transactions->isNotEmpty()) {
                $regularTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') === false;
                });

                $commissionTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') !== false;
                });

                //27 june
                $platformCommissionTransactions = $transactions->filter(function ($transaction) {
                    return str_contains($transaction->details, 'Platform Fee') ||
                        str_contains($transaction->details, 'Platform commission');
                });

                $commissionAmount = $platformCommissionTransactions->sum('amount');

                $bonusAmount = $regularTransactions->where('is_bonus', 1)->sum('amount') - $regularTransactions->where('is_bonus', 1)->sum('charge');

                $nonBonusPaidAmount = $regularTransactions->where('is_bonus', 0)->sum('amount');

                $grossNonBonusPaid = $regularTransactions->where('is_bonus', 0)->sum('amount') + $regularTransactions->where('is_bonus', 0)->sum('charge');
            }
        }

        if ($budget_type == 'hourly') {
            $pendingPayment = max(0, $totalBudget * $days - $grossNonBonusPaid);
            $remainingAmount = max(0, $totalBudget * $days - $grossNonBonusPaid);
        } elseif ($budget_type == 'byproject') {
            $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
            $totalBudget = $buyerCounterOfferData->sum('amount');
            $pendingPayment = max(0, $totalBudget - $grossNonBonusPaid);
            $remainingAmount = max(0, $totalBudget - $grossNonBonusPaid);
        } elseif ($budget_type == 'bymilestone') {
            $buyerCounterOfferData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
            $totalBudget = $buyerCounterOfferData->sum('amount');
            $pendingPayment = max(0, $totalBudget - $grossNonBonusPaid);
            $remainingAmount = max(0, $totalBudget - $grossNonBonusPaid);
        }
        return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount, $commissionAmount];
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
    //     $nonBonusPaidAmount = 0;
    //     $days = 0;

    //     $budget_type = Job::find($acceptedBid->job_id)->budgettype;
    //     if ($budget_type == "hourly") {
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->first();
    //         $totalBudget = $offerData->amount;
    //         $days = $offerData->days;
    //     } else {
    //         $offerData = Buyercounterofferdata::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
    //         $totalBudget = $offerData->sum('amount');
    //         $days = 0;
    //     }
    //     $booking = $acceptedBid->job->booking;

    //     if ($booking) {
    //         // Fetch transactions related to the booking
    //         $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->get();

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
    //             $nonBonusPaidAmount = $transactions->where('is_bonus', 0)->sum('amount');
    //         }
    //     }

    //     // $pendingPayment = max(0, $totalBudget - $paidAmount);
    //     // $remainingAmount = max(0, $totalBudget - $paidAmount);
    //     if ($budget_type == "hourly") {
    //         $pendingPayment = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget * $days - $nonBonusPaidAmount);
    //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //     } else {
    //         $pendingPayment = max(0, $totalBudget - $nonBonusPaidAmount);
    //         $remainingAmount = max(0, $totalBudget - $nonBonusPaidAmount);
    //         return [$totalBudget, $pendingPayment, $nonBonusPaidAmount, $bonusAmount, $remainingAmount];
    //     }
    // }

    // Common functionality to calculate total payments
    private function calculatePayments($userbiding)
    {
        $totalPayments = $userbiding->projectPayments()->sum('payment'); // Using the relationship
        $remainingPayment = $userbiding->bid_price ? $userbiding->bid_price - $totalPayments : 0;

        return [$totalPayments, $remainingPayment];
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
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('seller_id', $user->id)->first();
        $job = Job::find($jobId);
        $isCompleted = $job->is_completed;
        $buyer = User::find($acceptedBid->buyer_id);
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller; // Using the relationship
        if (! $seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        $buyerCounterOfferDataId = $buyerCounterOffer->offerdata->id;
        // Prepare data for the view
        // $userBidding = Userbiding::where('id', $userBidding)
        //     ->where('seller_offer_responce', 2)
        //     ->with(['buyerCounterOffer' => function ($query) {
        //         $query->where('status',1); // Fetch only records with NULL status
        //     }])
        //     ->first();

        // $buyerCounterOfferDataId = $userBidding->buyerCounterOffer->offerdata->id;
        // $requestDescription = $userBidding->buyerCounterOffer->request_description;

        $encBuyerCounterOfferDataId = encrypt($buyerCounterOfferDataId);
        $date = $request->query('date', now()->format('Y-m-d'));
        // Fetch all job work delivery data related to this buyerCounterOfferDataId, filtered by the date
        $jobWorkBids = Jobworkdeliveryhourlybid::whereIn('status', [0, 1, 6])->whereHas('intervals', function ($query) {
            $query->whereIn('status', [1, 5, 6, 3]); // Ensure at least one interval has status 1, 2, or 3
        })->with(['intervals' => function ($query) {
            $query->whereIn('status', [1, 5, 6, 3]); // Load only intervals with status 1, 2, or 3
        }])->where('data_id', $buyerCounterOfferDataId)->whereDate('date_start', $date)->get();

        $counterOfferData = DB::table('buyer_counter_offers_data')
            ->where('id', $buyerCounterOfferDataId)
            ->select('counter_offer_id')
            ->first();

        if (! $counterOfferData) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        // Fetch allow_manual_time from buyer_counter_offers using the counter_offer_id
        $buyerCounterOffer = DB::table('buyer_counter_offers')
            ->where('id', $counterOfferData->counter_offer_id)
            ->first();
        $allowManualTime = $buyerCounterOffer->allow_manual_time;
        $isAllowedManualTime = $buyerCounterOffer->is_manual_log_accepted;

        // Pre-select start and end times
        $currentTime = now();
        $currentMinute = $currentTime->minute;
        $currentHour = $currentTime->hour;

        // Nearest lower 10-minute interval
        $startMinute = floor($currentMinute / 10) * 10;
        $startHour = $currentHour;

        if ($startMinute == 60) {
            $startMinute = 0;
            $startHour++;
        }

        $startTime = sprintf('%02d:%02d', $startHour, $startMinute);

        // Next 10-minute interval
        $endMinute = $startMinute + 10;
        $endHour = $startHour;

        if ($endMinute == 60) {
            $endMinute = 0;
            $endHour++;
        }

        $endTime = sprintf('%02d:%02d', $endHour, $endMinute);
        $totalTime = ['hours' => 0, 'minutes' => 0, 'cost' => 0];
        $manualTime = ['hours' => 0, 'minutes' => 0, 'cost' => 0];
        $autoTrackedTime = ['hours' => 0, 'minutes' => 0, 'cost' => 0];
        // Iterate over each job work bid
        foreach ($jobWorkBids as $bid) {
            $hourlyBlocks = [];

            // Group intervals by hour
            $intervalsGroupedByHour = $bid->intervals->groupBy(function ($interval) {
                $intervalStartTime = \Carbon\Carbon::createFromFormat('H:i:s', $interval->interval_start_time);

                // Ensure correct grouping for :00 intervals
                return $intervalStartTime->format('g A');
            });

            $totalTime['hours'] += $bid->time;
            $totalTime['minutes'] += $bid->minutes;
            $totalTime['cost'] += $bid->total_cost;
            $firstIntervalType = $bid->intervals->first()->is_manual;
            if ($firstIntervalType == 1) {
                $manualTime['hours'] += $bid->time;
                $manualTime['minutes'] += $bid->minutes;
                $manualTime['cost'] += $bid->total_cost;
            }
            if ($firstIntervalType == 0) {
                $autoTrackedTime['hours'] += $bid->time;
                $autoTrackedTime['minutes'] += $bid->minutes;
                $autoTrackedTime['cost'] += $bid->total_cost;
            }

            foreach ($intervalsGroupedByHour as $hour => $intervalsForHour) {

                $hourStart = \Carbon\Carbon::createFromFormat('g A', $hour);
                $formattedHour = $hourStart->format('g A');
                $intervals = [];

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
                        $activityLevel = $matchingInterval->activity_level;
                        $filteredActivityLogs = $activityLogs->filter(function ($log) {
                            return $log->keyboard_activity > 0 || $log->mouse_activity > 0;
                        });
                        // Add the interval with data
                        $intervals[] = [
                            'id' => $matchingInterval->id,
                            'time' => $intervalTime->format('g:i A'),
                            'screen_shot' => $matchingInterval->screenshot_path,
                            'is_manual' => $matchingInterval->is_manual,
                            'has_data' => true,
                            'activity_level' => $activityLevel,
                            'mouse_clicks' => $actualMouseClicks,
                            'keystrokes' => $actualKeystrokes,
                            'status' => $matchingInterval->status,
                            'countActivityLogs' => count($filteredActivityLogs),
                            'activities' => $activityLogs->map(function ($log) {
                                return [
                                    'time' => \Carbon\Carbon::parse($log->log_time)->format('h:i:s A'),
                                    'mouse_clicks' => $log->mouse_activity,
                                    'keystrokes' => $log->keyboard_activity,
                                ];
                            }),
                        ];
                    } else {
                        // No data for this interval, mark has_data as false
                        $intervals[] = [
                            'time' => $intervalTime->format('g:i A'),
                            'has_data' => false,
                        ];
                    }
                }

                if (count($intervals) > 0) {
                    $hourlyBlocks[] = [
                        'hour' => $formattedHour,
                        'intervals' => $intervals,
                    ];
                }
            }
            $bid->hourlyBlocks = $hourlyBlocks;
        }
        $totalTime['hours'] += floor($totalTime['minutes'] / 60);
        $totalTime['minutes'] = $totalTime['minutes'] % 60;

        // $manualTime['hours'] += floor($manualTime['minutes'] / 60);
        // $manualTime['minutes'] = $manualTime['minutes'] % 60;

        // $autoTrackedTime['hours'] += floor($autoTrackedTime['minutes'] / 60);
        // $autoTrackedTime['minutes'] = $autoTrackedTime['minutes'] % 60;

        $pageTitle = 'Timesheet';
        $jobTitle = $acceptedBid->job->title;
        $jobDescription = $acceptedBid->job->description;
        $job_type = $acceptedBid->job_type;
        $timezone = $request->query('timezone', 'UTC');

        $loggedIntervals = []; // To store already logged intervals
        foreach ($jobWorkBids as $bid) {
            foreach ($bid->intervals as $interval) {
                $loggedIntervals[] = [
                    'start' => \Carbon\Carbon::createFromFormat('H:i:s', $interval->interval_start_time)->format('H:i'),
                    'end' => \Carbon\Carbon::createFromFormat('H:i:s', $interval->interval_end_time)->format('H:i'),
                ];
            }
        }
        $timeIntervals = getTimeIntervals($request->query('timezone', 'UTC'), $loggedIntervals);
        $encJobId = encrypt($jobId);
        if ($request->ajax()) {
            return view('seller.workspace.timesheet', compact(
                'encJobId',
                'jobWorkBids',
                'buyer',
                // 'encUserBidingId',
                'encBuyerCounterOfferDataId',
                'date',
                'allowManualTime',
                'startTime',
                'endTime',
                'timezone',
                'timeIntervals',
                // 'requestDescription',
                'totalTime',
                'manualTime',
                'autoTrackedTime',
                'isCompleted',
                'isAllowedManualTime'
            ));
        }

        return view('seller.workspace.full_timesheet', compact(
            'pageTitle',
            'jobWorkBids',
            'buyer',
            // 'encUserBidingId',
            'encBuyerCounterOfferDataId',
            'date',
            'allowManualTime',
            'jobTitle',
            'jobDescription',
            'job_type',
            'startTime',
            'endTime',
            'timezone',
            'timeIntervals',
            'encJobId',
            // 'requestDescription',
            'totalTime',
            'manualTime',
            'autoTrackedTime',
            'isCompleted',
            'isAllowedManualTime'
        ));
    }

    // public function workroomProject(Request $request, $encryptedId)
    // {
    //     $jobId = Crypt::decrypt($encryptedId);
    //     $user = Auth::user();
    //     $acceptedBid = Acceptedbid::where('job_id',$jobId)->where('seller_id', $user->id)->first();
    //     if (!$acceptedBid) {
    //         return redirect()->back()->with('error', 'Bidding not found.');
    //     }
    //     $seller = $acceptedBid->seller; // Using the relationship
    //     if (!$seller) {
    //         return redirect()->back()->with('error', 'Seller not found.');
    //     }
    //     $pageTitle = "By Project";
    //     // Fetch the user bidding with all necessary relationships
    //     $job = $acceptedBid->job;
    //     $encJobId = encrypt($job->id);

    //     $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
    //     $buyerCounterOfferDataId = $buyerCounterOffer->offerdata->id;

    //     // Loop through each `buyerCounterOfferData` entry if they exist
    //     foreach ($buyerCounterOffer->buyerCounterOfferData as $offerData) {
    //         $offerId = $offerData->id;
    //         $disputeId  = \DB::table('job_dispute_bid')->where('offer_id', $offerId)->value('id');

    //         // Format the due date
    //         $formattedDueDate = \Carbon\Carbon::parse($offerData->due_date)->format('d-m-Y');

    //         // Collect data for each buyer counter offer data entry
    //         $buyerCounterOffersData[] = [
    //             "amount" => $offerData->amount,
    //             "due_date" => $formattedDueDate,
    //             "milestoneStatusText" => $offerData->getMilestoneStatusLabel(),
    //             "buyerCounterOfferDataId" => $offerData->id,
    //             "encId" => encrypt($offerData->id),
    //             "revision" => $offerData->revisions,
    //             "milestoneStatus" => $offerData->milestone_status,
    //             "disputeId" => encrypt($disputeId),
    //             "requestType" => $offerData->req_type,
    //             "title" => $offerData->title ?? "Project Payment",
    //             "milestone_status" => $offerData->milestone_status,
    //         ];

    //         if($offerData->milestone_status == 6)
    //             $buyerCounterOffersData["jobDisputeBidStatus"] = $offerData->jobDisputeBid->pluck('status')->first() ;
    //     }
    //     $jobTitle = $job->title;
    //     $jobDescription = $job->description;
    //     $job_type =  $buyerCounterOffer->type;
    //     $isCompleted = $job->show_completed;

    //     if ($request->ajax()) {
    //         return view('seller.workspace.byproject', compact('buyerCounterOffersData', 'encJobId', 'pageTitle','isCompleted'))->render();
    //     }
    //     return view('seller.workspace.full_byproject', compact('buyerCounterOffersData', 'encJobId', 'pageTitle', 'jobTitle', 'jobDescription', 'job_type','isCompleted'));
    // }

    public function workroomProject(Request $request, $encryptedId)
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
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('seller_id', $user->id)->first();
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        $seller = $acceptedBid->seller; // Using the relationship
        if (! $seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }
        $pageTitle = 'By Project';
        // Fetch the user bidding with all necessary relationships
        $job = $acceptedBid->job;
        $encJobId = encrypt($job->id);

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        $buyerCounterOfferDataId = $buyerCounterOffer->offerdata->id;

        // Loop through each `buyerCounterOfferData` entry if they exist
        foreach ($buyerCounterOffer->buyerCounterOfferData as $offerData) {
            $offerId = $offerData->id;
            $disputeId = \DB::table('job_dispute_bid')->where('offer_id', $offerId)->value('id');

            // Format the due date
            $formattedDueDate = \Carbon\Carbon::parse($offerData->due_date)->format('d-m-Y');

            // Collect data for each buyer counter offer data entry
            $buyerCounterOffersData[] = [
                'id'=>$offerData->id,
                'amount' => $offerData->amount,
                'due_date' => $formattedDueDate,
                'milestoneStatusText' => $offerData->getMilestoneStatusLabel(),
                'buyerCounterOfferDataId' => $offerData->id,
                'encId' => encrypt($offerData->id),
                'revision' => $offerData->revisions,
                'milestoneStatus' => $offerData->milestone_status,
                'disputeId' => encrypt($disputeId),
                'requestType' => $offerData->req_type,
                'title' => $offerData->title ?? 'Project Payment',
                'milestone_status' => $offerData->milestone_status,
                'jobDisputeBidStatus' => $offerData->milestone_status == 6 ?
                    $offerData->jobDisputeBid->pluck('status')->first() : null,
            ];

            // if($offerData->milestone_status == 6)
            //     $buyerCounterOffersData["jobDisputeBidStatus"] = $offerData->jobDisputeBid->pluck('status')->first() ;
        }
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $job_type = $buyerCounterOffer->type;
        $isCompleted = $job->show_completed;
        $buyer = User::find($job->user_id);

        if ($request->ajax()) {
            return view('seller.workspace.byproject', compact('buyerCounterOffersData', 'encJobId', 'pageTitle', 'isCompleted'))->render();
        }

        return view('seller.workspace.full_byproject', compact('buyerCounterOffersData', 'buyer', 'encJobId', 'pageTitle', 'jobTitle', 'jobDescription', 'job_type', 'isCompleted'));
    }

    public function workroomMilestone(Request $request, $encryptedId)
    {
        $user = Auth::user();
        $jobId = Crypt::decrypt($encryptedId);
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('seller_id', $user->id)->first();
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        $seller = $acceptedBid->seller; // Using the relationship
        if (! $seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }
        $pageTitle = 'By Project';
        $job = $acceptedBid->job;
        $buyer = User::find($job->user_id);

        $encJobId = encrypt($job->id);

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        $milestones = [];

        foreach ($buyerCounterOffer->buyerCounterOfferData as $dataRow) {
            $disputeId = \DB::table('job_dispute_bid')->where('offer_id', $dataRow->id)->value('id');
            $milestone = [
                'title' => $dataRow->title,
                'id' => $dataRow->id,
                'encId' => encrypt($dataRow->id),
                'amount' => $dataRow->amount,
                'days' => $dataRow->days,
                'revision' => $dataRow->revisions,
                'milestoneStatus' => $dataRow->milestone_status,
                'due_date' => \Carbon\Carbon::parse($dataRow->due_date)->format('d-m-Y'),
                'milestoneStatusText' => $dataRow->getMilestoneStatusLabel(),
                'counterOfferId' => $dataRow->counter_offer_id,
                'disputeId' => encrypt($disputeId),
                'requestType' => $dataRow->req_type,
            ];
            if ($milestone['milestoneStatus'] == 1 || $milestone['milestoneStatus'] == 2 || $milestone['milestoneStatus'] == 6) {
                // Fetch job work delivery bid based on data_id and title
                $jobWorkDeliveryBid = Jobworkdeliverybid::where('data_id', $dataRow->id)->first();

                // Attach additional details if found
                if ($jobWorkDeliveryBid) {
                    $milestone['jobWorkDeliveryBidId'] = $jobWorkDeliveryBid->id;
                }
            }

            $milestones[] = $milestone;
        }
        $data = [
            'milestones' => $milestones,
        ];

        $pageTitle = 'Milestone';
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $job_type = $buyerCounterOffer->type;
        $isCompleted = $job->show_completed;
        if ($request->ajax()) {
            return view('seller.workspace.bymilestone', compact('data', 'encJobId', 'isCompleted'));
        }

        return view('seller.workspace.full_bymilestone', compact('data', 'encJobId', 'buyer', 'jobTitle', 'jobDescription', 'job_type', 'pageTitle', 'isCompleted'));
    }

    public function deleteIntervals(Request $request)
    {
        $request->validate([
            'interval_ids' => 'array',
            'interval_ids.*' => 'exists:job_work_intervals,id',
            'manual_delivery_hour_ids' => 'array',
            'manual_delivery_hour_ids.*' => 'exists:job_work_delivery_houly_bid,id',
        ]);

        // Get the start and end of the current week
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY); // Monday 12:00 AM
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);   // Sunday 11:59:59 PM

        \DB::beginTransaction();

        try {
            // Handle manual delivery hour deletion
            if ($request->filled('manual_delivery_hour_ids') && ! $request->filled('interval_ids')) {
                $manualDeliveryHourIds = $request->input('manual_delivery_hour_ids');

                // Ensure manual delivery hour IDs are within the current week
                $manualDeliveryHours = Jobworkdeliveryhourlybid::whereIn('id', $manualDeliveryHourIds)
                    ->whereBetween('date_start', [$startOfWeek, $endOfWeek])
                    ->get();

                if ($manualDeliveryHours->isEmpty()) {
                    return response()->json(['message' => 'No valid manual intervals found within the current week.'], 404);
                }

                // Update the status of manual delivery hours and their intervals
                Jobworkdeliveryhourlybid::whereIn('id', $manualDeliveryHours->pluck('id'))
                    ->update(['status' => 2]);

                JobworkIntervalEntry::whereIn('hourly_bid_id', $request->input('interval_ids'))
                    ->update(['status' => 2]);
            }

            // Handle interval deletion
            if ($request->filled('interval_ids')) {

                $intervalIds = $request->input('interval_ids');

                // Ensure intervals are within the current week
                $intervals = JobworkIntervalEntry::whereIn('id', $intervalIds)
                    ->whereBetween('interval_start_time', [$startOfWeek, $endOfWeek])
                    ->get();

                if ($intervals->isEmpty()) {
                    return response()->json(['message' => 'No valid intervals found within the current week.'], 404);
                }

                foreach ($intervals as $interval) {
                    $hourlyBid = $interval->hourlyBid; // Relation to Jobworkdeliveryhourlybid
                    if ($hourlyBid) {
                        $hourlyBid->total_cost -= $interval->cost;
                        $hourlyBid->time = max(0, $hourlyBid->time - floor($interval->activityLogs()->count() / 60));
                        $hourlyBid->minutes = max(0, $hourlyBid->minutes - ($interval->activityLogs()->count() % 60));
                        $hourlyBid->save();
                    }
                }

                // Mark intervals as deleted
                JobworkIntervalEntry::whereIn('id', $intervals->pluck('id'))->update(['status' => 2]);
            }

            \DB::commit();

            return response()->json(['message' => 'Selected intervals and manual entries have been marked as deleted successfully.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error in deleteIntervals: '.$e->getMessage());

            return response()->json(['message' => 'An error occurred while deleting intervals.'], 500);
        }
    }

    public function TimesheetAdd(Request $request, $encryptedId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $userbidingId = Crypt::decrypt($encryptedId);
        $userbiding = Userbiding::find($userbidingId, ['job_type', 'job_id']);
        $job = Job::find($userbiding->job_id, ['budgettype', 'amount']);

        $pageTitle = 'Add Timesheet';
        $jobBudget = $job->budgettype;
        $jobAmount = $job->amount;

        return view('templates.basic.user.seller.workroom.addtimesheet', compact('pageTitle', 'jobBudget', 'jobAmount'));
    }

    public function workroomChat(Request $request, $encryptedId)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        $userbidingId = Crypt::decrypt($encryptedId);
        $userbiding = Userbiding::where('id', $userbidingId)->first(['user_id', 'job_id']);
        $job = Job::where('id', $userbiding->job_id)->first(['user_id']);
        $buyeruserId = $job->user_id;
        $selleruserId = $userbiding->user_id;

        // Redirect to the chat route with the buyer and seller IDs
        return redirect()->route('chatify', ['buyer' => $buyeruserId, 'seller' => $selleruserId]);
    }

    public function paymentRequest(Request $request)
    {
        // Validate the request data
        // $validator = Validator::make($request->all(), [
        //     'title.*' => 'required|string',
        //     'amount.*' => 'required|numeric',
        //     'due_date.*' => 'required|date',
        //     'bid_id' => 'required|string',
        // ]);

        $data = $request->all();
        $request->validate([
            'title.*' => 'required|string',
            'amount.*' => 'required|numeric',
            'due_date.*' => 'required|date',
            'bid_id' => 'required|string',
        ]);
        $encryptedBidId = Crypt::decrypt($data['bid_id']);
        $milestones = [];
        $titles = $data['title'];
        $amounts = $data['amount'];
        $dueDates = $data['due_date'];

        for ($i = 0; $i < count($titles); $i++) {
            $milestones[] = [
                'bid_id' => $encryptedBidId,
                'title' => $titles[$i],
                'amount' => $amounts[$i],
                'due_date' => $dueDates[$i],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        UserMilestone::insert($milestones);

        $notify[] = ['success', 'milestone added successfully'];

        return redirect('/user/seller/job/workroom/'.$data['bid_id'])->withNotify($notify);
    }

    public function addTimesheet(Request $request)
    {
        $data = $request->all();

        $encryptedBidId = Crypt::decrypt($data['bid_id']);
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'hours' => 'required|string|max:255',
                'minutes' => 'required|string|max:255',
                'coverletter' => 'required|string|max:255',
                'bid_id' => 'required|string',
            ]);
            $userTimesheet = new UserTimesheet;
            $userTimesheet->title = $data['title'];
            $userTimesheet->start_date = $data['start_date'];
            $userTimesheet->end_date = $data['end_date'];
            $userTimesheet->hours = $data['hours'];
            $userTimesheet->minutes = $data['minutes'];
            $userTimesheet->coverletter = $data['coverletter'];
            $userTimesheet->bid_id = $encryptedBidId;
            $userTimesheet->save();
            $notify[] = ['success', 'timesheet added successfully'];

            return redirect('/user/seller/job/workroom/'.$data['bid_id'])->withNotify($notify);
        } catch (\Exception $e) {

            Log::error('Error adding timesheet: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while adding the timesheet.');
        }
    }

    public function getActiveHourlyJobs(Request $request)
    {
        $user = Auth::user();

        // Fetch current jobs based on specified criteria
        $jobs = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')
            ->where('user_biding.user_id', $user->id)
            ->where('jobs.budgettype', 'hourly') // Filter for hourly jobs
            ->where('user_biding.seller_offer_responce', 2) // Filter for seller_offer_response = 2
            ->select('user_biding.*', 'jobs.id', 'jobs.title', 'jobs.description', 'jobs.weekly_hours') // Select relevant fields
            ->orderby('user_biding.id', 'desc')
            ->get();

        if ($jobs->isEmpty()) {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'No jobs found',
                'user_name' => $user->fullname,
                'link' => env('APP_URL').'/chat',
            ], 200);
        }

        // Prepare the jobs data for rendering
        $jobsData = [];
        foreach ($jobs as $job) {
            // Fetch work hours from job_work_delivery_hourly_bid table
            $hourlyBids = Jobworkdeliveryhourlybid::where('job_id', $job->job_id)

                ->select('time', 'minutes') // Fetch time and minutes directly
                ->get();

            // Initialize total hours and minutes variables
            $totalHours = 0;
            $totalMinutes = 0;

            // Aggregate the hours and minutes from hourly bids
            foreach ($hourlyBids as $bid) {
                $totalHours += $bid->time; // Assuming `time` is in hours
                $totalMinutes += $bid->minutes; // Assuming `minutes` is in minutes
            }
            if ($totalMinutes >= 60) {
                $additionalHours = intdiv($totalMinutes, 60); // Get additional full hours
                $remainingMinutes = $totalMinutes % 60; // Get remaining minutes after converting to hours
                $totalHours += $additionalHours; // Add the extra hours
                $totalMinutes = $remainingMinutes; // Update minutes
            }
            // Prepare the response data
            $jobsData[] = [
                'job_id' => $job->id,
                'hoursWorked' => $totalHours, // Total hours from the fetched data
                'minutesWorked' => $totalMinutes, // Total minutes from the fetched data
                'progress' => 0, // Set progress as 0, as no calculations are made
                'totalHours' => $job->weekly_hours,
                'title' => $job->title,
                'description' => Str::words($job->description, 50, '...'),
            ];
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Current jobs fetched successfully',
            'data' => $jobsData,
            'user_name' => $user->username,
            'link' => env('APP_URL').'/chat',
        ], 200);
    }

    public function getActiveHourlyJobDetails($jobId)
    {
        $user = Auth::user();

        // Fetch the job details along with hourly bids and memo
        $job = Userbiding::join('jobs', 'user_biding.job_id', '=', 'jobs.id')
            ->leftJoin('job_work_delivery_houly_bid as bids', 'jobs.id', '=', 'bids.job_id')
            ->where('user_biding.user_id', $user->id)
            ->where('jobs.id', $jobId)
            ->where('jobs.budgettype', 'hourly')
            ->where('user_biding.seller_offer_responce', 2)
            ->select(
                'user_biding.*',
                'jobs.title',
                'jobs.description',
                'jobs.weekly_hours'
            )
            ->first();

        // Check if the job exists
        if (! $job) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Job not found',
            ], 404);
        }

        $today = Carbon::now('UTC')->startOfDay();
        $weekStart = Carbon::now('UTC')->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek();

        // Aggregate daily and weekly time logs
        $timeLogs = Jobworkdeliveryhourlybid::where('job_id', $job->job_id)
            ->selectRaw('
            SUM(CASE WHEN created_at >= ? THEN time ELSE 0 END) as daily_hours,
            SUM(CASE WHEN created_at >= ? THEN minutes ELSE 0 END) as daily_minutes,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN time ELSE 0 END) as weekly_hours,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN minutes ELSE 0 END) as weekly_minutes
        ', [$today, $today, $weekStart, $weekEnd, $weekStart, $weekEnd])
            ->first();

        // Handle daily hours and minutes
        $dailyHours = $timeLogs->daily_hours ?? 0;
        $dailyMinutes = $timeLogs->daily_minutes ?? 0;
        $dailyHours += floor($dailyMinutes / 60);
        $dailyMinutes = $dailyMinutes % 60;
        $formattedDailyHours = sprintf('%02d:%02d', $dailyHours, $dailyMinutes);

        // Handle weekly hours and minutes
        $weeklyHours = $timeLogs->weekly_hours ?? 0;
        $weeklyMinutes = $timeLogs->weekly_minutes ?? 0;
        $weeklyHours += floor($weeklyMinutes / 60);
        $weeklyMinutes = $weeklyMinutes % 60;
        $formattedWeeklyHours = sprintf('%02d:%02d', $weeklyHours, $weeklyMinutes);

        // Fetch the most recent screenshot and time since its creation
        $recentScreenshot = JobworkIntervalEntry::whereIn('hourly_bid_id', Jobworkdeliveryhourlybid::where('job_id', $job->job_id)->pluck('id'))
            ->whereNotNull('screenshot_path')
            ->latest('created_at')
            ->first();

        $screenshotPath = $recentScreenshot ? url('storage/screenshots/'.basename($recentScreenshot->screenshot_path)) : null;
        $timeElapsed = $recentScreenshot ? Carbon::parse($recentScreenshot->created_at)->diffForHumans(Carbon::now('UTC')) : 'No screenshot available';

        // Calculate progress percentage
        $totalWeeklyMinutes = $job->weekly_hours * 60;
        $loggedWeeklyMinutes = ($weeklyHours * 60) + $weeklyMinutes;
        $progressPercentage = $totalWeeklyMinutes > 0 ? ($loggedWeeklyMinutes / $totalWeeklyMinutes) * 100 : 0;
        $progressPercentage = round(min(max($progressPercentage, 0), 100)); // Clamp between 0 and 100

        // Prepare response
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Job details fetched successfully',
            'data' => [
                'job_id' => $job->id,
                'title' => $job->title,
                // 'description' => $job->description,
                'hoursWorkedToday' => $formattedDailyHours,
                'hoursWorkedThisWeek' => $formattedWeeklyHours,
                'progress' => $progressPercentage,
                'totalWeeklyHours' => $job->weekly_hours,
                'currentDay' => Carbon::now('UTC')->format('l'),
                'recentScreenshotPath' => $screenshotPath,
                'timeSinceScreenshot' => $timeElapsed,
                'timesheetUrl' => route('user.seller.workroom', ['encryptedId' => encrypt($job->job_id)]),
                // 'memo' => $job->memo,
            ],
        ], 200);
    }

    // public function startTracking(Request $request)
    // {
    //     Log::info("startTracking API called with data: ", $request->all());

    //     // Validate the request
    //     $validator = \Validator::make($request->all(), [
    //         'job_id' => 'required|exists:jobs,id',
    //         'timezone' => 'required|in:UTC,local', // Validate time zone
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error("Validation failed: ", $validator->errors()->toArray());
    //         return response()->json(['code' => 422, 'message' => $validator->errors()], 422);
    //     }

    //     $jobId = $request->job_id;
    //     $timezoneFlag =  1;
    //     $currentTimeUTC = now();

    //     Log::info("Current time (UTC): " . $currentTimeUTC);

    //     // Round time to the nearest 10-minute mark before the current time
    //     $roundedStartTime = $currentTimeUTC->copy()->minute(
    //         floor($currentTimeUTC->minute / 10) * 10
    //     )->second(0);

    //     Log::info("Rounded start time to the nearest 10-minute mark: " . $roundedStartTime);
    //     $dateStart = $roundedStartTime->toDateString();
    //     // Round the hour to match the rounded start time
    //     $currentHour = $roundedStartTime->copy()->startOfHour();
    //     Log::info("Rounded hour (start of the hour based on rounded time): " . $currentHour);

    //     // Get start of current week (Monday)
    //     $startOfWeek = $currentTimeUTC->copy()->startOfWeek();
    //     Log::info("Start of current week (Monday): " . $startOfWeek);

    //     // Fetch offer data to check weekly allowed hours
    //     $offerData = \DB::table('buyer_counter_offers_data')
    //         ->where('status', 1)
    //         ->where('job_id', $jobId)
    //         ->first();

    //     if (!$offerData) {
    //         Log::error("No valid offer data found for job_id: $jobId");
    //         return response()->json(['code' => 404, 'message' => 'No valid offer data found for this job.'], 404);
    //     }

    //     // Calculate weekly allowed hours in seconds
    //     $weeklyAllowedSeconds = $offerData->days * 3600; // Convert days to seconds

    //     // Calculate total elapsed time for the job THIS WEEK ONLY
    //     $totalElapsedTime = \DB::table('job_work_delivery_houly_bid')
    //         ->where('job_id', $jobId)
    //         ->where('created_at', '>=', $startOfWeek)
    //         ->sum('elapsed_time_in_seconds');

    //     $hours = floor($totalElapsedTime / 3600);
    //     $minutes = floor(($totalElapsedTime % 3600) / 60);
    //     $seconds = $totalElapsedTime % 60;
    //     Log::info("Weekly elapsed time for job_id $jobId: $totalElapsedTime seconds. Weekly limit: $offerData->days hours");
    //     Log::info(sprintf("Weekly elapsed time for job_id %d: %02d:%02d:%02d", $jobId, $hours, $minutes, $seconds));

    //     if ($totalElapsedTime >= $weeklyAllowedSeconds) {
    //         Log::warning("User has reached the maximum allowed weekly hours for job_id: $jobId");

    //         return response()->json([
    //             'code' => 409,
    //             'status' => 'error',
    //             'message' => "You've reached your maximum allowed hours for this period.",
    //         ], 409);
    //     }
    //     // Check if an hourly bid exists for the same job, date, and hour
    //     $hourlyBid = Jobworkdeliveryhourlybid::where('job_id', $jobId)->whereDate('created_at', now()->toDateString())->where('status', 0)->first();
    //     if (!$hourlyBid) {
    //         Log::info("No existing hourly bid found for job_id: $jobId, creating a new one.");
    //         $hourlyBid = Jobworkdeliveryhourlybid::create([
    //             'seller_id' => $offerData->reciever_id,
    //             'buyer_id' => $offerData->sender_id,
    //             'bid_id' => $offerData->bid_id,
    //             'data_id' => $offerData->id,
    //             'job_id' => $jobId,
    //             'start_time' => $roundedStartTime, // Use the rounded 10-minute start time
    //             'end_time' => null,
    //             'status' => 0, // Active
    //             'timezone' => $timezoneFlag,
    //             'per_hour_rate' => $offerData->amount,
    //             'date_start' => $dateStart,
    //         ]);

    //         Log::info("Created new hourly bid: ", $hourlyBid->toArray());
    //     } else {
    //         Log::info("Reusing existing hourly bid with ID: " . $hourlyBid->id);
    //     }

    //     // Check for manual log conflicts
    //     $manualConflict = \DB::table('job_work_intervals')
    //         ->where('hourly_bid_id', $hourlyBid->id)
    //         ->where('is_manual', 1)
    //         ->where(function ($query) use ($roundedStartTime) {
    //             $query->where('interval_start_time', '<=', $roundedStartTime)
    //                 ->where(function ($subquery) use ($roundedStartTime) {
    //                     $subquery->where('interval_end_time', '>=', $roundedStartTime)
    //                         ->orWhereNull('interval_end_time');
    //                 });
    //         })
    //         ->exists();

    //     if ($manualConflict) {
    //         Log::warning("Manual log conflict detected for hourly_bid_id: " . $hourlyBid->id);
    //         return response()->json([
    //             'code' => 409,
    //             'status' => 'error',
    //             'message' => 'Tracker cannot be started because a manual log exists for the same time period.',
    //         ], 409);
    //     }

    //     return response()->json([
    //         'code' => 200,
    //         'message' => 'Tracking started successfully.',
    //         'data' => [
    //             'job_work_delivery_hourly_bid_id' => $hourlyBid->id,
    //         ],
    //     ]);
    // }

    public function startTracking(Request $request)
    {
        Log::info('startTracking API called with data: ', $request->all());

        $validator = \Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'timezone' => 'required|in:UTC,local',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());

            return response()->json(['code' => 422, 'message' => $validator->errors()], 422);
        }

        $jobId = $request->job_id;
        $timezoneFlag = 1;
        $currentTimeUTC = now();

        Log::info('Current time (UTC): '.$currentTimeUTC);

        // Round time DOWN to the nearest 10-minute mark
        // $roundedStartTime = $currentTimeUTC->copy()->minute(
        //     floor($currentTimeUTC->minute / 10) * 10
        // )->second(0);

        $roundedStartTime = $currentTimeUTC->copy();

        Log::info('Rounded start time: '.$roundedStartTime);

        $dateStart = $roundedStartTime->toDateString();
        $currentHour = $roundedStartTime->copy()->startOfHour();
        $startOfWeek = $currentTimeUTC->copy()->startOfWeek();

        Log::info('Start of current week (Monday): '.$startOfWeek);

        // Fetch offer data
        $offerData = DB::table('buyer_counter_offers_data')
            ->where('status', 1)
            ->where('job_id', $jobId)
            ->first();

        if (! $offerData) {
            Log::error("No valid offer data found for job_id: $jobId");

            return response()->json(['code' => 404, 'message' => 'No valid offer data found for this job.'], 404);
        }

        // FIX: "days" field represents weekly allowed HOURS (not days)
        // Multiply by 3600 to get seconds correctly
        // If your field actually stores days, change to: $offerData->days * 8 * 3600
        $weeklyAllowedSeconds = $offerData->days * 3600;

        // Calculate total elapsed time for this job THIS WEEK
        $totalElapsedTime = DB::table('job_work_delivery_houly_bid')
            ->where('job_id', $jobId)
            ->where('created_at', '>=', $startOfWeek)
            ->sum('elapsed_time_in_seconds');

        $hours = floor($totalElapsedTime / 3600);
        $minutes = floor(($totalElapsedTime % 3600) / 60);
        $seconds = $totalElapsedTime % 60;

        Log::info(sprintf(
            'Weekly elapsed time for job_id %d: %02d:%02d:%02d | Weekly limit: %d seconds',
            $jobId, $hours, $minutes, $seconds, $weeklyAllowedSeconds
        ));

        if ($totalElapsedTime >= $weeklyAllowedSeconds) {
            Log::warning("User has reached maximum weekly hours for job_id: $jobId");

            return response()->json([
                'code' => 409,
                'status' => 'error',
                'message' => "You've reached your maximum allowed hours for this period.",
            ], 409);
        }

        // FIX: Don't reuse old bids from same day — always create a fresh one
        // This prevents old session logs from mixing with new session logs
        // Only reuse if the bid was created within the last 10 minutes (truly same session)
        $tenMinutesAgo = now()->subMinutes(10);
        $hourlyBid = Jobworkdeliveryhourlybid::where('job_id', $jobId)
            ->whereDate('created_at', now()->toDateString())
            ->where('status', 0)
            ->where('created_at', '>=', $tenMinutesAgo)
            ->first();

        if (! $hourlyBid) {
            Log::info("Creating a new hourly bid for job_id: $jobId");
            $hourlyBid = Jobworkdeliveryhourlybid::create([
                'seller_id' => $offerData->reciever_id,
                'buyer_id' => $offerData->sender_id,
                'bid_id' => $offerData->bid_id,
                'data_id' => $offerData->id,
                'job_id' => $jobId,
                'start_time' => $roundedStartTime,
                'end_time' => null,
                'status' => 0,
                'timezone' => $timezoneFlag,
                'per_hour_rate' => $offerData->amount,
                'date_start' => $dateStart,
            ]);
            Log::info('Created new hourly bid: ', $hourlyBid->toArray());
        } else {
            Log::info('Reusing recent hourly bid with ID: '.$hourlyBid->id);
        }

        // Check for manual log conflicts
        $manualConflict = DB::table('job_work_intervals')
            ->where('hourly_bid_id', $hourlyBid->id)
            ->where('is_manual', 1)
            ->where(function ($query) use ($roundedStartTime) {
                $query->where('interval_start_time', '<=', $roundedStartTime)
                    ->where(function ($subquery) use ($roundedStartTime) {
                        $subquery->where('interval_end_time', '>=', $roundedStartTime)
                            ->orWhereNull('interval_end_time');
                    });
            })
            ->exists();

        if ($manualConflict) {
            Log::warning('Manual log conflict for hourly_bid_id: '.$hourlyBid->id);

            return response()->json([
                'code' => 409,
                'status' => 'error',
                'message' => 'Tracker cannot start because a manual log exists for this time period.',
            ], 409);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Tracking started successfully.',
            'data' => [
                'job_work_delivery_hourly_bid_id' => $hourlyBid->id,
            ],
        ]);
    }

    // public function handleAutoTracking(Request $request)
    // {
    //     $validator = \Validator::make($request->all(), [
    //         'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
    //         'logs' => 'required|string', // Expect logs to be a JSON string
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error("Validation failed: ", $validator->errors()->toArray());
    //         return response()->json([
    //             'code' => 422,
    //             'status' => 'error',
    //             'message' => $validator->errors(),
    //         ], 422);
    //     }

    //     // Decode logs JSON
    //     $logs = json_decode($request->logs, true);
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         Log::error("JSON decode error: " . json_last_error_msg());
    //         return response()->json([
    //             'code' => 400,
    //             'status' => 'error',
    //             'message' => 'Invalid JSON format for logs.',
    //         ], 400);
    //     }

    //     $filteredLogs = collect($logs)->map(function ($log) {
    //         unset($log['screenshot']); // Screenshot key remove karein
    //         return $log;
    //     });

    //     Log::info("Decoded logs (without screenshot):", $filteredLogs->toArray());

    //     // Fetch hourly bid and related category
    //     $hourlyBidId = $request->job_work_delivery_hourly_bid_id;
    //     $hourlyBid = Jobworkdeliveryhourlybid::with('job.category')->find($hourlyBidId);
    //     if (!$hourlyBid) {
    //         Log::error("Hourly bid not found for ID: $hourlyBidId");
    //         return response()->json([
    //             'code' => 404,
    //             'status' => 'error',
    //             'message' => 'Hourly bid not found.',
    //         ], 404);
    //     }

    //     $maxMouseClicks = $hourlyBid->job->category->mouse_click_threshold ?? 0;
    //     $maxKeystrokes = $hourlyBid->job->category->keyboard_click_threshold ?? 0;

    //     $groupedLogs = [];

    //     foreach ($logs as $log) {
    //         $time = Carbon::parse($log['time']);

    //         // Find the 10-minute interval (XX:00, XX:10, XX:20, ...)
    //         $roundedMinute = floor(($time->minute - 1) / 10) * 10;  // Shift back by 1 min so 10th min stays in previous group
    //         $groupKey = $time->copy()->minute($roundedMinute)->second(0)->toDateTimeString();

    //         // Group logs under the computed key
    //         if (!isset($groupedLogs[$groupKey])) {
    //             $groupedLogs[$groupKey] = [];
    //         }

    //         $groupedLogs[$groupKey][] = $log;
    //     }
    //     $filteredLogs = collect($groupedLogs)->map(function ($logs) {
    //         return collect($logs)->map(function ($log) {
    //             unset($log['screenshot']); // Har entry me se 'screenshot' hatao
    //             return $log;
    //         })->toArray();
    //     });

    //     Log::info("Grouped logs (without screenshot):", $filteredLogs->toArray());

    //     $elapsedTimeInSeconds = 0;

    //     foreach ($groupedLogs as $intervalTime => $logsForInterval) {
    //         // Calculate rounded start time for new intervals
    //         $intervalStartTime = Carbon::parse($intervalTime);
    //         $startMinute = floor($intervalStartTime->minute / 10) * 10;
    //         Log::info('Interval Time:', ['intervalTime' => $intervalTime]);
    //         $roundedIntervalStartTime = $intervalStartTime->copy()->minute($startMinute)->second(0);
    //         $updatedIntervalStartTime = $roundedIntervalStartTime->copy()->addMinutes(10);
    //         $isSameDay = $hourlyBid->created_at->isSameDay($roundedIntervalStartTime);

    //         Log::info('Comparing Dates:', [
    //             'hourlyBid_created_at' => $hourlyBid->created_at->toDateString(),
    //             'roundedIntervalStartTime' => $roundedIntervalStartTime->toDateString(),
    //             'isSameDay' => $isSameDay ? 'Yes' : 'No'
    //         ]);

    //         if (!$isSameDay) {
    //             Log::info("Date change detected. Creating a new hourly bid.");

    //             $newHourlyBid = $hourlyBid->replicate(); // Copy existing record
    //             $newHourlyBid->created_at = $roundedIntervalStartTime->toDateString();
    //             $newHourlyBid->updated_at = now();
    //             $newHourlyBid->date_start = $roundedIntervalStartTime->toDateString();
    //             $newHourlyBid->elapsed_time_in_seconds = 0;
    //             $newHourlyBid->save();

    //             $hourlyBidId = $newHourlyBid->id;
    //             $hourlyBid = $newHourlyBid;

    //             Log::info("New hourly bid created with ID: " . $newHourlyBid->id);
    //         }

    //         $actualMouseClicks = collect($logsForInterval)->sum('mouseActivity');
    //         $actualKeystrokes = collect($logsForInterval)->sum('keyboardActivity');

    //         // Calculate activity level
    //         $mouseActivity = $maxMouseClicks > 0 ? min(($actualMouseClicks / $maxMouseClicks) * 5, 5) : 0;
    //         $keyboardActivity = $maxKeystrokes > 0 ? min(($actualKeystrokes / $maxKeystrokes) * 5, 5) : 0;
    //         $activityLevel = round($mouseActivity + $keyboardActivity);

    //         $isCounted = $activityLevel >= 6;
    //         // Calculate actual worked minutes and prorated cost
    //         $totalWorkedMinutes = count($logs); // Each log represents 1 minute of work
    //         $hourlyRate = $hourlyBid->per_hour_rate;
    //         $formula = ($hourlyRate / 60) * $totalWorkedMinutes;
    //         $cost = $isCounted ? round($formula, 2) : 0.00;

    //         $totalWorkedMinutes = count($logs);
    //         // Extract the screenshot from the last log entry if available
    //         //Log::info('Logs for current interval:', $logsForInterval->toArray());
    //         $lastLogIndex = count($logsForInterval) - 1;
    //         $screenshotPath = null;
    //         // Check if the last log entry has a screenshot
    //         if (isset($logsForInterval[$lastLogIndex]['screenshot']) && !empty($logsForInterval[$lastLogIndex]['screenshot'])) {
    //             $screenshotBase64 = $logsForInterval[$lastLogIndex]['screenshot'];
    //             $screenshotPath = $this->storeScreenshotFromBase64($screenshotBase64);
    //         }
    //         if (isset($logsForInterval[$lastLogIndex]['elapsed_time']) && !empty($logsForInterval[$lastLogIndex]['elapsed_time'])) {
    //             $elapsedTimeInSeconds = $logsForInterval[$lastLogIndex]['elapsed_time'];
    //         }
    //         $intervalId = \DB::table('job_work_intervals')->insertGetId([
    //             'hourly_bid_id' => $hourlyBidId,
    //             'interval_start_time' => $roundedIntervalStartTime,
    //             'interval_end_time' => $updatedIntervalStartTime->toTimeString(), // Placeholder value
    //             'status' => $isCounted ? 1 : 5,
    //             'screenshot_path' => $screenshotPath,
    //             'activity_level' => $activityLevel,
    //             'is_counted' => $isCounted,
    //             'cost' => $cost,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //             'total_minutes' => $totalWorkedMinutes,
    //         ]);

    //         $logEntries = collect($logsForInterval)->map(function ($log) use ($intervalId) {
    //             return [
    //                 'job_work_delivery_hourly_bid_id' => $log['job_work_delivery_hourly_bid_id'],
    //                 'job_work_intervals_id' => $intervalId,
    //                 'keyboard_activity' => $log['keyboardActivity'],
    //                 'mouse_activity' => $log['mouseActivity'],
    //                 'log_time' => Carbon::parse($log['time'])->format('Y-m-d H:i:s'),
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         })->toArray();

    //         Log::info('logEntries:', $logEntries);

    //         if (!empty($logEntries)) {
    //             \DB::table('activity_logs')->insert($logEntries);
    //         }
    //         Log::info("Processed interval from $intervalStartTime to $updatedIntervalStartTime for $hourlyBidId");
    //     }

    //     $hourlyBid->elapsed_time_in_seconds += $elapsedTimeInSeconds; // Add new elapsed time to existing value
    //     $hourlyBid->status = 1;
    //     $hourlyBid->end_time = $updatedIntervalStartTime->toTimeString();
    //     $hourlyBid->save();

    //     $data = DB::table('job_work_delivery_houly_bid as b')->join('job_work_intervals as i', 'b.id', '=', 'i.hourly_bid_id')
    //         ->selectRaw('b.id, SUM(i.cost) as total_cost, FLOOR(SUM(i.total_minutes) / 60) as time, MOD(SUM(i.total_minutes), 60) as minutes')
    //         ->where('b.id', $hourlyBidId)
    //         ->where('is_counted', 1)
    //         ->groupBy('b.id')
    //         ->first();

    //     if ($data) {
    //         $hourlyBid->total_cost = round($data->total_cost, 2); // Total cost
    //         $hourlyBid->time = $data->time; // Hours
    //         $hourlyBid->minutes = $data->minutes; // Remaining minutes
    //         $hourlyBid->save(); // Save the updated object

    //         Log::info("Updated hourly bid time, minutes, and total cost.", [
    //             'total_cost' => $data->total_cost,
    //             'time' => $data->time,
    //             'minutes' => $data->minutes,
    //         ]);
    //     } else {
    //         Log::warning("No data found for hourly bid update.", [
    //             'hourlyBidId' => $hourlyBidId
    //         ]);
    //     }

    //     return response()->json([
    //         'code' => 200,
    //         'status' => 'success',
    //         'message' => 'Screenshot and activity logs stored successfully',
    //         'job_work_delivery_hourly_bid_id' => $hourlyBidId,
    //     ]);
    // }

    // public function handleAutoTracking(Request $request)
    // {
    //     $validator = \Validator::make($request->all(), [
    //         'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
    //         'logs'                            => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error("Validation failed: ", $validator->errors()->toArray());
    //         return response()->json([
    //             'code'    => 422,
    //             'status'  => 'error',
    //             'message' => $validator->errors(),
    //         ], 422);
    //     }

    //     // Decode logs JSON
    //     $logs = json_decode($request->logs, true);
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         Log::error("JSON decode error: " . json_last_error_msg());
    //         return response()->json([
    //             'code'    => 400,
    //             'status'  => 'error',
    //             'message' => 'Invalid JSON format for logs.',
    //         ], 400);
    //     }

    //     // FIX: Guard against empty logs
    //     if (empty($logs)) {
    //         Log::warning("Empty logs received, nothing to process.");
    //         return response()->json([
    //             'code'    => 200,
    //             'status'  => 'success',
    //             'message' => 'No logs to process.',
    //             'job_work_delivery_hourly_bid_id' => $request->job_work_delivery_hourly_bid_id,
    //         ]);
    //     }

    //     $filteredLogs = collect($logs)->map(function ($log) {
    //         unset($log['screenshot']);
    //         return $log;
    //     });

    //     Log::info("Decoded logs (without screenshot):", $filteredLogs->toArray());

    //     // Fetch hourly bid and related category
    //     $hourlyBidId = $request->job_work_delivery_hourly_bid_id;
    //     $hourlyBid   = Jobworkdeliveryhourlybid::with('job.category')->find($hourlyBidId);

    //     if (!$hourlyBid) {
    //         Log::error("Hourly bid not found for ID: $hourlyBidId");
    //         return response()->json([
    //             'code'    => 404,
    //             'status'  => 'error',
    //             'message' => 'Hourly bid not found.',
    //         ], 404);
    //     }

    //     $maxMouseClicks = $hourlyBid->job->category->mouse_click_threshold ?? 0;
    //     $maxKeystrokes  = $hourlyBid->job->category->keyboard_click_threshold ?? 0;

    //     Log::info("Category thresholds — Mouse: $maxMouseClicks | Keyboard: $maxKeystrokes");

    //     // FIX: Correct grouping formula
    //     // Old: floor(($time->minute - 1) / 10) * 10  ← WRONG (minute 10 → group 0)
    //     // New: floor($time->minute / 10) * 10         ← CORRECT (minute 10 → group 10)
    //     $groupedLogs = [];
    //     foreach ($logs as $log) {
    //         $time         = Carbon::parse($log['time']);
    //         $roundedMinute = floor($time->minute / 10) * 10;
    //         $groupKey     = $time->copy()->minute($roundedMinute)->second(0)->toDateTimeString();

    //         $groupedLogs[$groupKey][] = $log;
    //     }

    //     Log::info("Grouped logs keys:", array_keys($groupedLogs));

    //     $elapsedTimeInSeconds    = 0;
    //     $updatedIntervalStartTime = null; // FIX: Initialize to avoid undefined variable crash

    //     foreach ($groupedLogs as $intervalTime => $logsForInterval) {
    //         $intervalStartTime       = Carbon::parse($intervalTime);
    //         $startMinute             = floor($intervalStartTime->minute / 10) * 10;
    //         $roundedIntervalStartTime = $intervalStartTime->copy()->minute($startMinute)->second(0);
    //         $updatedIntervalStartTime = $roundedIntervalStartTime->copy()->addMinutes(10);

    //         Log::info('Processing interval:', [
    //             'intervalTime'             => $intervalTime,
    //             'roundedIntervalStartTime' => $roundedIntervalStartTime->toDateTimeString(),
    //             'updatedIntervalStartTime' => $updatedIntervalStartTime->toDateTimeString(),
    //         ]);

    //         $isSameDay = $hourlyBid->created_at->isSameDay($roundedIntervalStartTime);

    //         Log::info('Date comparison:', [
    //             'hourlyBid_created_at'     => $hourlyBid->created_at->toDateString(),
    //             'roundedIntervalStartTime' => $roundedIntervalStartTime->toDateString(),
    //             'isSameDay'                => $isSameDay ? 'Yes' : 'No',
    //         ]);

    //         // Handle date change (midnight crossover)
    //         if (!$isSameDay) {
    //             Log::info("Date change detected. Creating a new hourly bid.");
    //             $newHourlyBid                       = $hourlyBid->replicate();
    //             $newHourlyBid->created_at           = $roundedIntervalStartTime->toDateString();
    //             $newHourlyBid->updated_at           = now();
    //             $newHourlyBid->date_start           = $roundedIntervalStartTime->toDateString();
    //             $newHourlyBid->elapsed_time_in_seconds = 0;
    //             $newHourlyBid->save();

    //             $hourlyBidId = $newHourlyBid->id;
    //             $hourlyBid   = $newHourlyBid;

    //             Log::info("New hourly bid created with ID: " . $newHourlyBid->id);
    //         }

    //         $actualMouseClicks = collect($logsForInterval)->sum('mouseActivity');
    //         $actualKeystrokes  = collect($logsForInterval)->sum('keyboardActivity');

    //         Log::info("Interval activity — Mouse: $actualMouseClicks | Keyboard: $actualKeystrokes");

    //         // Activity level calculation (0–10 scale)
    //         $mouseScore    = $maxMouseClicks > 0 ? min(($actualMouseClicks / $maxMouseClicks) * 5, 5) : 0;
    //         $keyboardScore = $maxKeystrokes > 0 ? min(($actualKeystrokes / $maxKeystrokes) * 5, 5) : 0;
    //         $activityLevel = round($mouseScore + $keyboardScore);

    //         // Interval is counted if activity score >= 6 out of 10
    //         $isCounted = $activityLevel >= 6;

    //         Log::info("Activity scores — Mouse: $mouseScore | Keyboard: $keyboardScore | Total: $activityLevel | Counted: " . ($isCounted ? 'Yes' : 'No'));

    //         // FIX: Use count($logsForInterval) not count($logs) for per-interval cost
    //         $totalWorkedMinutes = count($logsForInterval);
    //         $hourlyRate         = $hourlyBid->per_hour_rate;
    //         $formula            = ($hourlyRate / 60) * $totalWorkedMinutes;
    //         $cost               = $isCounted ? round($formula, 2) : 0.00;

    //         // Extract screenshot from last log entry
    //         $lastLogIndex  = count($logsForInterval) - 1;
    //         $screenshotPath = null;

    //         if (
    //             isset($logsForInterval[$lastLogIndex]['screenshot']) &&
    //             !empty($logsForInterval[$lastLogIndex]['screenshot'])
    //         ) {
    //             $screenshotBase64 = $logsForInterval[$lastLogIndex]['screenshot'];
    //             $screenshotPath   = $this->storeScreenshotFromBase64($screenshotBase64);
    //         }

    //         if (
    //             isset($logsForInterval[$lastLogIndex]['elapsed_time']) &&
    //             !empty($logsForInterval[$lastLogIndex]['elapsed_time'])
    //         ) {
    //             $elapsedTimeInSeconds = $logsForInterval[$lastLogIndex]['elapsed_time'];
    //         }

    //         // FIX: Store full datetime for interval_end_time (not just time string)
    //         $intervalId = DB::table('job_work_intervals')->insertGetId([
    //             'hourly_bid_id'       => $hourlyBidId,
    //             'interval_start_time' => $roundedIntervalStartTime->toDateTimeString(),
    //             'interval_end_time'   => $updatedIntervalStartTime->toDateTimeString(), // FIX: full datetime
    //             'status'              => $isCounted ? 1 : 5,
    //             'screenshot_path'     => $screenshotPath,
    //             'activity_level'      => $activityLevel,
    //             'is_counted'          => $isCounted,
    //             'cost'                => $cost,
    //             'created_at'          => now(),
    //             'updated_at'          => now(),
    //             'total_minutes'       => $totalWorkedMinutes,
    //         ]);

    //         // Insert per-minute activity logs
    //         $logEntries = collect($logsForInterval)->map(function ($log) use ($intervalId) {
    //             return [
    //                 'job_work_delivery_hourly_bid_id' => $log['job_work_delivery_hourly_bid_id'],
    //                 'job_work_intervals_id'           => $intervalId,
    //                 'keyboard_activity'               => $log['keyboardActivity'],
    //                 'mouse_activity'                  => $log['mouseActivity'],
    //                 'log_time'                        => Carbon::parse($log['time'])->format('Y-m-d H:i:s'),
    //                 'created_at'                      => now(),
    //                 'updated_at'                      => now(),
    //             ];
    //         })->toArray();

    //         Log::info('Inserting activity log entries:', $logEntries);

    //         if (!empty($logEntries)) {
    //             DB::table('activity_logs')->insert($logEntries);
    //         }

    //         Log::info("Processed interval: $roundedIntervalStartTime → $updatedIntervalStartTime for bid ID: $hourlyBidId");
    //     }

    //     // FIX: Only update end_time if we actually processed intervals
    //     if ($updatedIntervalStartTime) {
    //         $hourlyBid->elapsed_time_in_seconds += $elapsedTimeInSeconds;
    //         $hourlyBid->status   = 1;
    //         $hourlyBid->end_time = $updatedIntervalStartTime->toDateTimeString(); // FIX: full datetime
    //         $hourlyBid->save();
    //     }

    //     // Recalculate total cost, time, and minutes from intervals
    //     $data = DB::table('job_work_delivery_houly_bid as b')
    //         ->join('job_work_intervals as i', 'b.id', '=', 'i.hourly_bid_id')
    //         ->selectRaw('b.id, SUM(i.cost) as total_cost, FLOOR(SUM(i.total_minutes) / 60) as time, MOD(SUM(i.total_minutes), 60) as minutes')
    //         ->where('b.id', $hourlyBidId)
    //         ->where('i.is_counted', 1)
    //         ->groupBy('b.id')
    //         ->first();

    //     if ($data) {
    //         $hourlyBid->total_cost = round($data->total_cost, 2);
    //         $hourlyBid->time       = $data->time;
    //         $hourlyBid->minutes    = $data->minutes;
    //         $hourlyBid->save();

    //         Log::info("Updated hourly bid totals.", [
    //             'total_cost' => $data->total_cost,
    //             'time'       => $data->time,
    //             'minutes'    => $data->minutes,
    //         ]);
    //     } else {
    //         Log::warning("No counted intervals found for hourly bid.", ['hourlyBidId' => $hourlyBidId]);
    //     }

    //     return response()->json([
    //         'code'                            => 200,
    //         'status'                          => 'success',
    //         'message'                         => 'Screenshot and activity logs stored successfully',
    //         'job_work_delivery_hourly_bid_id' => $hourlyBidId,
    //     ]);
    // }

    public function handleAutoTracking(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
            'logs' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());

            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        // Decode logs JSON
        $logs = json_decode($request->logs, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error: '.json_last_error_msg());

            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Invalid JSON format for logs.',
            ], 400);
        }

        // FIX: Guard against empty logs
        if (empty($logs)) {
            Log::warning('Empty logs received, nothing to process.');

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'No logs to process.',
                'job_work_delivery_hourly_bid_id' => $request->job_work_delivery_hourly_bid_id,
            ]);
        }

        $filteredLogs = collect($logs)->map(function ($log) {
            unset($log['screenshot']);

            return $log;
        });

        Log::info('Decoded logs (without screenshot):', $filteredLogs->toArray());

        // Fetch hourly bid and related category
        $hourlyBidId = $request->job_work_delivery_hourly_bid_id;
        $hourlyBid = Jobworkdeliveryhourlybid::with('job.category')->find($hourlyBidId);

        if (! $hourlyBid) {
            Log::error("Hourly bid not found for ID: $hourlyBidId");

            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Hourly bid not found.',
            ], 404);
        }

        $maxMouseClicks = $hourlyBid->job->category->mouse_click_threshold ?? 0;
        $maxKeystrokes = $hourlyBid->job->category->keyboard_click_threshold ?? 0;

        Log::info("Category thresholds — Mouse: $maxMouseClicks | Keyboard: $maxKeystrokes");

        // FIX: Correct grouping formula
        // Old: floor(($time->minute - 1) / 10) * 10  ← WRONG (minute 10 → group 0)
        // New: floor($time->minute / 10) * 10         ← CORRECT (minute 10 → group 10)
        $groupedLogs = [];
        foreach ($logs as $log) {
            $time = Carbon::parse($log['time']);
            $roundedMinute = floor($time->minute / 10) * 10;
            $groupKey = $time->copy()->minute($roundedMinute)->second(0)->toDateTimeString();

            $groupedLogs[$groupKey][] = $log;
        }

        Log::info('Grouped logs keys:', array_keys($groupedLogs));

        $elapsedTimeInSeconds = 0;
        $updatedIntervalStartTime = null; // FIX: Initialize to avoid undefined variable crash

        foreach ($groupedLogs as $intervalTime => $logsForInterval) {
            $intervalStartTime = Carbon::parse($intervalTime);
            $startMinute = floor($intervalStartTime->minute / 10) * 10;
            $roundedIntervalStartTime = $intervalStartTime->copy()->minute($startMinute)->second(0);
            $updatedIntervalStartTime = $roundedIntervalStartTime->copy()->addMinutes(10);

            Log::info('Processing interval:', [
                'intervalTime' => $intervalTime,
                'roundedIntervalStartTime' => $roundedIntervalStartTime->toDateTimeString(),
                'updatedIntervalStartTime' => $updatedIntervalStartTime->toDateTimeString(),
            ]);

            $isSameDay = $hourlyBid->created_at->isSameDay($roundedIntervalStartTime);

            Log::info('Date comparison:', [
                'hourlyBid_created_at' => $hourlyBid->created_at->toDateString(),
                'roundedIntervalStartTime' => $roundedIntervalStartTime->toDateString(),
                'isSameDay' => $isSameDay ? 'Yes' : 'No',
            ]);

            // Handle date change (midnight crossover)
            if (! $isSameDay) {
                Log::info('Date change detected. Creating a new hourly bid.');
                $newHourlyBid = $hourlyBid->replicate();
                $newHourlyBid->created_at = $roundedIntervalStartTime->toDateString();
                $newHourlyBid->updated_at = now();
                $newHourlyBid->date_start = $roundedIntervalStartTime->toDateString();
                $newHourlyBid->elapsed_time_in_seconds = 0;
                $newHourlyBid->save();

                $hourlyBidId = $newHourlyBid->id;
                $hourlyBid = $newHourlyBid;

                Log::info('New hourly bid created with ID: '.$newHourlyBid->id);
            }

            $actualMouseClicks = collect($logsForInterval)->sum('mouseActivity');
            $actualKeystrokes = collect($logsForInterval)->sum('keyboardActivity');

            Log::info("Interval activity — Mouse: $actualMouseClicks | Keyboard: $actualKeystrokes");

            // Activity level calculation (0–10 scale)
            $mouseScore = $maxMouseClicks > 0 ? min(($actualMouseClicks / $maxMouseClicks) * 5, 5) : 0;
            $keyboardScore = $maxKeystrokes > 0 ? min(($actualKeystrokes / $maxKeystrokes) * 5, 5) : 0;
            $activityLevel = round($mouseScore + $keyboardScore);

            // Interval is counted if activity score >= 6 out of 10
            $isCounted = $activityLevel >= 6;

            Log::info("Activity scores — Mouse: $mouseScore | Keyboard: $keyboardScore | Total: $activityLevel | Counted: ".($isCounted ? 'Yes' : 'No'));

            // FIX: Use count($logsForInterval) not count($logs) for per-interval cost
            $totalWorkedMinutes = count($logsForInterval);
            $hourlyRate = $hourlyBid->per_hour_rate;
            $formula = ($hourlyRate / 60) * $totalWorkedMinutes;
            $cost = $isCounted ? round($formula, 2) : 0.00;

            // Extract screenshot from last log entry
            $lastLogIndex = count($logsForInterval) - 1;
            $screenshotPath = null;

            if (
                isset($logsForInterval[$lastLogIndex]['screenshot']) &&
                ! empty($logsForInterval[$lastLogIndex]['screenshot'])
            ) {
                $screenshotBase64 = $logsForInterval[$lastLogIndex]['screenshot'];
                $screenshotPath = $this->storeScreenshotFromBase64($screenshotBase64);
            }

            if (
                isset($logsForInterval[$lastLogIndex]['elapsed_time']) &&
                ! empty($logsForInterval[$lastLogIndex]['elapsed_time'])
            ) {
                $elapsedTimeInSeconds = $logsForInterval[$lastLogIndex]['elapsed_time'];
            }

            // FIX: Store full datetime for interval_end_time (not just time string)
            $intervalId = DB::table('job_work_intervals')->insertGetId([
                'hourly_bid_id' => $hourlyBidId,
                'interval_start_time' => $roundedIntervalStartTime->toDateTimeString(),
                'interval_end_time' => $updatedIntervalStartTime->toDateTimeString(), // FIX: full datetime
                'status' => $isCounted ? 1 : 5,
                'screenshot_path' => $screenshotPath,
                'activity_level' => $activityLevel,
                'is_counted' => $isCounted,
                'cost' => $cost,
                'created_at' => now(),
                'updated_at' => now(),
                'total_minutes' => $totalWorkedMinutes,
            ]);

            // Insert per-minute activity logs
            $logEntries = collect($logsForInterval)->map(function ($log) use ($intervalId) {
                return [
                    'job_work_delivery_hourly_bid_id' => $log['job_work_delivery_hourly_bid_id'],
                    'job_work_intervals_id' => $intervalId,
                    'keyboard_activity' => $log['keyboardActivity'],
                    'mouse_activity' => $log['mouseActivity'],
                    'log_time' => Carbon::parse($log['time'])->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Log::info('Inserting activity log entries:', $logEntries);

            if (! empty($logEntries)) {
                DB::table('activity_logs')->insert($logEntries);
            }

            Log::info("Processed interval: $roundedIntervalStartTime → $updatedIntervalStartTime for bid ID: $hourlyBidId");
        }

        // FIX: Only update end_time if we actually processed intervals
        if ($updatedIntervalStartTime) {
            $hourlyBid->elapsed_time_in_seconds += $elapsedTimeInSeconds;
            $hourlyBid->status = 1;
            $hourlyBid->end_time = $updatedIntervalStartTime->toDateTimeString(); // FIX: full datetime
            $hourlyBid->save();
        }

        // Recalculate total cost, time, and minutes from intervals
        $data = DB::table('job_work_delivery_houly_bid as b')
            ->join('job_work_intervals as i', 'b.id', '=', 'i.hourly_bid_id')
            ->selectRaw('b.id, SUM(i.cost) as total_cost, FLOOR(SUM(i.total_minutes) / 60) as time, MOD(SUM(i.total_minutes), 60) as minutes')
            ->where('b.id', $hourlyBidId)
            ->where('i.is_counted', 1)
            ->groupBy('b.id')
            ->first();

        if ($data) {
            $hourlyBid->total_cost = round($data->total_cost, 2);
            $hourlyBid->time = $data->time;
            $hourlyBid->minutes = $data->minutes;
            $hourlyBid->save();

            Log::info('Updated hourly bid totals.', [
                'total_cost' => $data->total_cost,
                'time' => $data->time,
                'minutes' => $data->minutes,
            ]);
        } else {
            Log::warning('No counted intervals found for hourly bid.', ['hourlyBidId' => $hourlyBidId]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Screenshot and activity logs stored successfully',
            'job_work_delivery_hourly_bid_id' => $hourlyBidId,
        ]);
    }

    // private function storeScreenshotFromBase64($base64Image)
    // {
    //     if (empty($base64Image)) {
    //         return null;
    //     }

    //     // Remove the "data:image/jpeg;base64," prefix
    //     $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);

    //     // Decode base64 to binary data
    //     $imageData = base64_decode($base64Image);

    //     // Check if the decoding was successful
    //     if ($imageData === false) {
    //         \Log::error('Failed to decode base64 image data.');
    //         return null;
    //     }

    //     // Create a unique file name
    //     $filename = 'screenshot_' . time() . '.jpg';
    //     $filePath = 'screenshots/' . $filename;

    //     // Store the image
    //     \Storage::disk('public')->put($filePath, $imageData);

    //     return $filePath;
    // }

    private function storeScreenshotFromBase64($base64Image)
    {
        if (empty($base64Image)) {
            return null;
        }

        // Remove data URI prefix
        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = base64_decode($base64Image);

        if ($imageData === false) {
            Log::error('Failed to decode base64 image data.');

            return null;
        }

        $filename = 'screenshot_'.time().'_'.uniqid().'.jpg';
        $filePath = 'screenshots/'.$filename;

        \Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }

    // public function stopTracking(Request $request)
    // {
    //     // Validate the request
    //     $validator = \Validator::make($request->all(), [
    //         'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
    //         'elapsed_time' => 'required|numeric|min:0',
    //         'memoInput' => 'required|string|max:500',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'code' => 422,
    //             'status' => 'error',
    //             'message' => $validator->errors(),
    //         ], 422);
    //     }

    //     $elapsedTimeInSeconds = $request->elapsed_time; // Total elapsed time in seconds

    //     // Retrieve the hourly bid from the request
    //     $hourlyBid = Jobworkdeliveryhourlybid::where('id', $request->job_work_delivery_hourly_bid_id)->first();

    //     if ($hourlyBid) {
    //         // Update end_time to the current timestamp
    //         $hourlyBid->updated_at = now();
    //         $hourlyBid->memo = $request->memoInput;
    //         $hourlyBid->elapsed_time_in_seconds = $elapsedTimeInSeconds;
    //         $hourlyBid->status = 1;
    //         $hourlyBid->save(); // Save changes to the hourly bid
    //     }

    //     return response()->json([
    //         'code' => 200,
    //         'status' => 'success',
    //         'message' => 'Tracking stopped and job delivery recorded successfully',
    //     ]);
    // }

    public function stopTracking(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
            'elapsed_time' => 'required|numeric|min:0',
            'memoInput' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $hourlyBid = Jobworkdeliveryhourlybid::where('id', $request->job_work_delivery_hourly_bid_id)->first();

        if (! $hourlyBid) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Hourly bid not found.',
            ], 404);
        }

        // FIX: Don't overwrite elapsed_time_in_seconds already set by handleAutoTracking
        // Only update memo, status, and updated_at
        $hourlyBid->updated_at = now();
        $hourlyBid->memo = $request->memoInput;
        $hourlyBid->status = 1;

        // Only update elapsed time if handleAutoTracking hasn't set it yet
        if ($hourlyBid->elapsed_time_in_seconds == 0) {
            $hourlyBid->elapsed_time_in_seconds = $request->elapsed_time;
        }

        $hourlyBid->save();

        Log::info('Tracking stopped for bid ID: '.$hourlyBid->id, [
            'elapsed_time' => $hourlyBid->elapsed_time_in_seconds,
            'memo' => $request->memoInput,
        ]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Tracking stopped and job delivery recorded successfully',
        ]);
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

        $pageTitle = 'Invoice';
        $jobId = decrypt($encJobId);
        $errorMessage = null;

        // Fetch the related booking
        $booking = Booking::where('job_id', $jobId)->first();
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
            ->where('status', 1)
            ->orderBy('created_at','desc')
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
            // Check if any transaction is a platform commission
            $isCommission = $transactions->contains(function ($transaction) {
                return str_contains($transaction->details, 'Platform commission for Job ID');
            });
            // Update seller name if it's a commission
            if ($isCommission) {
                $sellerName = 'Wiserlance Platform';
            }

            return [
                'invoiceNumber' => $invoiceNumber,
                'sellerName' => $sellerName,
                'orderNumber' => $orderNumber,
                'amount' => $amount,
                'details' => $transactions->first()->details ?? '',
                'invoiceDate' => Carbon::parse($invoiceDate)->format('d M, Y'),
                'transactions' => $transactions,
                'isCommission' => $isCommission,
            ];
        });

        if (request()->ajax()) {
            return view('seller.workspace.invoice', compact('invoices', 'errorMessage'));
        }

        return view('seller.workspace.full_invoice', compact('errorMessage', 'buyer', 'invoices', 'pageTitle', 'jobTitle', 'jobDescription', 'job_type', 'seller', 'buyer', 'isCompleted', 'buyerId', 'sellerId', 'encUserBidingId', 'encJobId'));
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
        $acceptedBid = Acceptedbid::where('job_id', $jobId)->where('seller_id', $user->id)->first();
        if (! $acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller; // Using the relationship
        if (! $seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }
        // Retrieve Userbiding based on `job_id` and `seller_offer_responce`
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('reciever_id', $user->id)->where('status', 1)->first();
        $rating = ReviewRating::where('data_id', $jobId)->first();

        $job = $acceptedBid->job; // Access job directly from the relationship
        $encJobId = encrypt($job->id);
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $isCompleted = $job->show_completed;
        $pageTitle = 'Rating';
        $buyerId = $job->user_id;
        $buyer = User::find($buyerId);
        $sellerId = $seller->id;

        $job_type = $buyerCounterOffer->type;

        if ($request->ajax()) {
            return view('seller.workspace.rating', compact('rating', 'buyerId', 'sellerId', 'seller')); // Change to singular
        }

        return view('seller.workspace.full_rating', compact('rating', 'buyer', 'buyerId', 'sellerId', 'seller', 'pageTitle', 'jobTitle', 'jobDescription', 'isCompleted', 'encJobId', 'job_type'));
    }

    public function requestManualLog(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'encBuyerCounterOfferDataId' => 'required|string',
        ]);

        try {
            // Decrypt the encBuyerCounterOfferDataId
            $buyerCounterOfferDataId = decrypt($validated['encBuyerCounterOfferDataId']);
            // Find the record in buyer_counter_offers_data
            $buyerCounterOfferData = Buyercounterofferdata::find($buyerCounterOfferDataId);
            if (! $buyerCounterOfferData) {
                $notify[] = ['error', 'No eligible record found in buyer_counter_offers'];

                return redirect()->back()->withNotify($notify);
            }

            // Retrieve the counter_offer_id
            $counterOfferId = $buyerCounterOfferData->counter_offer_id;

            // Update the request_description in buyer_counter_offers
            $buyerCounterOffer = Buyercounteroffer::find($counterOfferId);

            if (! $buyerCounterOffer) {
                $notify[] = ['error', 'No eligible record found in buyer_counter_offers'];

                return redirect()->back()->withNotify($notify);
            }

            $buyerCounterOffer->update([
                'request_description' => $validated['reason'],
                'allow_manual_time' => 1,
            ]);

            sendJobEmailWithNotification($buyerCounterOffer->reciever_id, $buyerCounterOffer->sender_id, $buyerCounterOffer->job_id, 'MANUAL_LOG_REQUESTED', false, null, null, null, $buyerCounterOffer->id);

            $notify[] = ['success', 'Your request for manual time logging has been submitted.'];

            return redirect()->back()->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];

            return redirect()->back()->withNotify($notify);
        }
    }

    public function updateMemo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_work_delivery_hourly_bid_id' => 'required|exists:job_work_delivery_houly_bid,id',
            'memo' => 'required|string|min:5|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        $bid = Jobworkdeliveryhourlybid::find($validated['job_work_delivery_hourly_bid_id']);

        // Calculate the start and end of the current week
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY); // Monday 12:00 AM
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);   // Sunday 11:59:59 PM

        if (! $bid || $bid->created_at < $startOfWeek || $bid->created_at > $endOfWeek) {
            return response()->json(['success' => false, 'message' => 'Memo can only be updated for bids in the current week.'], 403);
        }

        $bid->memo = $validated['memo'];

        if ($bid->save()) {
            return response()->json(['success' => true, 'message' => 'Memo updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update memo.'], 500);
    }

    public function printInvoice($invoiceNo)
    {
        $transaction = Transaction::with('user:id,firstname,lastname,address', 'toUser:id,firstname,lastname,address', 'booking:id,qty,job_id', 'booking.job:id,budgettype')
            ->where('trx', $invoiceNo)
            ->first();
        $invoice = [
            'invoiceNumber' => $invoiceNo,
            'date' => now()->toDateString(),
            'seller' => $transaction?->user?->firstname.' '.$transaction?->user?->lastname,
            'seller_address' => optional($transaction->user->address)->address.' '.
                optional($transaction->user->address)->city.' '.
                optional($transaction->user->address)->state,
            'employeer' => $transaction?->toUser?->firstname.' '.$transaction?->toUser?->lastname,
            'employeer_address' => $transaction?->toUser?->address
                ? $transaction->toUser->address->address.' '.
                $transaction->toUser->address->city.' '.
                $transaction->toUser->address->state
                : '',
            'amount' => $transaction?->amount ?? 0,
            'qty' => $transaction?->booking?->qty,
            'budgettype' => $transaction?->booking?->job?->budgettype,
            'charge' => $transaction?->charge ?? 0,
            'created_at' => $transaction?->created_at,
            'details'=>$transaction?->details
        ];

        $pdf = \Pdf::loadView('seller.workspace.print', compact('invoice'));

        // Show the PDF in browser (not download)
        return $pdf->stream("invoice_{$invoiceNo}.pdf");
        // return view('seller.workspace.print');
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
}
