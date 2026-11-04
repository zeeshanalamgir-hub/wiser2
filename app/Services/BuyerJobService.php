<?php

namespace App\Services;

use App\Models\BidsLog;
use App\Models\Employerservicescount;
use Illuminate\Support\Facades\Auth;

use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancerservicescount;
use App\Models\User;
use App\Models\Userbiding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class BuyerJobService
{

    public function remainingBuyerCreateJobCount($userId, $jobType = 'any'){

        $userservices = Employerservicescount::where('user_id', $userId)->get();
        $count = 0;

        if($jobType == 'any'){
            $count = $userservices[0]->normal_jobs + $userservices[0]->priority_jobs + $userservices[0]->featured_jobs;
        }

        return $count;

    }

    public function remainingBuyerWalletBalance($userId){

        $user = User::where('id', $userId)->first();
        if(!$user){
            return 0;
        }

        $currentBalance = (int) $user->balance;
        return $currentBalance;

    }
}
