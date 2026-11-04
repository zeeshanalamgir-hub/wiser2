<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Buyercounteroffer;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\JobBiding;
use App\Models\Userbiding;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Auth;

class JobController extends Controller
{
    public function index()
    {
    	$pageTitle = "Manage All Job";
    	$emptyMessage = "No data found";
    	$jobs = Job::latest()->with('user', 'category', 'subCategory')->paginate(getPaginate());
    	return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function details($id)
    {
    	$pageTitle = "Job Details";
    	$job = Job::findOrFail($id);

        $booking = Booking::where('job_id', $job->id)->first();
        $buyerId = $booking->buyer->id ?? null;
        $sellerId = $booking->seller->id ?? null;

        $messages = DB::table('ch_messages')
            ->where('ch_messages.job_id', $job->id)
            ->join('users as from_user', 'from_user.id', '=', 'ch_messages.from_id')
            ->join('users as to_user', 'to_user.id', '=', 'ch_messages.to_id')
            ->select(
                'ch_messages.body',
                'ch_messages.from_id',
                'ch_messages.to_id',
                'ch_messages.created_at',
                'from_user.name as sender_name',
                'to_user.name as receiver_name'
            )
            ->orderBy('ch_messages.created_at', 'asc')
            ->get();
    	return view('admin.job.details', compact('pageTitle', 'job','messages','buyerId'));
    }

    protected function getJobMessages($jobId)
    {
        $booking = Booking::where('job_id', $jobId)->first();
        $buyerId = $booking->buyer->id ?? null;
        $sellerId = $booking->seller->id ?? null;

        if (!$buyerId || !$sellerId) {
            return response()->json(['error' => 'No participants found for this job.'], 404);
        }

        $messages = DB::table('ch_messages')
            ->where('ch_messages.job_id', $jobId)
            ->join('users as from_user', 'from_user.id', '=', 'ch_messages.from_id')
            ->join('users as to_user', 'to_user.id', '=', 'ch_messages.to_id')
            ->select(
                'ch_messages.body',
                'ch_messages.from_id',
                'ch_messages.to_id',
                'ch_messages.created_at',
                'from_user.name as sender_name',
                'to_user.name as receiver_name'
            )
            ->orderBy('ch_messages.created_at', 'asc')
            ->get();
        dd($messages);
        return [$messages,$buyerId,$sellerId];
    }

    public function pending()
    {
    	$pageTitle = "Pending Job";
    	$emptyMessage = "No data found";
    	$jobs = Job::where('status', 0)->latest()->with('user', 'category', 'subCategory')->paginate(getPaginate());
    	return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function approved()
    {
    	$pageTitle = "Approved Job";
    	$emptyMessage = "No data found";
    	$jobs = Job::where('status', 1)->latest()->with('user', 'category', 'subCategory')->paginate(getPaginate());
    	return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function closed()
    {
        $pageTitle = "Closed Job";
        $emptyMessage = "No data found";
        $jobs = Job::where('status', 2)->latest()->with('user', 'category', 'subCategory')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function cancel()
    {
    	$pageTitle = "Cancel Job";
    	$emptyMessage = "No data found";
    	$jobs = Job::where('status', 3)->latest()->with('user', 'category', 'subCategory')->paginate(getPaginate());
    	return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }


    public function approvedBy(Request $request)
    {
        $admin = Admin::first();
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->status = 1;
        $job->created_at = Carbon::now();
        $job->save();


        // send approval notification of admin to BUYER
        sendJobEmailWithNotification($admin->id, $job->user_id, $job->id, 'ADMIN_NOTIFY_BUYER_NEW_JOB_APPROVED',true);
        sendAdminRealTimeNotification($admin, 'Job has beed approved By '. $admin->username, route('admin.job.details',['id'=>$job->id]));
        notifyMatchedJobCategoryUsers($job);
        $this->notifyUsersAboutJob($job);
        $notify[] = ['success', 'Job has been approved'];
        return back()->withNotify($notify);
    }

    public function cancelBy(Request $request)
    {
        $admin = Admin::first();
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->status = 3;
        $job->created_at = Carbon::now();
        $job->save();
        sendJobEmailWithNotification($admin->id, $job->user_id, $job->id, 'ADMIN_NOTIFY_BUYER_NEW_JOB_CANCELLED',true);
        sendAdminRealTimeNotification($admin, 'Job has beed cancelled By '. $admin->username, route('admin.job.details',['id'=>$job->id]));

        $notify[] = ['success', 'Job has been canceled'];
        return back()->withNotify($notify);
    }


    public function closedBy(Request $request)
    {
        $admin = Admin::first();
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->status = 2;
        $job->created_at = Carbon::now();
        $job->save();

        sendJobEmailWithNotification($admin->id, $job->user_id, $job->id, 'ADMIN_NOTIFY_BUYER_NEW_JOB_CLOSED',true);
        sendAdminRealTimeNotification($admin, 'Job has beed closed By '. $admin->username, route('admin.job.details',['id'=>$job->id]));

        $notify[] = ['success', 'Job has been closed'];
        return back()->withNotify($notify);
    }


    public function jobCategory(Request $request)
    {
        if(is_null($request->category)) {
            $notify[] = ['error', 'Please select a category'];
            return back()->withNotify($notify);
        }
        $category = Category::findOrFail($request->category);
        $searchCategory = $category->id;
        $pageTitle = "Job search by category - " . $category->name;
        $emptyMessage = "No data found";
        $jobs = Job::where('category_id', $category->id)->with('category', 'user', 'subCategory')->latest()->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs', 'searchCategory'));
    }


    public function jobBiding($id)
    {
        $pageTitle = "Job Biding List";
        $emptyMessage = "No data found";

        //$jobBidings = Job::where('id', $id)->with('user')->latest()->paginate(getPaginate());
        $jobBidings = Buyercounteroffer::where('job_id', $id)->with(['buyer','seller'])->latest()->paginate(getPaginate());
        //dd($jobBidings);
        return view('admin.job.job_biding', compact('pageTitle', 'emptyMessage', 'jobBidings'));
    }


    public function jobBidingDetails($id)
    {
        $pageTitle = "Job Biding Details";
        $jobBidingDetails = Buyercounteroffer::where('id', $id)->firstOrFail();
        return view('admin.job.job_biding_details', compact('pageTitle', 'jobBidingDetails'));
    }

    public function closedJobFee(Request $request)
    {
        $pageTitle = "Closed Job Earnings";
        $emptyMessage = "No data found";
        
        $query = Job::with([
            'user',
            'booking.buyer',
            'booking.seller',
            'booking.transactions' => function ($q) {
                $q->where('details', 'like', '%Platform commission%');
            },
            'category',
            'subCategory'
        ])
        ->where('show_completed', 1);
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('username', 'LIKE', "%{$search}%")
                            ->orWhere('firstname', 'LIKE', "%{$search}%")
                            ->orWhere('lastname', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }
        
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('budget_type') && !empty($request->budget_type)) {
            $query->where('budgettype', $request->budget_type);
        }
        
        $jobs = $query->latest()->paginate(getPaginate())->appends($request->all());
        
        $categories = Category::all();
        
        return view('admin.job.job-fee-index', compact(
            'pageTitle', 
            'emptyMessage', 
            'jobs', 
            'categories',
        ));
    }


    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $jobs = Job::where(function ($jobs) use ($search) {
            $jobs->where('title', 'like', "%$search%");
        });
        $pageTitle = '';
        switch ($scope) {
            case 'approved':
                $pageTitle .= 'Approved ';
                $jobs = $jobs->where('status', 1);
                break;
            case 'pending':
                $pageTitle .= 'Pending ';
                $jobs = $jobs->where('status', 0);
                break;
            case 'closed':
                $pageTitle .= 'Cancel ';
                $jobs = $jobs->where('status', 2);
                break;
            case 'cancel':
                $pageTitle .= 'Cancel ';
                $jobs = $jobs->where('status', 3);
                break;
        }
        $jobs = $jobs->latest()->paginate(getPaginate());
        $pageTitle .= 'Job search by - ' . $search;
        $emptyMessage = 'No data found';
        return view('admin.job.index', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'jobs'));
    }
     /**
     * Notify users about the job
     */
    protected function notifyUsersAboutJob($job)
    {
        $freelancers = User::where('is_freelancer', 1)->get();

        foreach ($freelancers as $freelancer) {
            $percentage = getUserSkillsVsJobSkillsPercentage($freelancer, $job);
            \Log::error('Percentage match : ' . $percentage);
            if ($percentage > 60) {
                sendJobEmailWithNotification($job->user_id, $freelancer->id, $job->id, 'JOB_POSTING_ALERTS');
            }
        }
    }
}