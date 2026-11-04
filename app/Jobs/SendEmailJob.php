<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailTemplate;
    protected $user;
    protected $type;
    protected $shortCodes;

    /**
     * Create a new job instance.
     */
    public function __construct($emailTemplate, $user, $type, $shortCodes)
    {
        $this->emailTemplate = $emailTemplate;
        $this->user = $user;
        $this->type = $type;
        $this->shortCodes = $shortCodes;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // \Log::info('handle called', [
        //     'emailTemplate' => $this->emailTemplate,
        //     'type' => $this->type,
        // ]);

        sendEmail(
            emailTemplate: $this->emailTemplate,
            user: $this->user,
            type: $this->type,
            shortCodes: $this->shortCodes
        );
    }
}
