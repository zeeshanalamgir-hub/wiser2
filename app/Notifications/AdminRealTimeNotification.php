<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AdminRealTimeNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    public string $message;

    public ?string $clickUrl;

    public int $userId;

    public function __construct(string $message, int $userId, ?string $clickUrl = null)
    {
        $this->message = $message;
        $this->clickUrl = $clickUrl;
        $this->userId = $userId;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        Log::info("Broadcasting to channel: private-App.Models.Admin.{$notifiable->id}", [
            'admin_id' => $notifiable->id,
            'message' => $this->message,
            'click_url' => $this->clickUrl,
        ]);

        return new BroadcastMessage([
            'message' => $this->message,
            'click_url' => $this->clickUrl,
            'user_id' => $this->userId,
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'user_id' => $this->userId,
            'click_url' => $this->clickUrl,
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'click_url' => $this->clickUrl,
        ];
    }
}
