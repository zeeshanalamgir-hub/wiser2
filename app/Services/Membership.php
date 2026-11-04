<?php

namespace App\Services;

use App\Models\Employermembership;
use App\Models\Employermembershiptransactions;
use App\Models\Employerservicescount;
use App\Models\Freelancermembership;
use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancerservicescount;
use Carbon\Carbon;

class Membership
{
    public function isMembershipExpired($user)
    {
        if($user->is_employer == 1) {
            return Employermembershiptransactions::where("user_id", $user->id)->where("end_at", "<", now()->format('Y-m-d'))->exists();
        }else if($user->is_freelancer == 1) {
            return Freelancermembershiptransactions::where("user_id", $user->id)->where("end_at", "<", now()->format('Y-m-d'))->exists();
        }
    }

    public function membershipExpireDate($user)
    {
        if($user->is_employer == 1) {
            return Employermembershiptransactions::where("user_id", $user->id)->value('end_at');
        }else if($user->is_freelancer == 1) {
            return Freelancermembershiptransactions::where("user_id", $user->id)->value('end_at');
        }
    }

    public function isFreeMembership($user, $membership_id)
    {
        if($user->is_employer == 1) {
            return Employermembership::where("id", $membership_id)->value('default') == 1;
        }else if($user->is_freelancer == 1) {
            return Freelancermembership::where('id',$membership_id)->value('default') == 1;
        }
    }

    public function updateEmployerMembership($user)
    {
        $end_at = Carbon::now()->addMonth()->format('Y-m-d');

        $basic_emp_membership = Employermembership::where('default', 1)->first();
        if($basic_emp_membership) {
            Employermembershiptransactions::where('user_id', $user->id)->update([
                'membership_id' => $basic_emp_membership->id,
                'start_at' => date('Y-m-d'),
                'end_at' => $end_at
            ]);

            Employerservicescount::where('user_id', $user->id)->update([
                'normal_jobs' => $basic_emp_membership->normal_jobs,
                'invites' => $basic_emp_membership?->invites ?? 0,
                'featured_jobs' => $basic_emp_membership->featured_jobs,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
    }

    public function updateFreelancerMembership($user)
    {
        $end_at = Carbon::now()->addMonth()->format('Y-m-d');

        $basic_freelancer_membership = Freelancermembership::where('default', 1)->first();
        if($basic_freelancer_membership) {
            Freelancermembershiptransactions::where('user_id', $user->id)->update([
                'membership_id' => $basic_freelancer_membership->id,
                'start_at' => date('Y-m-d'),
                'end_at' => $end_at
            ]);

            Freelancerservicescount::where('user_id', $user->id)->update([
                'bids' => $basic_freelancer_membership->bids,
                'featured_job_bids' => $basic_freelancer_membership->featuredjobquotes,
                'normal_services' => $basic_freelancer_membership->normal_services,
                'priority_services' => $basic_freelancer_membership->priority_services,
                'featured_services' => $basic_freelancer_membership->featured_services,
                'annual_bids_rollover' => $basic_freelancer_membership->annual_bids_rollover,
                'portfolio_size' => $basic_freelancer_membership->portfolio_size,
                'annual_services_rollover' => $basic_freelancer_membership->annual_services_rollover,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

    }
}
