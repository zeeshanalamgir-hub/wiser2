<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RealTimeNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    public string $message;
    public ?string $clickUrl;
    public int $senderId;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, int $senderId, ?string $clickUrl = null)
    {
        $this->message = $message;
        $this->clickUrl = $clickUrl;
        $this->senderId = $senderId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        Log::info("Broadcasting to channel: private-App.Models.User.{$notifiable->id}", [
            'message' => $this->message,
            'click_url' => $this->clickUrl,
        ]);


        return new BroadcastMessage([
            'message' => $this->message,
            'click_url' => $this->clickUrl,
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'click_url' => $this->clickUrl,
        ];
    }

    public function toDatabase($notifiable)
    {
        Log::info('Database notification payload', [
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'click_url' => $this->clickUrl,
        ]);

        return [
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'click_url' => $this->clickUrl,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
