<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Acceptedbid;
use Carbon\Carbon;


class SendTimesheetReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-timesheet-reminder';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send timesheet reminders to developers every Saturday at 8 AM in their time zone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all users who have logged in with a timezone
        // $users = User::where('status',1)
        // ->where('is_freelancer',1)
        // ->whereHas('latestLogin', function ($query) {
        //     $query->whereNotNull('timezone');
        // })
        // ->with('latestLogin')
        // ->get();

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
            $timezone = $user->latestLogin->timezone ?? null;

            if ($timezone) {
                // Convert current time to user's latest login timezone
                $userTime = Carbon::now($timezone);
                // Check if it's Saturday and 8:00 AM in their timezone
                if ($userTime->isThursday() && $userTime->format('H') == '08') {

                    // Fetch all active jobs with is_endcontract = 0 and budgettype = 'hourly'
                    // get active job
                    $jobs = Acceptedbid::where('is_endcontract', 0)
                        ->whereHas('job', function ($query) {
                            $query->where('budgettype', 'hourly');
                        })
                        ->where('seller_id', $user->id) // Ensure job belongs to the user
                        ->get();
                    foreach ($jobs as $job) {
                        // Sending email notification using the existing function
                        sendJobEmailWithNotification(
                            senderId: $job->buyer_id, // Buyer ID as sender
                            receiverId: $job->seller_id, // Seller ID as receiver
                            jobId: $job->job_id,
                            type: 'TIMESHEET_REMINDER', // Define appropriate email type
                            admin: false,
                            jobType: "timesheet",
                            userBiddingId: $job->bid_id
                        );

                        $this->info("Timesheet reminder sent to {$user->email} at 8 AM in {$timezone}");
                    }
                }
            } else {
                $this->warn("Timezone not found for user ID: {$user->id}");
            }
        }

        $this->info('All reminders have been processed.');
    }
}
