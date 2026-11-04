<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contactData;
    protected $supportEmail;
    protected $type;

    /**
     * Create a new job instance.
     */
    public function __construct($contactData, $supportEmail, $type = 'contact')
    {
        $this->contactData = $contactData;
        $this->supportEmail = $supportEmail;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        sendContactEmail(
            contactData: $this->contactData,
            supportEmail: $this->supportEmail,
            type: $this->type
        );
    }
}