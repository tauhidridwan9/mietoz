<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        // Specify that the notification should be stored in the database
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // This is the data that will be stored in the 'data' field of the notifications table
        return [
            'order_id' => $this->order->id,
            'message' => 'Pesanan Anda telah berhasil dibuat!',
        ];
    }
}
