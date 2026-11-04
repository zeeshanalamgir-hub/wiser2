<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Jobworkdeliveryhourlybid;
use App\Models\Acceptedbid;
use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Freelancermembershiptransactions;
use App\Models\Employermembershiptransactions;

class LockTimeSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lock-time-sheet';

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
        // Get all users who have logged in with a timezone
        // $users = User::where('status',1)->where('is_freelancer',1)->whereHas('latestLogin', function ($query) { $query->whereNotNull('timezone'); })->with('latestLogin')->get();
        $users = User::where('status', 1)
        ->where('is_freelancer', 1)
        ->whereHas('latestLogin', function ($query) {
            $query->whereNotNull('timezone');
        })
        ->with('buyerAcceptedBids')
        ->orderBy('id','desc')
        ->get();

        foreach ($users as $user) {

            // Get the latest login's timezone
            $timezone = $user?->latestLogin?->timezone ?? null;

            if ($timezone) {
                // Convert current time to user's latest login timezone
                $userTime = Carbon::now($timezone);
                // Check if it's Sunday and 8:00 AM in their timezone u need to uncomment this for production
                if ($userTime->isSunday() && $userTime->format('H') == '08') {

                    // Fetch all active jobs with is_endcontract = 0 and budgettype = 'hourly'
                    // get active job
                        $accepted_bids = Acceptedbid::where('is_endcontract', 0)
                        ->whereHas('job', function ($query) {
                            $query->where('budgettype', 'hourly');
                        })
                        ->where('seller_id', $user->id) // Ensure job belongs to the user
                        ->orderBy('id','desc')
                        ->get();

                        foreach ($accepted_bids as $accepted_bid) {
                            //get buyer and seller id then deduct amount from buyer and add in seller account
                           $job_work_delievery_hourly_bid = Jobworkdeliveryhourlybid::where('job_id', $accepted_bid->job_id)
                            ->where(function ($q) {
                                $q->where('status', 1)
                                ->whereNull('date_end')
                                ->where('disputed_hours', 0)
                                ->where('disputed_minutes', 0)
                                ->where(function ($qry) {
                                    $qry->where('is_payed', 0)
                                        ->orWhereNull('is_payed');
                                });
                            })
                            ->orderByDesc('id')
                            ->first();

                            if(!is_null($job_work_delievery_hourly_bid))
                            {
                                $buyer = User::find($job_work_delievery_hourly_bid->buyer_id);
                                $seller = User::find($job_work_delievery_hourly_bid->seller_id);

                                $seller_platform_fee_percentage = ($this->getMembershipFee($seller->id));
                                $buyer_platform_fee_percentage = ($this->getMembershipFee($buyer->id));

                                $weekly_cost = $job_work_delievery_hourly_bid->total_cost;

                                $seller_platform_fee_amount = ($weekly_cost * $seller_platform_fee_percentage) / 100;

                                $buyer_platform_fee_amount = ($weekly_cost * $buyer_platform_fee_percentage) / 100;

                                $seller_earning = $weekly_cost - $seller_platform_fee_amount;
                                $buyer_spend = $weekly_cost + $buyer_platform_fee_amount;

                                if($buyer->balance < $buyer_spend)
                                {
                                    sendLowBalanceEmail('LOW_BALANCE_ALERT',$buyer,[
                                        'balance' => number_format($buyer->balance, 2),
                                        'required_balance' => number_format($buyer_spend, 2),
                                    ]);
                                    $this->warn("Buyer ID: {$buyer->id} does not have sufficient balance to process the payment for Job ID: {$accepted_bid->job_id}. Required: {$buyer_spend}, Available: {$buyer->balance}");
                                    continue;
                                }

                                if(isset($seller) && isset($buyer) && isset($weekly_cost))
                                {
                                    $buyer->balance -= number_format($buyer_spend,2);
                                    $buyer->save();

                                    //from seller we deduct the platform fee
                                    $seller->balance += number_format($seller_earning,2);
                                    $seller->save();

                                    $booking = Booking::where('job_id',$accepted_bid->job_id)->first();

                                    // Buyer transaction (debit)
                                    $buyerTransaction = new Transaction();
                                    $buyerTransaction->user_id = $buyer->id;
                                    $buyerTransaction->to_user = $seller->id;
                                    $buyerTransaction->amount = $buyer_spend;
                                    $buyerTransaction->post_balance = $buyer->balance;
                                    $buyerTransaction->booking_id = $booking?->id ?? null;
                                    $buyerTransaction->trx_type = '-';
                                    $buyerTransaction->trx = getTrx();
                                    $buyerTransaction->status = 1;
                                    $buyerTransaction->charge = $buyer_platform_fee_amount;
                                    $buyerTransaction->details = "Hourly job payment (Start date: {$job_work_delievery_hourly_bid?->date_start})";
                                    $buyerTransaction->save();

                                    // buyer charge transaction (fee)
                                    $buyercTransaction = new Transaction();
                                    $buyercTransaction->user_id = $buyer->id;
                                    $buyercTransaction->to_user = $seller->id;
                                    $buyercTransaction->amount = $buyer_platform_fee_amount;
                                    $buyercTransaction->post_balance = $buyer->balance;
                                    $buyercTransaction->booking_id = $booking?->id ?? null;
                                    $buyercTransaction->trx_type = '-';
                                    $buyercTransaction->trx = getTrx();
                                    $buyercTransaction->charge = 0;
                                    $buyercTransaction->status = 1;
                                    $buyercTransaction->details = "Platform commission from buyer on hourly job {$job_work_delievery_hourly_bid?->date_start} after {$buyer_platform_fee_percentage}% platform fee deduction";
                                    $buyercTransaction->save();


                                    // Seller transaction
                                    $sellerTransaction = new Transaction();
                                    $sellerTransaction->user_id = $seller->id;
                                    $sellerTransaction->to_user = $buyer->id;
                                    $sellerTransaction->amount = $seller_earning;
                                    $sellerTransaction->post_balance = $seller->balance;
                                    $sellerTransaction->booking_id = $booking?->id ?? null;
                                    $sellerTransaction->trx_type = '+';
                                    $sellerTransaction->trx = getTrx();
                                    $sellerTransaction->charge = $seller_platform_fee_amount;
                                    $sellerTransaction->status = 1;
                                    $sellerTransaction->details = "Hourly job payment received (Start date: {$job_work_delievery_hourly_bid?->date_start})";
                                    $sellerTransaction->save();

                                    // Seller transaction Fee
                                    $sellercTransaction = new Transaction();
                                    $sellercTransaction->user_id = $seller->id;
                                    $sellercTransaction->to_user = $buyer->id;
                                    $sellercTransaction->amount = $seller_platform_fee_amount;
                                    $sellercTransaction->post_balance = $seller->balance;
                                    $sellercTransaction->booking_id = $booking?->id ?? null;
                                    $sellercTransaction->trx_type = '-';
                                    $sellercTransaction->trx = getTrx();
                                    $sellercTransaction->charge = 0;
                                    $sellercTransaction->status = 1;
                                    $sellercTransaction->details = "Platform commission from seller on hourly job {$job_work_delievery_hourly_bid?->date_start} after {$seller_platform_fee_percentage}% platform fee deduction";
                                    $sellercTransaction->save();

                                }

                                $job_work_delievery_hourly_bid->date_end = Carbon::now()->format('Y-m-d');
                                $job_work_delievery_hourly_bid->is_payed = 1;
                                $job_work_delievery_hourly_bid->save();
                            }
                            $this->info("Timesheet locked of {$user->email} at 8 AM in ");
                        }
                }
            } else {
                $this->warn("Timezone not found for user ID: {$user->id}");
            }
        }

        $this->info('All reminders have been processed.');
    }

    protected function getMembershipFee($userId)
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
            return $usermembership[0]->fee;
        }

        if ($user->is_employer == 1) {
            $usermembership1 = Employermembershiptransactions::join('employer_membership', 'employer_membership_transactions.membership_id', '=', 'employer_membership.id')->where('user_id', $user->id)->get();
            return $usermembership1[0]->fee;
        }

        return 0;
    }
}