<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\UserMilestone;
use Illuminate\Support\Facades\Crypt;

// used with user bidding
trait UbPriceStrategyTrait
{
    public function calculatePlatformfeePercentage($item, $platformfee)
    {
        $item->platform_fee = $platformfee;
        $item->platform_fee_text = '$0';

        $item->platform_fee_percentage = 0;
        $item->platform_fee_percentage_text = 0;

        $item->getpaid_amount = 0;
        $item->getpaid_amount_text = 0;

        if ($item->job->budgettype == 'fixed') {
            if ($item->job_type == 'byproject') {
                // Calculate for 'byproject'
                $item->platform_fee_percentage = $platformfee;
                $item->platform_fee_text = '$' . ($item->bid_price * ($platformfee / 100));

                $item->getpaid_amount = $item->bid_price - ($item->bid_price * ($platformfee / 100));
                $item->getpaid_amount_text = '$' . $item->getpaid_amount;
            } else if ($item->job_type == 'bymilestone') {
                // Calculate for 'bymilestone'
                $milestones = UserMilestone::where('bid_id', $item->id)->get();
                $sum = $milestones->sum('amount');

                $item->platform_fee_percentage = $platformfee;
                $item->platform_fee_text = '$' . ($sum * ($platformfee / 100));

                $item->getpaid_amount = $sum - ($sum * ($platformfee / 100));
                $item->getpaid_amount_text = '$' . $item->getpaid_amount;
            }
        } else if ($item->job->budgettype == 'per_hour') {
            // Calculate for 'per_hour'
            $item->platform_fee_percentage = $platformfee;
            $item->platform_fee_text = '$' . ($item->bid_price * ($platformfee / 100));

            $item->getpaid_amount = $item->bid_price - ($item->bid_price * ($platformfee / 100));
            $item->getpaid_amount_text = '$' . $item->getpaid_amount;
        }

        return $item;
    }


    public function calculatePriceAndFormat($item)
    {
        $item->collective_price = 0;
        $item->collective_price_text = '';

        if ($item->job->job_type == 'fixed') {
            if ($item->job_type == 'byproject') {
                $item->collective_price = $item->bid_price;
                $item->collective_price_text = '$' . $item->bid_price;
                $item->seller_proposal_days = $item->due_date;
            } else if ($item->job_type == 'bymilestone') {

                $milestones = UserMilestone::where('bid_id', $item->id)->get();
                $sum = $milestones->sum('amount');
                $sumdays = $milestones->sum('due_date');

                $item->collective_price = $sum;
                $item->collective_price_text = '$' . $sum;

                $item->seller_proposal_days = $sumdays;

                $item->load('userMilestones');
            }
        } else if ($item->job->job_type == 'hourly') {
            $item->collective_price = $item->bid_price;
            $item->collective_price_text = '$' . $item->bid_price . '/hr';
            $item->seller_proposal_days = $item->due_date;
        }

        return $item;
    }


}
