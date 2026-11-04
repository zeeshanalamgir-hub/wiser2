<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Membership;
use Illuminate\Console\Command;

class ExpireMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach($users as $user) {
            if($user?->is_employer == 1 && (new Membership())->isMembershipExpired($user)) {
                return (new Membership())->updateEmployerMembership($user);
            }else if($user?->is_freelancer == 1 && (new Membership())->isMembershipExpired($user)) {
                return (new Membership())->updateFreelancerMembership($user);
            }
        }
    }
}
