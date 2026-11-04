<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bidpakages;
use App\Models\User;

use App\Models\BidsLog;
use App\Models\Transaction;
use App\Models\Freelancerservicescount;

class BidspackagesController extends Controller
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
        $pageTitle = 'Freelancer Bids Pakages';
        $emptyMessage = "No data found";
        $user = Auth::user();
        //get user current memeber
        $bidspakages = Bidpakages::get();
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
            ['title' => 'Bids Packages'],
        ];
        return view($this->activeTemplate . 'user.seller.bidspackages.index', compact('pageTitle', 'emptyMessage','bidspakages','breadcrumbs'));
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
        $id = $request->id;
        if($id == ""){
            return redirect(url('/user/seller/dashboard'));
        }
        $bidspakages =   Bidpakages::where('id',$id)->get();
        return view($this->activeTemplate . 'user.seller.bidspackages.payment_page', compact('pageTitle', 'emptyMessage','id','bidspakages'));
    }
    public function updatemembership(Request $request){

        $user = Auth::user();
        $id = $request->id;
        $bidspakages = Bidpakages::find($id);

        if( $user->balance < $bidspakages->price ) {
            $notify[] = ['error', 'Out of Balance'];
            return back()->withNotify($notify);
        }

        $getbids = Freelancerservicescount::where('user_id',$user->id)->first();

        $transaction_arr[] = [
            'user_id' => $user->id,
            'bids' => $bidspakages->bids,
            'tr_op' => '+',
            'post_balance' => $getbids->bids+$bidspakages->bids,
            'details' => 'Bids Purchase',
        ];
        BidsLog::insert($transaction_arr);

        $updateservices =Freelancerservicescount::find($getbids->id);
        $updateservices->bids = $getbids->bids+$bidspakages->bids;
        $updateservices->normal_services = $getbids->services+$bidspakages->services;
        $updateservices->featured_services = $getbids->f_services+$bidspakages->f_services;
        $updateservices->save();

        $user=User::where('id',$user->id)->first();
        $balance=substr($user->balance,0,-9);
        $membershipprice=substr($bidspakages->price,0,2);

        $user->balance=$balance-$membershipprice;
        $user->save();

        //add transaction
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $bidspakages->price;
        $transaction->details = $bidspakages->title." Package purchased successfully!";
        $transaction->trx_type = '-';
        $transaction->post_balance = $user->balance;
        $transaction->trx = getTrx();
        $transaction->save();
        sendJobAdminRealTimeNotification($user,'SELLER_BIDS_UPGRADE','#');
        $notify[] = ['success', 'Bids Purchased successfully!'];
        return redirect(url('/user/seller/dashboard'))->withNotify($notify);
    }
}
