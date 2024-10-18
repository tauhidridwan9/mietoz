<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function broadcastOn()
    {
        return new Channel('dashboard'); // This is the channel name
    }
    public function broadcastAs()
    {
        return 'OrderUpdated'; // Sesuaikan dengan nama yang Anda gunakan di front-end
    }

}
