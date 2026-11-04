<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobpakages;
use App\Models\Employerservicescount;
use App\Models\Transaction;

use App\Models\BidsLog;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class JobspackagesController extends Controller
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
        $pageTitle = 'Employer Packages';
        $emptyMessage = "No data found";
        $user = Auth::user();
        //get user current memeber
        $jobspakages = Jobpakages::get();

        return view($this->activeTemplate . 'user.buyer.jobspackages.index', compact('pageTitle', 'emptyMessage','jobspakages'));
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
            return redirect(url('/user/buyer/dashboard'));
        }
        $bidspakages =   Jobpakages::where('id',$id)->get(); 
        return view($this->activeTemplate . 'user.buyer.jobspackages.payment_page', compact('pageTitle', 'emptyMessage','id','bidspakages')); 
    }
    public function updatemembership(Request $request){
        
      
        $user = Auth::user();
        $id = $request->id;
        $Jobpakages =   Jobpakages::where('id',$id)->get(); 
        // echo $Jobpakages[0]->type;exit;
        // if($Jobpakages[0]->type == 0){
        //     $coloum_name = "normal_jobs";
        // }else if($Jobpakages[0]->type == 1){
        //     $coloum_name = "priority_jobs";
        // }else if($Jobpakages[0]->type == 2){
        //     $coloum_name = "featured_jobs";
        // }else if($Jobpakages[0]->type == 3){
        //     $coloum_name = "invites";
        // }
       
        // $updateservices = Employerservicescount::where('user_id',$user->id)->increment($coloum_name, $Jobpakages[0]->jobs);
        $getuserjobs=Employerservicescount::where('user_id',$user->id)->first();
        $up_tab=Employerservicescount::find($getuserjobs->id);
        $up_tab->normal_jobs=$getuserjobs->normal_jobs + $Jobpakages[0]->jobs;
        $up_tab->featured_jobs=$getuserjobs->featured_jobs + $Jobpakages[0]->f_jobs;
        if($Jobpakages[0]->invites != 'unlimited'){
            if($up_tab->invites == 'unlimited'){
            $up_tab->invites=$Jobpakages[0]->invites;
        }else{
            $up_tab->invites=$getuserjobs->invites + $Jobpakages[0]->invites;
        }
        }else{
            $up_tab->invites='unlimited';
        }
        $up_tab->save();

        
          $transaction_arr[] = [
            'user_id' => $user->id,
            'bids' => $Jobpakages[0]->jobs,
            'tr_op' => '+',
            'post_balance' => $getuserjobs->normal_jobs+$getuserjobs->priority_jobs+$getuserjobs->featured_jobs,
            'details' => 'Job Purchased',
                   
             ];
         BidsLog::insert($transaction_arr);
          $user=User::where('id',$user->id)->first();
        $balance=substr($user->balance,0,-9);
        $membershipprice=substr($Jobpakages[0]->price,0,2);
        if($balance<$membershipprice)
        {
            return redirect(url('/user/buyer/dashboard'))->with('error', 'Out of balance');
        }else
        {
            $user->balance=$balance-$membershipprice;
            $user->save();
        }
          //add transaction
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $Jobpakages[0]->price;
        $transaction->details = $Jobpakages[0]->title." Package purchased successfully!";
        $transaction->trx_type = '-';
        $transaction->post_balance = $user->balance;
        $transaction->trx = getTrx();
        $transaction->save();
        sendJobAdminRealTimeNotification($user, 'BUYER_JOB_PACKAGE_UPGRADE', '#');
        sendAdminRealTimeNotification($user, ' update the membership by '. $user->username, route('admin.users.detail',['id'=>$user->id]));


        return redirect(url('/user/buyer/dashboard'))->with('message', 'Jobs Purchased successfully!');
        
    }
}