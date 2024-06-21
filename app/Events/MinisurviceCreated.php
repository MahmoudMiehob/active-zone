<?php

namespace App\Events;

use App\Models\Minisurvice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MinisurviceCreated
{
    public $service;
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(Minisurvice $service)
    {
        $this->service = $service;

    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
