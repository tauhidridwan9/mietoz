<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $message;
    public $customerId;

    public function __construct($chatId, $message, $customerId)
    {
        $this->chatId = $chatId;          // Menyimpan chat_id
        $this->message = $message;        // Menyimpan pesan
        $this->customerId = $customerId;  // Menyimpan customer_id
    }

    public function broadcastOn()
    {
        // Mengirim event ke channel khusus untuk customerId
        return new Channel('chat.' . $this->customerId);
    }

    public function broadcastWith()
    {
        // Mengembalikan data yang dikirim ke frontend
        return [
            'data' => [
                'chat_id' => $this->chatId,
                'message' => $this->message,
                'customer_id' => $this->customerId,
            ],
        ];
    }

    public function broadcastAs()
    {
        // Menentukan nama event saat dikirim
        return 'MessageEvent';
    }
}
