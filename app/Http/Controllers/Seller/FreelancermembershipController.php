<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancermembership;
use App\Models\Freelancerservicescount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FreelancermembershipController extends Controller
{
    //
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    public function getMembeship() {}
    public function index(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = 'Freelancer Membership';
        $emptyMessage = "No data found";
        $user = Auth::user();
        //get user current memeber
        $usermembership = Freelancermembershiptransactions::where('user_id', $user->id)->select('membership_id')->get();
        $currentmembership_id = Freelancermembership::where('default', 1)->pluck('id')->first();
        if($usermembership->count() > 0)
            $currentmembership_id = $usermembership[0]->membership_id;
        $membership = Freelancermembership::get();
        $arr = ["primary", "info", "success", "danger"];
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Memberships'],
        ];

        return view($this->activeTemplate . 'user.seller.membership.index', compact('pageTitle', 'emptyMessage', 'membership', 'currentmembership_id', 'arr','breadcrumbs'));
    }
    public function payment(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = 'Payment';
        $emptyMessage = "No data found";
        $current_id = $request->c_id;
        $new_id = $request->id;
        if ($current_id == "" || $new_id == "") {
            return redirect(url('/user/seller/dashboard'));
        }
        $membership =   Freelancermembership::where('id', $new_id)->get();
        return view($this->activeTemplate . 'user.seller.membership.payment_page', compact('pageTitle', 'emptyMessage', 'current_id', 'new_id', 'membership'));
    }
    public function updatemembership(Request $request)
    {
        $user = Auth::user();
        $membership = Freelancermembership::where('id', $request->new_id)->first();
        $user = User::where('id', $user->id)->first();

        if (!$membership) {
            return redirect(url('/user/seller/freelancermembership'))->withNotify(['error' => 'Invalid membership selected.']);
        }

        // Calculate end date based on freelance_payment_plan_id
        $end_at = $membership->freelancer_payment_plan_id == 2
            ? Carbon::now()->addYear()->format('Y-m-d')  // Yearly
            : Carbon::now()->addMonth()->format('Y-m-d'); // Monthly
        $balance = substr($user->balance, 0, -9);
        $membershipprice = substr($membership->price, 0, 2);

        if ($balance < $membershipprice) {
            return redirect(url('/user/seller/freelancermembership'))->withNotify(['error' => 'Out of balance!']);
        } else {
            $user->balance = $balance - $membershipprice;
            $user->save();
        }

        $updatemember = Freelancermembershiptransactions::where('user_id', $user->id)->update([
            'membership_id' => $request->new_id,
            'start_at' => date('Y-m-d'),
            'end_at' => $end_at
        ]);

        $getservices = Freelancerservicescount::where('user_id', $user->id)->first();
        if($getservices) {
            $updateservices = Freelancerservicescount::where('user_id', $user->id)->update([
                'bids' => $getservices->bids + $membership->bids,
                'featured_job_bids' => $getservices->featured_service_bids + $membership->featuredjobquotes,
                'normal_services' => $getservices->normal_services + $membership->normal_services,
                'priority_services' => $getservices->priority_services + $membership->priority_services,
                'featured_services' => $getservices->featured_services + $membership->featured_services,
                'annual_bids_rollover' => $getservices->annual_bids_rollover + $membership->annual_bids_rollover,
                'portfolio_size' => $getservices->portfolio_size + $membership->portfolio_size,
                'annual_services_rollover' => $getservices->annual_services_rollover + $membership->annual_services_rollover,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $updateservices = Freelancerservicescount::where('user_id', $user->id)->update([
                'bids' => $membership->bids,
                'featured_job_bids' => $membership->featuredjobquotes,
                'normal_services' => $membership->normal_services,
                'priority_services' => $membership->priority_services,
                'featured_services' => $membership->featured_services,
                'annual_bids_rollover' => $membership->annual_bids_rollover,
                'portfolio_size' => $membership->portfolio_size,
                'annual_services_rollover' => $membership->annual_services_rollover,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }


        // Add transaction
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $membership->price;
        $transaction->details = "Membership upgraded to " . $membership->title;
        $transaction->trx_type = '-';
        $transaction->trx = getTrx();
        $transaction->post_balance = $user->balance;
        $transaction->save();

        sendJobAdminRealTimeNotification($user, 'SELLER_MEMBERSHIP_UPGRADE', '#');


        return redirect('/user/seller/dashboard')->withNotify(['success' => 'Membership Upgraded successfully!']);
    }
}
