<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param int $bookingId
     * @param string $type ring|accepted|declined|cancelled
     * @param int $toUserId The user this signal is delivered to.
     * @param array $from ['id' => int, 'name' => string]
     */
    public function __construct(
        public int $bookingId,
        public string $type,
        public int $toUserId,
        public array $from,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->toUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'video-call-signal';
    }

    public function broadcastWith(): array
    {
        return [
            'booking_id' => $this->bookingId,
            'type' => $this->type,
            'from' => $this->from,
        ];
    }
}
