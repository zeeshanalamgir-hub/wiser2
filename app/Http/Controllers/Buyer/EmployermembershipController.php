<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employermembershiptransactions;
use App\Models\Employermembership;
use App\Models\Employerservicescount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployermembershipController extends Controller
{
    //
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
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
        $pageTitle = 'Employer Membership';
        $emptyMessage = "No data found";
        $user = Auth::user();
        //get user current memeber
        $usermembership = Employermembershiptransactions::where('user_id', $user->id)->select('membership_id')->get();
        $currentmembership_id =  null;
        if($usermembership->count() > 0)
            $currentmembership_id = $usermembership[0]->membership_id;
        $membership = Employermembership::get();
        $arr = ["primary", "info", "success", "danger"];
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'Memberships'],
        ];
        return view($this->activeTemplate . 'user.buyer.membership.index', compact('pageTitle','breadcrumbs' ,'emptyMessage', 'membership', 'currentmembership_id', 'arr'));
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
            return redirect(url('/user/buyer/dashboard'));
        }
        $membership =   Employermembership::where('id', $new_id)->get();
        return view($this->activeTemplate . 'user.buyer.membership.payment_page', compact('pageTitle', 'emptyMessage', 'current_id', 'new_id', 'membership'));
    }
    public function updatemembership(Request $request)
    {
        $user = Auth::user();
        $end_at = Carbon::now()->addMonth()->format('Y-m-d');

        $updatemember = Employermembershiptransactions::where('user_id', $user->id)->update([
            'membership_id' => $request->new_id,
            'start_at' => date('Y-m-d'),
            'end_at' => $end_at
        ]);
        //getting new membership det    ail
        $membership =   Employermembership::where('id', $request->new_id)->first();
        $user = User::where('id', $user->id)->first();
        $balance = substr($user->balance, 0, -9);
        $membershipprice = substr($membership->price, 0, 2);
        if ($balance < $membershipprice) {
            return redirect(url('/user/buyer/dashboard'))->with('error', 'Out of balance');
        } else {
            $user->balance = $balance - $membershipprice;
            $user->save();
        }
        //getting user services detail
        //$getservices = Employerservicescount::where('user_id', $user->id)->first();

        // $updateservices = Employerservicescount::where('user_id', $user->id)->update([
        //     'normal_jobs' => $getservices->normal_jobs + $membership->normal_jobs,
        //     'invites' => null,
        //     'featured_jobs' => $getservices->featured_jobs + $membership->featured_jobs,
        //     'updated_at' => date('Y-m-d H:i:s')
        // ]);

        //19 feb 2026
        $updateservices = Employerservicescount::where('user_id', $user->id)->update([
            'normal_jobs' => $membership->normal_jobs,
            'invites' => $membership?->invites ?? 0,
            'featured_jobs' => $membership->featured_jobs,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        //add transaction
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $membership->price;
        $transaction->details = "Membership upgraded to " . $membership->title;
        $transaction->trx_type = '-';
        $transaction->post_balance = $user->balance;
        $transaction->trx = getTrx();
        $transaction->save();
        sendJobAdminRealTimeNotification($user, 'BUYER_MEMBERSHIP_UPGRADE', '#');

        return redirect('/user/buyer/dashboard')->withNotify(['success' => 'Membership Upgraded successfully!']);
    }
}
