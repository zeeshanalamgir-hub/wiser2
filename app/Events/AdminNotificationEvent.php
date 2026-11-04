<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdminNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin.notifications');
    }

    public function broadcastWith()
    {
        try {
            return [
                'id' => $this->notification->id,
                'message' => $this->notification->title,
                'user_image' => getImage(
                    imagePath()['profile']['user']['path'] . '/' . $this->notification->user->image,
                    imagePath()['profile']['user']['size']
                ),
                'created_at' => $this->notification->created_at->diffForHumans(),
                'read_url' => route('admin.notification.read', $this->notification->id),
            ];
        } catch (\Exception $e) {
            Log::error('Error in broadcastWith(): ' . $e->getMessage());
            return [];
        }
    }

    public function broadcastAs()
    {
        return 'admin.notification';
    }
}