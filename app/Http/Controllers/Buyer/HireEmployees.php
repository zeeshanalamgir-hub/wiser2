<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userbiding;
use App\Models\User;
use App\Models\Buyercounteroffer;
use App\Models\Buyercounterofferdata;
use App\Models\Job;
use App\Models\UserTimesheet;
use App\Traits\UbPriceStrategyTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Acceptedbid;
use App\Models\Jobworkdeliverybid;
use App\Models\Booking;
use App\Models\UserOffer;
use Illuminate\Support\Facades\DB;
use App\Models\Employermembershiptransactions;
use App\Models\Employerservicescount;
class HireEmployees extends Controller
{
    use UbPriceStrategyTrait;

    public function hireEmployOpenJobs(Request $request)
    {
        $user = Auth::user();
        $pageTitle = "Open Jobs";
        $jobs = $this->getOpenJobs($request, $user);

        return view('templates.basic.user.buyer.components.hire-employee.open-jobs', compact('pageTitle', 'jobs'));
    }
    public function hireEmployOpenJobsAjax(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.authorization');
        }
        $pageTitle = "Open Jobs";
        $jobs = $this->getOpenJobs($request, $user);

        if ($request->ajax()) {
            return view('templates.basic.user.buyer.components.hire-employee.partials.open-jobs', compact('pageTitle', 'jobs'));
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.buyer.components.hire-employee.open-jobs', compact('pageTitle', 'jobs'));
        }
    }

    protected function getOpenJobs(Request $request, $user)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $query = Job::select('jobs.*')->withCount('userBiding')->where('user_id', $user->id);
        // ->where('status', 1)
        // ->havingRaw('user_biding_count > 0');
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('id', 'desc');
        $jobs = $query->paginate(10);
        //$jobs = $query->paginate(10, ['*'], 'page', $page);
        $jobs->getCollection()->transform(function ($job) {
            $job->encrypted_job_id = Crypt::encrypt($job->id);
            $job->created_date = $job->created_at->format('d-M-Y');
            return $job;
        });

        return $jobs;
    }
    public function offeredJobDetails(Request $request, $buyerCounter)
    {
        
        $pageTitle = "Offer Details";
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        // Decrypt buyerCounter to get the actual ID
        $id = decrypt($buyerCounter);
        // Fetch data from buyer_counter_offers table
        $buyerCounterOffer = Buyercounteroffer::where('id', $id)->first();

        if (!$buyerCounterOffer) {
            return redirect()->back()->with('error', 'Offer not found');
        }

        // Initialize milestones as an empty collection
        $milestones = collect();
        $totalAmount = 0; // Initialize total amount
        $offerData = Buyercounterofferdata::where('counter_offer_id', $id)->get();
        // Determine offer type and fetch appropriate data
        foreach ($offerData as $index => $data) {
            $milestones->push((object) [
                'title' => $data->title ?? 'Project Payment', // Use data properties
                'amount' => $data->amount ?? 0,
                'due_date' => $data->due_date ?? 'N/A',
                'due_days' => $data->due_days ?? 'N/A',
            ]);
            $totalAmount += $data->amount ?? 0; // Safely sum amounts
        }
        // Format the created_at date for the buyer counter offer
        $buyerCounterOffer->created_date = \Carbon\Carbon::parse($buyerCounterOffer->created_at)->format('d-M-Y');
        // Get status label from the model method

        $status = $buyerCounterOffer->status;
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Offered Jobs', 'url' => '/user/buyer/hire/offered-jobs'],
            ['title' => 'View', 'url' => ''],
        ];
        return view('templates.basic.user.buyer.components.hire-employee.offered-job-details', compact('breadcrumbs','pageTitle', 'buyerCounterOffer', 'milestones', 'totalAmount', 'status'));
    }


    public function hireEmployClosedJobs(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Closed Jobs";
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified

        $jobs = Job::where('show_completed', 1)->select('id', 'title', 'budgettype')->get();
        // Prepare an array to store valid job IDs
        $validJobIds = $jobs->pluck('id');
        $query = Buyercounteroffer::whereIn('job_id', $validJobIds)->where('sender_id', $user->id)->where('status', 1);
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
            $item->encUserBiddingId =  encrypt($item->id);

            $item->username = optional($item->user)->name ?? 'N/A'; // Use optional() to avoid errors

            return $item;
        });
        if ($request->ajax()) {
            return view('templates.basic.user.buyer.components.hire-employee.partials.closed-jobs', compact('pageTitle', 'data'));
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.buyer.components.hire-employee.closed-jobs', compact('pageTitle', 'data'));
        }
    }

    public function hireEmployCancelJobFromBuyer(Request $request)
    {

        $data = $request->all();
        $user = Auth::user();
        $encId = $data['encId'];

        $query = Job::where(['user_id' => $user->id, 'id' => decrypt($encId)])->update(['status' => 3]);

        return response()->json($query);
    }

    public function hireEmploybidsendoffer(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Send Bid Offer";

        $item = Userbiding::where('id', decrypt($id))
            ->with(['user', 'biddingStatus', 'job'])
            ->first();

        if (!$item) {
            return redirect()->back();
        }

        $item->encrypted_job_id = Crypt::encrypt($item->job_id);
        $item->encrypted_id = Crypt::encrypt($item->id);

        $item = $this->calculatePriceAndFormat($item);

        // dd("asfgnsdjgb",$item->id);
        $bidData = Buyercounteroffer::where('bid_id', $item->id)->with(['offerdata'])->first();

        $bidStatus = $item->bid_status;

        $bidStatus = str_replace('_', ' ', $bidStatus);

        // Capitalize the first letter of each word
        $bidStatus = ucwords($bidStatus);
        $item->bid_status = $bidStatus;

        // dd($bidData);

        $data = [
            'pageTitle' => $pageTitle,
            'biddetail' => $item,
            'item' => $item,
            'offer' => $bidData
        ];

        // dd($data);

        return view('templates.basic.user.buyer.hire_employ_bid-send-offer', $data);
    }

    // When Buyer already received offer from seller and now buyer is making a counter offer to seller
    public function hireEmploybidsubmitoffer(Request $request)
    {
        $buyer = User::findOrFail($request->sender);
        $receiver = User::findOrFail($request->reciever);

        // Validate input based on job type
        $this->validate($request, $this->getValidationRules($request->job_type));

        DB::beginTransaction();

        try {
            // Check balance
            $total = $this->calculateTotalAmount($request);

            if ($total > $buyer->balance) {
                return redirect()->back()->withNotify(['error' => 'You have insufficient balance.'])->withInput();
            }

            // Handle buyer counter offer
            $offer = $this->createOrUpdateOffer($request);

            // Handle buyer counter offer data
            $this->handleOfferData($request, $offer);

            // Update Userbiding
            $this->updateUserBiding($request->bid_id);

            // Create a booking
            $this->createBooking($request, $offer, $total);

            try {
                sendJobEmailWithNotification($offer->sender_id, $offer->reciever_id, $offer->job_id, 'SEND_OFFER', false, $request->bid_id,null,null, $offer->id);
                sendAdminRealTimeNotification(Auth::user(), 'Send Offer to '.$receiver?->username, route('admin.job.details',['id'=>$offer->job_id]));
            } catch (\Exception $emailException) {
                // Log the email error but do not rollback the transaction
                \Log::error('Email failed to send: ' . $emailException->getMessage());
            }

            DB::commit();

            return $this->redirectWithSuccess('Offer successfully sent', $request->job_id);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the primary error
             \Log::error('Error in hireEmploybidsubmitoffer: ' . $e->getMessage());
            return $this->redirectWithError('An error occurred while processing the request.');
        }
    }

    public function inviteProfessional(Request $request)
    {
        $this->validate($request, [
            'job_id'=>'required|exists:jobs,id',
            // 'paymentMethod' => 'required',
            'project_amount' => 'required_if:paymentMethod,project',
            'due_date' => 'required_if:paymentMethod,project',
            'milestone_title.*'  => 'required_if:paymentMethod,milestone',
            'milestone_amount.*' => 'required_if:paymentMethod,milestone|numeric',
            'due_date.*' => 'required_if:paymentMethod,milestone|date',
        ],[
            'job_id.required' => 'Please select a job',
            // 'paymentMethod.required' => 'Please select a payment method',
            'project_amount.required_if'    => 'Please enter the project amount when payment method is Project.',
            'due_date.required_if'          => 'Please provide a due date when payment method is Project.',

            'milestone_title.*.required_if'    => 'Milestone title is required when payment method is Milestone.',
            'milestone_amount.*.required_if'   => 'Milestone amount is required when payment method is Milestone.',
            'milestone_amount.*.numeric'       => 'Milestone amount must be a valid number.',
            'due_date.*.required_if' => 'Milestone due date is required when payment method is Milestone.',
            'due_date.*.date'        => 'Milestone due date must be a valid date.',
        ]);
        if ($request->job_type === 'fixed') {
            if ($request->paymentMethod === 'project') {
                $request->merge(['job_type' => 'byproject']);
                $this->validate($request, [
                    'paymentMethod' => 'required',
                ],[
                    'paymentMethod.required' => 'Please select a payment method',
                ]);

            } elseif ($request->paymentMethod === 'milestone') {
                $request->merge(['job_type' => 'bymilestone']);
                $this->validate($request, [
                    'paymentMethod' => 'required',
                ],[
                    'paymentMethod.required' => 'Please select a payment method',
                ]);
            }
        }

        // $this->validate($request, [
        //     'job_id' => 'required|exists:jobs,id', // Ensure the selected job exists
        //     'reciever' => 'required|exists:users,id',
        //     'hourly_amount' => 'required_if:job_type,hourly|numeric', // Required for Hourly jobs
        //     'weekly_hours' => 'required_if:job_type,hourly|numeric', // Required for Hourly jobs
        //     'amount' => 'required_if:job_type,Fixed|numeric',
        //     // 'depositfund' => 'required',
        //     'description' => 'required',
        // ]);

        $user = Auth::user();
        $totalamount = $request->hourly_amount * $request->weekly_hours;
        if ($totalamount > $user->balance) {
            $notify[] = ['error', 'You do not have enough money in your account to process this request.'];
            return redirect(url('/professionals'))->withNotify($notify);
        }

        $professionalId = $request->reciever;
        $jobId = $request->job_id;

        $checkofferexist = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('reciever_id', $professionalId)->get();
        if (count($checkofferexist) > 0) {
            $notify[] = ['error', 'You already made offer to this professional on this job'];
            return redirect()->back()->withNotify($notify);
        }
        // Validate input based on job type
        $this->validate($request, $this->getValidationRules($request->job_type));

        try {
            // Check balance
            $total = $this->calculateTotalAmount($request);
            if ($total > $user->balance) {
                return $this->redirectWithError('You do not have enough money to perform this request.');
            }

            //user bidding
            $biding = $this->createUserBiding($request);
            
            // Handle buyer counter offer
            $offer = $this->createOrUpdateOffer($request, $biding);

            // Handle buyer counter offer data
            $this->handleOfferData($request, $offer, $biding);

            // Update Userbiding
            $this->updateUserBiding($request->bid_id);

            // Create a booking
            $this->createBooking($request, $offer, $total);

            $employeeMembership =  null;
            if ($user->is_employer == 1) {
                $employeeMembership = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            }
            if($employeeMembership->count() > 0){
                $getinvites = Employerservicescount::where('user_id', $user->id)->first();
                $invites = $getinvites->invites;
                $up = Employerservicescount::find($getinvites->id);
                $up->invites = $invites - 1;
                $up->save();
            }
            try {
                sendJobEmailWithNotification($offer->sender_id, $offer->reciever_id, $offer->job_id, 'SEND_OFFER', false, $request->bid_id,null,null, $offer->id);
            } catch (\Exception $emailException) {
                // Log the email error but do not rollback the transaction
                \Log::error('Email failed to send: ' . $emailException->getMessage());
            }

            DB::commit();

            return $this->redirectWithSuccess('Offer successfully sent', $request->job_id);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the primary error
             \Log::error('Error in hireEmploybidsubmitoffer: ' . $e->getMessage());
            return $this->redirectWithError('An error occurred while processing the request.');
        }
    }

    private function userBiding()
    {

    }

    private function getValidationRules($jobType)
    {
        $rules = ['description' => 'required'];

        if ($jobType === 'hourly') {
            $rules += [
                'hourly_amount' => 'required',
                'weekly_hours' => 'required',
            ];
        } elseif ($jobType === 'byproject') {
            $rules += [
                'project_amount' => 'required',
                'due_date.*' => 'date|after_or_equal:today',
            ];
        } elseif ($jobType === 'bymilestone') {
            $rules += [
                'milestone_title' => 'required|array',
                'milestone_amount' => 'required|array',
                'milestone_title.*' => 'string|max:255',
                'milestone_amount.*' => 'numeric|min:1',
                'due_date' => 'required|array',
                'due_date.*' => 'date|after_or_equal:today',
            ];
        }

        return $rules;
    }

    private function calculateTotalAmount($request)
    {
        if ($request->job_type === 'hourly') {
            return (int)$request->hourly_amount * (int)$request->weekly_hours;
        }

        if ($request->job_type === 'byproject') {
            return $request->project_amount;
        }

        if ($request->job_type === 'bymilestone') {
            return array_sum($request->milestone_amount);
        }

        return 0;
    }

    private function createOrUpdateBuyerCounterOffer($request, $biding)
    {
        $offer = $request->counter_offer_id
            ? Buyercounteroffer::findOrNew($request->counter_offer_id)
            : new Buyercounteroffer();

        $offer->fill([
            'job_id' => $request->job_id,
            'type' => $request->job_type,
            'status' => 0,
            'bid_id' => $biding->id ?? null,
            'sender_id' => Auth::id(),
            'reciever_id' => $request->reciever,
            'description' => $request->description,
            'allow_manual_time' => $request->manual_time_logging === 'on' ? 1 : 0,
            'is_manual_log_accepted' => $request->manual_time_logging === 'on' ? 1 : 0,
            'close_job' => $request->has('close_project') ? true : false, // Set close_job based on checkbox
            'invite_professional' => $request->has('invite_professional') ? true : false,
        ]);
        $offer->save();

        return $offer;
    }

    private function createOrUpdateOffer($request,$biding = null)
    {
        $offer = $request->counter_offer_id
            ? Buyercounteroffer::findOrNew($request->counter_offer_id)
            : new Buyercounteroffer();

        $offer->fill([
            'job_id' => $request->job_id,
            'type' => $request->job_type,
            'status' => 0,
            'bid_id' => $request->bid_id ?? $biding->id,
            'sender_id' => Auth::id(),
            'reciever_id' => $request->reciever,
            'description' => $request->description,
            'allow_manual_time' => $request->manual_time_logging === 'on' ? 1 : 0,
            'is_manual_log_accepted' => $request->manual_time_logging === 'on' ? 1 : 0,
            'close_job' => $request->has('close_project') ? true : false, // Set close_job based on checkbox
            'invite_professional' => $request->has('invite_professional') ? true : false,
        ]);
        $offer->save();

        return $offer;
    }

    private function handleOfferData($request, $offer, $biding = null)
    {
        if ($request->job_type === 'hourly') {
            $this->saveHourlyOfferData($request, $offer, $biding);
        } elseif ($request->job_type === 'byproject') {
            $this->saveProjectOfferData($request, $offer);
        } elseif ($request->job_type === 'bymilestone') {
            $this->saveMilestoneOfferData($request, $offer);
        }
    }

    private function saveHourlyOfferData($request, $offer, $biding = null)
    {
        $buyer_counter_offer_data = Buyercounterofferdata::create([
            'counter_offer_id' => $offer->id,
            'job_id' => $request->job_id,
            'bid_id' => $request->bid_id ?? $biding->id,
            'sender_id' => Auth::id(),
            'reciever_id' => $request->reciever,
            'days' => $request->weekly_hours,
            'amount' => $request->hourly_amount,
            'title' => $request->h_title,
        ]);
    }

    private function saveProjectOfferData($request, $offer)
    {
        $projectDays = now()->diffInDays(Carbon::parse($request->due_date));
        Buyercounterofferdata::create([
            'counter_offer_id' => $offer->id,
            'job_id' => $request->job_id,
            'bid_id' => $request->bid_id,
            'sender_id' => Auth::id(),
            'reciever_id' => $request->reciever,
            'days' => $projectDays,
            'amount' => $request->project_amount,
            'title' => $request->p_title,
            'milestone_status' => 0,
            'due_date' => $request->due_date,
        ]);
    }

    private function saveMilestoneOfferData($request, $offer)
    {
        $titles = $request->milestone_title;
        $amounts = $request->milestone_amount;
        $dueDates = $request->due_date;

        foreach ($titles as $key => $title) {
            $milestoneDays = now()->diffInDays(Carbon::parse($dueDates[$key]));
            Buyercounterofferdata::create([
                'counter_offer_id' => $offer->id,
                'job_id' => $request->job_id,
                'bid_id' => $request->bid_id,
                'sender_id' => $request->sender,
                'reciever_id' => $request->reciever,
                'days' =>  $milestoneDays,
                'amount' => $amounts[$key],
                'title' => $title,
                'due_date' => $dueDates[$key],
                'milestone_status' => 0,
            ]);
        }
    }

    private function createUserBiding($request)
    {
        return Userbiding::create([
            'record_type'=>'invite',
            'job_type' => $request->job_type,
            'job_id' => $request->job_id,
            'user_id'=>$request->reciever,
            'bid_price'=>$request->hourly_amount ?? $request->project_amount ?? array_sum($request->milestone_amount),
            'cutout'=>0,
            'proposal'=> $request->description,
            'status'=>0,
            'order_number' => getTrx(),
            'is_buyer_offer_sent' => 1,
            'bid_status' => 'buyer_invite_freelancer',
        ]);
    }

    private function updateUserBiding($bidId)
    {
        Userbiding::where('id', $bidId)->update([
            'is_buyer_offer_sent' => 1,
            'seller_offer_responce' => 3,
            'bid_status' => 'buyer_offer_received',
        ]);
    }

    private function createBooking($request, $offer, $totalAmount)
    {
        Booking::create([
            'buyer_id' => Auth::id(),
            'service_id' => $offer->id,
            'seller_id' => $request->reciever,
            'job_id' => $request->job_id,
            'qty' => 1,
            'amount' => $totalAmount,
            'order_number' => getTrx(),
            'status' => 0,
            'booking_type' => $request->job_type,
            'updated_at' => now(),
            'status_updated_at' => now(),
        ]);
    }

    private function redirectWithError($message)
    {
        return redirect()->back()->withNotify([['error', $message]]);
    }

    private function redirectWithSuccess($message, $jobId)
    {
        return redirect()->route('user.offered.jobs')->withNotify([['success', $message]]);
    }

    public function buyerOfferFixedBidDetails(Request $request)
    {
        $data = $request->all();

        $endBidId = $data['encrypt_bid_id'];

        if (!$endBidId) {
            return null;
        }

        $bid = Userbiding::where('id', decrypt($endBidId))->with(['job'])->first();
        if (!$bid) {
            return null;
        }

        $bid->encrypted_job_id = Crypt::encrypt($bid->job_id);
        $bid->encrypted_id = Crypt::encrypt($bid->id);

        $offerData = Buyercounteroffer::where('bid_id', $bid->id)->first();

        if (!$offerData) {
            return null;
        }

        $offerData->created_date = Carbon::parse($offerData->created_at)->format('Y-m-d');

        $offerDataChild = Buyercounterofferdata::where('counter_offer_id', $offerData->id)->first();

        $obj = [
            'bid' => $bid,
            'buyer_offer' => $offerData,
            'buyer_offer_child' => $offerDataChild,
        ];

        return $obj;
    }
    // AJAX-only method
    public function offeredJobListAjax(Request $request)
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
        $pageTitle = "Offered Jobs";
        $offers = $this->buyerSendOffer($request, $user, [0, 1],true);

        if ($request->ajax()) {
            return view('templates.basic.user.buyer.components.hire-employee.partials.offered-jobs', compact('pageTitle', 'offers'));
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.buyer.components.hire-employee.offered-jobs', compact('pageTitle', 'offers'));
        }
    }

    // Non-AJAX method
    public function offeredJobList(Request $request)
    {
        $user = Auth::user();
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Offered Jobs";

        // Reuse the logic from the AJAX method
        $offers = $this->buyerSendOffer($request, $user, [0, 1 , 2],true);
        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Offered Jobs'],
        // ];
        return view('templates.basic.user.buyer.components.hire-employee.offered-jobs', compact('pageTitle', 'offers'));
    }

    protected function buyerSendOffer(Request $request, $user, $status = null, $showCompletedFlag = false)
    {
        // Retrieve the search query from the request
        $search = $request->input('search');

        // Initialize the query to get counter offers with related data and job details
        $query = BuyerCounterOffer::with(['buyerCounterOfferData','job' => function ($q) use ($showCompletedFlag) {
                $q->select('id', 'title', 'budgettype', 'show_completed');
                if (!$showCompletedFlag) {
                    $q->where('show_completed', null);
                }
            }
        ])->select('id', 'bid_id', 'job_id', 'created_at', 'status') // Fetch main fields for BuyerCounterOffer
            ->where('sender_id', $user->id)
            ->whereIn('status',$status)
            ->whereHas('job', function ($q) use ($showCompletedFlag,$search) {
                if (!$showCompletedFlag) {
                    $q->where('show_completed', null)
                    ->when(!is_null($search), function ($query) use ($search) {
                        $query->where('title', 'LIKE', "%{$search}%");
                    });
                }
            });

        // Apply search filter if provided
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Order by the most recent offers
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $offers = $query->paginate(10);

        // Transform the collection to include the necessary data
        $offers->getCollection()->transform(function ($item) {
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

        return $offers;
    }

    public function activeJobList(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Active Jobs";
        $status = [1];
        $offers = $this->buyerSendOffer($request, $user, $status);
        if ($request->ajax()) {
            return view('templates.basic.user.buyer.components.hire-employee.partials.active-jobs', compact('pageTitle', 'offers'));
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.buyer.components.hire-employee.active-jobs', compact('pageTitle', 'offers'));
        }
    }

    public function completedJobList(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $user = Auth::user();
        $pageTitle = "Completed Jobs";
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not specified
        $jobs = Job::where('show_completed', 1)->select('id', 'title', 'budgettype')->get();
        // Prepare an array to store valid job IDs
        $validJobIds = $jobs->pluck('id');
        $query = Buyercounteroffer::whereIn('job_id', $validJobIds)->where('sender_id', $user->id)->where('status', 1);
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
            $item->encUserBiddingId =  encrypt($item->id);

            $item->username = optional($item->user)->name ?? 'N/A'; // Use optional() to avoid errors

            return $item;
        });

        // Handle AJAX response
        if ($request->ajax()) {
            return view('templates.basic.user.buyer.components.hire-employee.partials.completed-jobs', compact('pageTitle', 'data'));
        } else {
            // If it's not an AJAX request, redirect to the full page
            return view('templates.basic.user.buyer.components.hire-employee.completed-jobs', compact('pageTitle', 'data'));
        }
    }



    public function activeJobById(Request $request, $id)
    {
        dd($id);
        $user = Auth::user();
        $singlejob = Job::where('id', decrypt($id))->first();

        if (!$singlejob) {
            return redirect()->back();
        }

        $pageTitle = "Hire employ bids";

        return response()->json($data);
    }

    public function workroom(Request $request, $encryptedId)
    {
        $jobId = Crypt::decrypt($encryptedId);
        $user = Auth::user();
        // Fetch the user bidding data
        $acceptedBid = Acceptedbid::with(['job','seller','userBiding'])->where('job_id',$jobId)->where('buyer_id', $user->id)->first();
        if (!$acceptedBid) {
            return redirect()->back()->withNotify([['error', 'Bidding not found.']]);
        }

        // Fetch the seller data using user_id from userbiding
        $seller = $acceptedBid->seller; // Using the relationship

        if (!$seller) {
            return redirect()->back()->with([['error', 'Seller not found.']]);
        }
        $job = $acceptedBid->job;
        $buyerId = $acceptedBid->buyer_id;
        $sellerId = $acceptedBid->seller_id;
        $isCompleted = $job->show_completed;
        // Initialize total budget
        $totalBudget = 0;
        // Calculate total budget and payment details
        [$totalBudget, $pendingPayment, $paidAmount, $remainingAmount] = $this->calculateBudgetAndPayments($acceptedBid);
        $buyerCounterOffer = Buyercounteroffer::where('job_id',$jobId)->where('sender_id', $user->id)->where('status',1)->first();
        // Prepare data for the view
        $job_type = $buyerCounterOffer->type; // Use type if it exists
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $startDate = $acceptedBid->created_at;
        $status = $job->booking->working_status_text;
        $pageTitle = "Workroom";
        $encJobId = encrypt($job->id);
        // Pass the data to the view
        return view('templates.basic.user.buyer.workroom_list', compact(
            'status',
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
            'buyerId',
            'sellerId',
            'isCompleted',
            'encJobId'
        ));
    }
    private function calculateBudgetAndPayments($acceptedBid)
    {
        $totalBudget = 0;
        $pendingPayment = 0;
        $paidAmount = 0;
        $remainingAmount = 0;

        $offerData = BuyerCounterOfferData::where('bid_id', $acceptedBid->bid_id)->where('status', 1)->get();
        $totalBudget = $offerData->sum('amount');
        $booking = $acceptedBid->job->booking;

        if ($booking) {
            // Fetch transactions related to the booking
            $transactions = $booking->transactions()->where('user_id', $acceptedBid->seller_id)->get();

            // Calculate paid amount
            if ($transactions->isNotEmpty()) {
                $regularTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') === false;
                });

                $commissionTransactions = $transactions->filter(function ($transaction) {
                    return strpos($transaction->details, 'Platform commission') !== false;
                });

                $paidAmount = $transactions->sum('amount'); // Sum transaction amounts

                // Calculate commission amount
                $commissionAmount = $commissionTransactions->sum('amount');
            }
        }
        $pendingPayment = max(0, $totalBudget - $paidAmount);
        $remainingAmount = max(0, $totalBudget - $paidAmount);

        return [$totalBudget, $pendingPayment, $paidAmount, $remainingAmount];
    }
    public function workroomOverview(Request $request, $encryptedId)
    {

        $userbidingId = Crypt::decrypt($encryptedId);


        $query = Userbiding::where('id', $userbidingId)
            ->with(['job', 'job.user', 'buyerCounterOffer', 'buyerCounterOffer.offerdata', 'user', 'projectPayments']);


        $search = $request->input('search');
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }


        $page = $request->input('page', 1);
        $query->orderBy('created_at', 'desc');
        $data = $query->paginate(10, ['*'], 'page', $page);


        $data->getCollection()->transform(function ($item) {
            $item->encrypted_job_id = Crypt::encrypt($item->job_id);
            $item->created_date = Carbon::parse($item->created_at)->format('Y-m-d');
            $item->offered_amount_text = $item->job->budgettype == 'per_hour' ? '$' . $item->offered_amount . ' /hr' : '$' . $item->offered_amount;
            $item->username = $item->user->name;


            $totalPayments = $item->projectPayments->sum('payment');
            $remainingPayment = $item->bid_price ? $item->bid_price - $totalPayments : 0;


            $item->budget = $item->bid_price;
            $item->pending = $remainingPayment;
            $item->paid = $totalPayments;
            $item->remaining = $remainingPayment;

            // Add project payments data
            $item->project_payments = $item->projectPayments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'payment' => $payment->payment,
                    'created_at' => Carbon::parse($payment->created_at)->format('Y-m-d'),
                ];
            });

            return $item;
        });

        // Return the transformed data as JSON
        return response()->json($data);
    }
    public function workroomProject(Request $request, $encryptedId)
    {
        $jobId = Crypt::decrypt($encryptedId);
        $user = Auth::user();
        $pageTitle = "By Project";
        $acceptedBid = Acceptedbid::where('job_id',$jobId)->where('buyer_id', $user->id)->first();
        if (!$acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        $seller = $acceptedBid->seller; // Using the relationship
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }
        $job = $acceptedBid->job;
        $encJobId = encrypt($job->id);
        $encBidId = encrypt($acceptedBid->bid_id); // here is my code

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('status', 1)->first();
        foreach ($buyerCounterOffer->buyerCounterOfferData as $offerData) {
            $buyerCounterOffersData[] = [
                "amount" => $offerData->amount,
                "due_date" => \Carbon\Carbon::parse($offerData->due_date)->format('d-m-Y'),
                "milestoneStatusText" => $offerData->getMilestoneStatusLabel(),
                "milestoneStatus" => $offerData->milestone_status,
                "title" => $offerData->title ?? "Project Payment",
                "encbuyerCounterOfferDataId" => encrypt($offerData->id),
                "revision" => $offerData->revisions,
                "status" => $offerData->status,
                "requestType" => $offerData->req_type,
                "disputeId" => encrypt(\DB::table('job_dispute_bid')->where('offer_id', $offerData->id)->value('id')),
            ];
        }
        $isCompleted = $job->show_completed;
        if ($request->ajax()) {
            return view('workspace.byproject', ['buyerCounterOffersData' => $buyerCounterOffersData, 'encJobId' => $encJobId,'isCompleted'=> $isCompleted]);
        }
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $job_type  =  $buyerCounterOffer->type;

        $buyerId = $buyerCounterOffer->sender_id;
        $sellerId  = $buyerCounterOffer->reciever_id;
        $dataId   = $buyerCounterOffer->reciever_id;
        // $breadcrumbs = [
        //     ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
        //     ['title' => 'Offered Jobs', 'url' => '/user/buyer/hire/offered-jobs'],
        //     ['title' => 'Workspace Timesheet', 'url' => ''],
        // ];
        return view(
            'workspace.full_byproject',
            ['buyerCounterOffersData' => $buyerCounterOffersData, 'encJobId' => $encJobId],
            compact('jobTitle', 'jobDescription', 'jobDescription', 'seller', 'isCompleted', 'buyerId', 'sellerId', 'dataId', 'job_type', 'pageTitle','encBidId')
        );
    }
    public function workroomMilestone(Request $request, $encryptedId)
    {
        $jobId = Crypt::decrypt($encryptedId);
        $user = Auth::user();
        $acceptedBid = Acceptedbid::where('job_id',$jobId)->where('buyer_id', $user->id)->first();
        if (!$acceptedBid) {
            return redirect()->back()->with('error', 'Bidding not found.');
        }
        $encBidId = Crypt::encrypt($acceptedBid->bid_id);
        $seller = $acceptedBid->seller; // Using the relationship
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found.');
        }
        $pageTitle = "By Project";
        // Fetch the user bidding with all necessary relationships
        $job = $acceptedBid->job;
        $encJobId = encrypt($job->id);

        $buyerCounterOffer = Buyercounteroffer::where('job_id', $jobId)->where('sender_id', $user->id)->where('status', 1)->first();

        foreach ($buyerCounterOffer->buyerCounterOfferData as $dataRow) {
            $disputeId  = \DB::table('job_dispute_bid')->where('offer_id', $dataRow->id)->value('id');

            $milestone = [
                'id' => $dataRow->id,
                'title' => $dataRow->title,
                'amount' => $dataRow->amount,
                'days' => $dataRow->days,
                'revision' => $dataRow->revisions,
                'milestoneStatus' => $dataRow->milestone_status,
                'due_date' => \Carbon\Carbon::parse($dataRow->due_date)->format('d-m-Y'),
                'milestoneStatusText' => $dataRow->getMilestoneStatusLabel(),
                "encbuyerCounterOfferDataId" => encrypt($dataRow->id),
                "disputeId" => encrypt($disputeId),
                'requestType' => $dataRow->req_type,
            ];

            if ($milestone['milestoneStatus'] == 2 || $milestone['milestoneStatus'] == 6) {
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
            "milestones" => $milestones,
        ];

        $pageTitle = "Milestone";
        $jobTitle = $job->title;
        $jobDescription = $job->description;
        $job_type =  $buyerCounterOffer->type;

        $isCompleted = $job->show_completed;
        $buyerId = $buyerCounterOffer->sender_id;
        $sellerId  = $buyerCounterOffer->reciever_id;
        $dataId   = $buyerCounterOffer->reciever_id;
        if ($request->ajax())
            return view('workspace.bymilestone', compact('data', 'encJobId','isCompleted'));

        return view('workspace.full_bymilestone', compact(
            'data',
            'encJobId',
            'jobTitle',
            'jobDescription',
            'job_type',
            'pageTitle',
            'isCompleted',
            'buyerId',
            'sellerId',
            'dataId',
            'seller',
            'encBidId'
        ));
    }
    public function workroomTimesheet(Request $request, $encryptedId)
    {

        $userbidingId = Crypt::decrypt($encryptedId);


        $query = UserTimesheet::where('bid_id', $userbidingId);


        $search = $request->input('search');
        if ($search) {
            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }


        $page = $request->input('page', 1);
        $query->orderBy('created_at', 'desc');
        $data = $query->paginate(10, ['*'], 'page', $page);


        $data->getCollection()->transform(function ($item) {


            $title = $item->title;
            $startDate = $item->start_date;
            $endDate = $item->end_date;
            $hours = $item->hours;
            $minutes = $item->minutes;
            $coverletter = $item->coverletter;
            $attachment = $item->attachment;



            return $item;
        });


        return response()->json($data);
    }
    public function getByProjectWork($dataId)
    {
        // Fetch all work data records associated with the data_id
        $dataId = decrypt($dataId);
        $workDataList = Jobworkdeliverybid::where('data_id', $dataId)->orderBy('created_at', 'desc')->get();

        if ($workDataList->isEmpty()) {
            return response()->json(['error' => 'Work data not found.'], 404);
        }

        // Get associated buyer counter offer data (single record)
        $counterOfferData = Buyercounterofferdata::find($dataId);

        // Prepare the data to pass to the view
        $data = [

            'title' => "Project Payment",
            'workDataList' => $workDataList->map(function ($workData) {
                return [
                    "workSubmittedOn" => \Carbon\Carbon::parse($workData->created_at)->format('d M Y'),
                    "attachment" => $workData->file,
                    "coverLetter" => $workData->cover,
                    "id" => $workData->id,
                    "reason" => $workData->reason,
                    "status" => $workData->status,
                    "statusText" => $workData->getStatusLabel(),
                ];
            }),
        ];
        // Load a view to display work details
        return view('workspace.byprojectworkdetails', compact('data'));
    }
    public function getByMilestoneWork($dataId)
    {
        $workDelivery = Jobworkdeliverybid::find($dataId);

        if (!$workDelivery) {
            // Handle the case where no work delivery is found
            return redirect()->back()->withErrors('Work delivery not found.');
        }

        $title = $workDelivery->milestone_title; // Get the title from the work delivery
        $counterOfferId = $workDelivery->data_id; // Get the associated counter offer ID

        // Fetch all work data records associated with the counter offer ID and the milestone title
        $workDataList = Jobworkdeliverybid::where('data_id', $counterOfferId)->orderBy('created_at', 'desc')->get();

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
                    "workSubmittedOn" => \Carbon\Carbon::parse($workData->created_at)->format('d M Y'),
                    "attachment" => $workData->file,
                    "coverLetter" => $workData->cover,
                    "id" => $workData->id,
                    "reason" => $workData->reason,
                    "status" => $workData->status,
                    "statusText" => $workData->getStatusLabel(),
                ];
            }),
        ];
        // Load a view to display work details
        return view('workspace.byprojectworkdetails', compact('data'));
    }

    public function closeJob($encrypted_job_id)
    {
        $job_id = decrypt($encrypted_job_id);
        $job = Job::find($job_id);
        if(!$job)
        {
            $notify[] = ['error', 'Something went wrong'];
            return redirect()->back()->withNotify($notify);
        }
        $job->status = 2;
        $job->save();
        $notify[] = ['success', 'Job Closed Successfully'];
        return redirect()->back()->withNotify($notify);
    }
}
