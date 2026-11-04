<?php

namespace App\Services;

use App\Models\BidsLog;
use Illuminate\Support\Facades\Auth;

use App\Models\Freelancermembershiptransactions;
use App\Models\Freelancerservicescount;
use App\Models\User;
use App\Models\Userbiding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class UserBidService
{

    public function decrementBidCountBy($userId, $value)
    {
        $freelancerServicesCount = Freelancerservicescount::where('user_id', $userId)->first();
        if (!$freelancerServicesCount) {
            return false;
        }

        if ($freelancerServicesCount->bids <= 0) {
            return false;
        }


        $freelancerServicesCount->decrement('bids', $value);

        return true;
    }


    // for freelancer
    public function getAvailableBids($userId){
        $freelancerServicesCount = Freelancerservicescount::where('user_id', $userId)->first();
        if (!$freelancerServicesCount) {
            return 0;
        }

        $totalBids = $freelancerServicesCount->bids;
        return $totalBids;
    }
}
