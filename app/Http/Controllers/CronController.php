<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\GeneralSetting;
use App\Models\Service;
use App\Models\User;
use App\Models\JobBiding;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Employermembershiptransactions;
use App\Models\Freelancermembershiptransactions;
use App\Models\Employermembership;
use App\Models\Freelancermembership;
use App\Models\Employerservicescount;
use App\Models\Freelancerservicescount;

class CronController extends Controller
{
    public function service()
    {
        $bookings = Booking::where('status', 1)->whereIn('working_status', [0, 4])->whereNotNull('service_id')->get();
        $general = GeneralSetting::first();
        $general->last_cron_run = Carbon::now();
        $general->save();

        foreach ($bookings as $booking) {
            $service = Service::where('id', $booking->service_id)->first();
            $serviceUser = User::where('id', $service->user_id)->first();
            $deliveryTime = $booking->created_at->addDays($service->delivery_time);
            $nowTime = Carbon::now()->toDateTimeString();
            if ($nowTime > $deliveryTime) {
                $booking->status = 4;
                $booking->working_status = 5;
                $booking->save();
                $booking->status_updated_at = Carbon::now();

                $user = User::where('id', $booking->user_id)->firstOrFail();
                $user->balance += $booking->amount;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->to_user = $booking->user_id;
                $transaction->amount = $booking->amount;
                $transaction->post_balance = $user->balance;
                $transaction->trx_type = '+';
                $transaction->trx = $booking->order_number;
                $transaction->details = "Refund Money " . $booking->order_number;
                $transaction->save();

                notify($user, 'MONEY_REFUND', [
                    'amount' => getAmount($booking->amount),
                    'currency' => $general->cur_text,
                    'order_number' => $booking->order_number,
                    'post_balance' => getAmount($user->balance)
                ]);

                notify($serviceUser, 'SERVICE_BOOKING_EXPIRED', [
                    'order_number' => $booking->order_number,
                    'amount' => getAmount($booking->amount),
                    'currency' => $general->cur_text,
                ]);
            }
            return 0;
        }
    }

    public function job()
    {
        $bookings = Booking::where('status', 1)->where('working_status', 4)->whereNotNull('job_biding_id')->get();
        $general = GeneralSetting::first();
        $general->last_cron_run = Carbon::now();
        $general->save();
        foreach ($bookings as $booking) {
            $bidingUser = User::where('id', $booking->biding->user_id)->firstOrFail();
            $deliveryTime = $booking->created_at->addDays($booking->biding->job->delivery_time);
            $nowTime = Carbon::now()->toDateTimeString();
            if ($nowTime > $deliveryTime) {
                $booking->status = 4;
                $booking->working_status = 5;
                $booking->save();
                $booking->status_updated_at = Carbon::now();

                $user = User::where('id', $booking->user_id)->firstOrFail();
                $user->balance += $booking->amount;
                $user->save();

                $transaction = new Transaction();

                $transaction->user_id = $user->id;
                $transaction->to_user = $booking->user_id;
                $transaction->amount = $booking->amount;
                $transaction->post_balance = $user->balance;
                $transaction->trx_type = '+';
                $transaction->trx = $booking->order_number;
                $transaction->details = "Refund Money " . $booking->order_number;
                $transaction->save();

                notify($user, 'MONEY_REFUND', [
                    'amount' => getAmount($booking->amount),
                    'currency' => $general->cur_text,
                    'order_number' => $booking->order_number,
                    'post_balance' => getAmount($user->balance)
                ]);

                notify($bidingUser, 'JOB_EXPIRED', [
                    'order_number' => $booking->order_number,
                    'amount' => getAmount($booking->amount),
                    'currency' => $general->cur_text,
                ]);
            }
            return 0;
        }
    }
    public function checkEmployerMembership()
    {
        $currentDate = date('Y-m-d');

        // Get default membership details (free membership with default = 1)
        $defaultMembership = EmployerMembership::where('default', 1)->select('id', 'normal_jobs', 'featured_jobs', 'invites')->first();

        // Check if we have a default membership; otherwise, exit
        if (!$defaultMembership) {
            return;
        }

        $membershipId = $defaultMembership->id;
        $normalJobs = $defaultMembership->normal_jobs;
        $featuredJobs = $defaultMembership->featured_jobs;
        $invites = $defaultMembership->invites;

        // Calculate new end date for expired memberships
        $newEndDate = date('Y-m-d', strtotime('+1 month', strtotime($currentDate)));

        // Find memberships that are expired and have the default membership
        $expiredMemberships = EmployerMembershipTransactions::where('membership_id', $membershipId)
            ->where('end_at', '<', $currentDate)
            ->get();

        // Loop through each expired membership
        foreach ($expiredMemberships as $membership) {
            // Update membership transaction with new start and end dates
            $updated = EmployerMembershipTransactions::where('id', $membership->id)->update([
                'membership_id' => $membershipId,
                'start_at' => $currentDate,
                'end_at' => $newEndDate,
            ]);

            // If update was successful, increment services counts
            if ($updated) {
               // Increment job and invite counts in employer_services_count table for an expired membership
               EmployerServicesCount::where('user_id', $membership->user_id)->update([
                'normal_jobs' => $normalJobs,
                'featured_jobs' => $featuredJobs,
                'invites' => $invites
            ]);

            }
        }
        $standardMembership  = EmployerMembership::where('default', 0)->first();
        if (!$standardMembership) {
            // Handle case if no standard membership is found
            return;
        }
        // Handle users without free membership
        $expiredStandardMemberships = EmployerMembershipTransactions::where('membership_id', $standardMembership->id)->where('end_at', '<', $currentDate)->get();

        foreach ($expiredStandardMemberships as $membership) {
            $updated = EmployerMembershipTransactions::where('id', $membership->id)->update([
                'membership_id' => $defaultMembership->id,
                'start_at' => $currentDate,
                'end_at' => $newEndDate,
            ]);
            if ($updated) {
                // Update the services count for each expired membership
                EmployerServicesCount::where('user_id', $membership->user_id)->update([
                    'normal_jobs' => \DB::raw("normal_jobs + {$defaultMembership->normal_jobs}"),
                    'featured_jobs' => \DB::raw("featured_jobs + {$defaultMembership->featured_jobs}"),
                    'invites' => \DB::raw("invites + {$defaultMembership->invites}"),
                ]);
            }

        }
    }


    public function checkfreelancermembership()
    {
        //get default membership id
        $default_membership = Freelancermembership::where('default', 1)->select('id', 'bids', 'normal_services', 'priority_services', 'featured_services')->get();
        $membership_id = $default_membership[0]->id;
        $bids = $default_membership[0]->bids;
        $normal_services = $default_membership[0]->normal_services;
        $priority_services = $default_membership[0]->priority_services;
        $featured_services = $default_membership[0]->featured_services;
        //dd($normal_jobs." ".$priority_jobs." ".$featured_jobs);
        $current_date = date('Y-m-d');
        $expiry = config('app.membership_expiry');
        $end_at = date('Y-m-d', strtotime("+" . $expiry . " days"));

        $check_membership = Freelancermembershiptransactions::where('end_at', '<', $current_date)->get();
        if (count($check_membership) > 0) {
            foreach ($check_membership  as $m) {
                //update membership to default
                $updatemembership = Freelancermembershiptransactions::where('id', $m->id)->update([
                    'membership_id' => $membership_id,
                    'start_at' => date('Y-m-d'),
                    'end_at' => $end_at
                ]);
                if ($updatemembership) {
                    $updateservices = Freelancerservicescount::where('user_id', $m->user_id)->increment('bids', $bids);
                    $updateservices1 = Freelancerservicescount::where('user_id', $m->user_id)->increment('normal_services', $normal_services);
                    $updateservices2 = Freelancerservicescount::where('user_id', $m->user_id)->increment('priority_services', $priority_services);
                    $updateservice3 = Freelancerservicescount::where('user_id', $m->user_id)->increment('featured_services', $featured_services);
                }
            }
        }
    }
}
