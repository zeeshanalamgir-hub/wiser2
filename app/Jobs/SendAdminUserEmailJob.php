<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user;
    public $subject;
    public $message;
    public $general;
    public $config;
    
    public function __construct( $user, $subject, $message, $general, $config )
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
        $this->general = $general;
        $this->config = $config;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
{
    Log::info("Admin User Email Job Started");

    $html = view('admin.mail.user-email', [
        'subject' => $this->subject,
        'body' => $this->message,
        'username' => $this->user->username ?? $this->user->name,
    ])->render();

    try {

        sendSendGridMail(
            $this->config,
            $this->user->email,
            $this->user->username ?? $this->user->name,
            $this->subject,
            $html,
            $this->general
        );

        Log::info('SendGrid email sent', [
            'email' => $this->user->email
        ]);

    } catch (\Exception $e) {

        Log::error('SendGrid email failed', [
            'error' => $e->getMessage()
        ]);

    }
}
}
