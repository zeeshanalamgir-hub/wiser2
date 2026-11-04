<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FreelancerMembershipTransaction;
use App\Models\Freelancermembershiptransactions;
use App\Models\FreelancerServicesCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RewardExpiredMembershipBids extends Command
{
    protected $signature = 'membership:reward-expired-bids';
    protected $description = 'Reward 10 bids to users whose membership has expired';

    public function handle()
    {
        $today = now();

        FreelancerMembershipTransactions::whereDate('end_at', '<', $today)
            ->chunk(100, function ($memberships) use ($today) {

                foreach ($memberships as $membership) {
                    if (!$membership->last_rewarded_at ||$membership->last_rewarded_at->diffInMonths($today) >= 1) {

                        DB::transaction(function () use ($membership, $today) {

                            $service = FreelancerServicesCount::firstOrCreate(
                                ['user_id' => $membership->user_id],
                                ['bids' => 0]
                            );

                            $service->increment('bids', 10);

                            $membership->update([
                                'last_rewarded_at' => $today
                            ]);
                        });
                    }
                }
            });
    }
}