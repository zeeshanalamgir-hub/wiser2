<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Usermembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MembershipController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
         
    }
    public function index($id)
    {
        $pageTitle = 'Membership';
        $emptyMessage = "No data found";
        $user = Auth::user();
        if($id == 0)
        {
            $membership =   Membership::where('type',0)->get();  
            $usermembership = Usermembership::join('membership','user_membership.membership_id','=','membership.id')->where('user_id',$user->id)->where('membership.type',0)->select('membership.id as m_id')->get();
            $current_membership = $usermembership[0]->m_id;
        }else if($id == 1){
            $membership =   Membership::where('type',1)->get();  
            $usermembership = Usermembership::join('membership','user_membership.membership_id','=','membership.id')->where('user_id',$user->id)->where('membership.type',1)->select('membership.id as m_id')->get();
            $current_membership = $usermembership[0]->m_id; 
        }else{
            return redirect(url('/user/seller/dashboard'));
        }
        return view($this->activeTemplate . 'user.membership_page', compact('pageTitle', 'emptyMessage','membership','current_membership'));
    }
    public function payment(Request $request)
    {
        $pageTitle = 'Payment';
        $emptyMessage = "No data found";
        $current_id = $request->c_id;
        $new_id = $request->id;
        if($current_id == "" || $new_id == ""){
            return redirect(url('/user/seller/dashboard'));
        }
        $membership =   Membership::where('id',$new_id)->get(); 
        return view($this->activeTemplate . 'user.payment_page', compact('pageTitle', 'emptyMessage','current_id','new_id','membership')); 
    }
    public function updatemembership(Request $request){
        $user = Auth::user();
      
        $updatemember = Usermembership::where('user_id',$user->id)->where('membership_id',$request->current_id)->update([
            'membership_id' => $request->new_id,
            'start_at' => date('Y-m-d'),
            'end_at' => date('Y-m-d', strtotime("+30 days"))
        ]);
        if($request->type == 1){
            return redirect(url('/user/buyer/dashboard'))->with('message', 'Membership Upgraded successfully!');
        }else{
            return redirect(url('/user/seller/dashboard'))->with('message', 'Membership Upgraded successfully!');
        }
        
    }
}
