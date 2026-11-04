<?php

namespace App\Services;

use App\Models\BidsLog;
use App\Models\Employermembershiptransactions;
use Illuminate\Support\Facades\Auth;

use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancerservicescount;
use App\Models\User;
use App\Models\Userbiding;
use Carbon\Carbon;

class MembershipService
{

    public function isUserBiddingAvailable($userId)
    {
        try {
            $membership = $this->isUserMembershipActive($userId);
            if (!$membership) {
                return ['status' => false, 'message' => 'User does not have an active membership.'];
            }

            // Check freelancer services count
            $freelancerServicesCount = Freelancerservicescount::where('user_id', $userId)->first();

            if (!$freelancerServicesCount) {
                return ['status' => false, 'message' => 'No bid count record found for this user.'];
            }

            $totalBids = $freelancerServicesCount->bids;

            if ($totalBids > 0) {
                return ['status' => true, 'message' => 'User has available bids.'];
            }

            return ['status' => false, 'message' => 'User has no available bids.'];
        } catch (\Exception $e) {
            \Log::error('Error checking user bidding availability', [
                'user_id' => $userId,
                'error_message' => $e->getMessage()
            ]);
            return ['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function getMembershipFee($userId)
    {
        if (!$userId) {
            return -1;
        }
        $user = User::where(['id' => $userId])->first();
        if (!$user) {
            return -1;
        }

        if ($user->is_freelancer == 1) {
            $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->get();
            if(count($usermembership) == 0){
                return 0;
            }
            return $usermembership[0]->fee;
        }

        if ($user->is_employer == 1) {
            $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            if(count($usermembership1) == 0){
                return 0;
            }
            return $usermembership1[0]->fee;
        }

        return 0;

    }

    public function isUserMembershipActive($userId)
    {
        if (!$userId) {
            return false;
        }

        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        $usermembership = null;

        if ($user->is_freelancer == 1) {
            $usermembership = Freelancermembershiptransactions::join('freelancer_membership', 'freelancer_membership_transactions.membership_id', '=', 'freelancer_membership.id')->where('user_id', $user->id)->orderBy('freelancer_membership_transactions.id', 'desc')->first();

        }
        if ($user->is_employer == 1) {
            $usermembership = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->orderBy('employer_membership_transactions.id', 'desc')->first();
        }

        if (!$usermembership) {
            return false;
        }

        // if end_at is less then today return false else return true;
        $endAt = Carbon::parse($usermembership->end_at);
        $now = Carbon::now();

        if ($now->lessThanOrEqualTo($endAt)) {
            return $usermembership;
        }

        return false;
    }

}