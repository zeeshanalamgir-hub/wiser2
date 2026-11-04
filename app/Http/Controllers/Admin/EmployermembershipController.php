<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employermembership;

class EmployermembershipController extends Controller
{

    public function index()
    {
        $pageTitle = "Employer Member Ship details";
    	$emptyMessage = "No data found";

        $membership_details = Employermembership::paginate(getPaginate());
        return view('admin.membership.employer',compact('pageTitle','emptyMessage','membership_details'));
    }

    public function redirect()
    {
        $pageTitle = "Select Membership type";
    	$emptyMessage = "No data found";
        return view('admin.membership.addredirect',compact('pageTitle','emptyMessage'));
    }

    public function add()
    {
        $pageTitle = "Add Employer Packages";
    	$emptyMessage = "No data found";
        $check_default = Employermembership::where('default',1)->count();

        return view('admin.membership.add_employer',compact('pageTitle','emptyMessage','check_default'));
    }

    public function store(Request $request){
        // $request->validate([
        //     'title' => 'required|string',
        //     'price' => 'required',
        //     'fee' => 'required',
        //     'featured_services' => 'required',
        //     'priority_services' => 'required',
        //     'normal_services' => 'required',
        //     'r_assistance' => 'required',
        //     'safe_payment' => 'required',
        //     'support' => 'required',
        //     'verified' => 'required'
        // ]);
        $addmember = new Employermembership();
        $addmember->title = $request->title;
        $addmember->price = $request->price;
        $addmember->fee = $request->fee;
        $addmember->normal_jobs = $request->normal_jobs;
        // $addmember->priority_jobs = $request->priority_services;
        $addmember->invites = $request->invites;
        $addmember->jobtag = $request->jobtag;
        $addmember->featured_jobs = $request->featured_jobs;
        $addmember->r_assistance = $request->r_assistance;
        $addmember->safe_payment = $request->safe_payment;
        $addmember->support = $request->support;
        $addmember->verified = $request->verified;
        $addmember->service_platform_fee = $request->service_platform_fee;
        $addmember->expertise_matching_mechanism = $request->expertise_matching_mechanism;
        $addmember->money_deposit_for_job = $request->money_deposit_for_job;
        $addmember->communication_tools = $request->communication_tools;
        $addmember->time_tracking_features = $request->time_tracking_features;


        if($request->has('default')){
            if($request->default == 1){
                $addmember->default = 1;
            }
        }
        $addmember->save();
        $notify[] = ['success', 'Member Ship has been created.'];
        return back()->withNotify($notify);
    }
    public function update(Request $request){
        $request->validate([
            'title' => 'required|string',
            'price' => 'required',
            'fee' => 'required',
            'featured_jobs' => 'required',

            'normal_jobs' => 'required',
            'r_assistance' => 'required',
            'safe_payment' => 'required',
            'support' => 'required',
            'verified' => 'required',
        ]);
        $addmember = Employermembership::where('id',$request->id)->update([
            'title' => $request->title,
            'price' => $request->price,
            'fee' => $request->fee,
            'normal_jobs' => $request->normal_jobs,
            // 'priority_jobs' => $request->priority_jobs,
            'invites' => $request->invites,
            'jobtag' => $request->jobtag,
            'featured_jobs' => $request->featured_jobs,
            'r_assistance' => $request->r_assistance,
            'safe_payment' => $request->safe_payment,
            'support' => $request->support,
            'verified' => $request->verified,
            'service_platform_fee' => $request->service_platform_fee,
            'expertise_matching_mechanism' => $request->expertise_matching_mechanism,
            'money_deposit_for_job' => $request->money_deposit_for_job,
            'communication_tools' => $request->communication_tools,
            'time_tracking_features' => $request->time_tracking_features,
        ]);
        $notify[] = ['success', 'Member Ship has been Updated.'];
        return back()->withNotify($notify);
    }
}
