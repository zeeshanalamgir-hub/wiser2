<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Database storage.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'click_url' => '/dashboard', // Example URL
        ];
    }

    /**
     * Broadcasting configuration.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'click_url' => '/dashboard', // Example URL
        ]);
    }

    /**
     * Email configuration.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Test Notification')
            ->line($this->message)
            ->action('View Details', url('/dashboard'));
    }
}
