<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Freelancermembership;

class FreelancermembershipController extends Controller
{

    public function index()
    {
        $pageTitle = "Freelancer Member Ship details";
    	$emptyMessage = "No data found";

        $membership_details = Freelancermembership::paginate(getPaginate());
        return view('admin.membership.freelancer',compact('pageTitle','emptyMessage','membership_details'));
    }

    public function redirect()
    {
        $pageTitle = "Select Membership type";
    	$emptyMessage = "No data found";
        return view('admin.membership.addredirect',compact('pageTitle','emptyMessage'));
    }
    public function addredirect(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);
        if($request->type == 0){
            return redirect(url('/admin/membership/package-plan'));
        }else{
            return redirect(url('/admin/membership/addemployer'));
        }
    }
    public function addfreelancer(Request $request)
    {
        $pageTitle = "Add Freelancer Packages";
    	$emptyMessage = "No data found";
        $type = $request->query('type');
        $check_default = Freelancermembership::where('default',1)->count();

        return view('admin.membership.add_freelacer',compact('pageTitle','emptyMessage','check_default','type'));
    }

    public function store(Request $request){
        //echo "<pre>";print_r($request->all());exit;
        // $request->validate([
        //     'title' => 'required|string',
        //     'price' => 'required',
        //     'fee' => 'required',
        //     'bids' => 'required',
        //     'skills' => 'required',
        //     'p_size' => 'required',
        //     'profile_display' => 'required',
        //     'featured_services' => 'required',
        //     'priority_services' => 'required',
        //     'normal_services' => 'required',
        //     'r_assistance' => 'required',
        //     'safe_payment' => 'required',
        //     'support' => 'required'
        // ]);
        $addmember = new Freelancermembership();
        $addmember->title = $request->title;
        $addmember->price = $request->price;

        $addmember->service_cost = $request->service_cost;
        $addmember->normal_services = $request->normal_services;
        $addmember->featured_services = $request->featured_services;
        $addmember->annual_services_rollover = $request->annual_services_rollover;
        $addmember->purchase_service = $request->purchase_service;

        $addmember->fee = $request->fee;
        $addmember->bids = $request->bids;
        $addmember->featuredjobquotes = $request->featuredjobquotes;
        $addmember->annual_bids_rollover = $request->annual_bids_rollover;
        $addmember->purchase_additional_bids = $request->purchase_additional_bids;


        $addmember->portfolio_size = $request->p_size;
        $addmember->verifiedaccount = $request->verifiedaccount;
        $addmember->support = $request->support;
        $addmember->safe_payment = $request->safe_payment;

        $addmember->freelancer_payment_plan_id = $request->freelancer_payment_plan;

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
        // $request->validate([
        //     'title' => 'required|string',
        //     'price' => 'required',
        //     'fee' => 'required',
        //     'bids' => 'required',
        //     'skills' => 'required',
        //     'p_size' => 'required',
        //     'profile_display' => 'required',
        //     'featured_services' => 'required',
        //     'priority_services' => 'required',
        //     'normal_services' => 'required',
        //     'r_assistance' => 'required',
        //     'safe_payment' => 'required',
        //     'support' => 'required'
        // ]);
        $addmember = Freelancermembership::where('id',$request->id)->update([
        'title' => $request->title,
        'price' => $request->price,
        'service_cost' => $request->service_cost,
        'normal_services' => $request->normal_services,
        'featured_services' => $request->featured_services,
        'annual_services_rollover' => $request->annual_services_rollover,
        'purchase_service' => $request->purchase_service,
        'fee' => $request->fee,
        'bids' => $request->bids,
        'featuredjobquotes' => $request->featuredjobquotes,
        'annual_bids_rollover' => $request->annual_bids_rollover,
        'purchase_additional_bids' => $request->purchase_additional_bids,
        'portfolio_size' => $request->p_size,
        'verifiedaccount' => $request->verifiedaccount,
        'safe_payment' => $request->safe_payment,
        'support' => $request->support
        ]);

        $notify[] = ['success', 'Member Ship has been Updated.'];
        return back()->withNotify($notify);
    }
    public function getPackagePlan()
    {
        $pageTitle = "Select Packages Monthly or Annually";
    	$emptyMessage = "No data found";
        return view('admin.membership.add_package',compact('pageTitle','emptyMessage'));
    }
    public function package()
    {
        $pageTitle = "Select Packages Monthly or Annually";
    	$emptyMessage = "No data found";
        return view('admin.membership.package',compact('pageTitle','emptyMessage'));
    }

    public function createPackage(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);
        return redirect(url('/admin/membership/addfreelancer?type=' . $request->type));
    }
    public function addPackage(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);
        return redirect(url('/admin/membership/addfreelancer?type=' . $request->type));
    }
    public function getFreelancerPackage(Request $request)
    {
        $pageTitle = "Freelancer Membership Details";
        $emptyMessage = "No data found";

        // Get the type parameter from the query string
        $type = $request->query('type');

        // Fetch membership details based on the type
        $membership_details = Freelancermembership::where('freelancer_payment_plan_id', $type)->paginate(getPaginate());

        return view('admin.membership.freelancer', compact('pageTitle', 'emptyMessage', 'membership_details'));
    }
    public function annually(Request $request)
    {
        $pageTitle = "Freelancer Member Ship details";
    	$emptyMessage = "No data found";
        $type = $request->query('type');
        $membership_details = Freelancermembership::where('freelancer_payment_plan_id', $type)->paginate(getPaginate());
        return view('admin.membership.freelancer',compact('pageTitle','emptyMessage','membership_details'));
    }
}
