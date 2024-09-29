<?php
// app/Notifications/OrderStatusUpdated.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderStatusUpdated extends Notification
{
    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Status pesanan Anda sedang {$this->order->status}.",
            'order_id' => $this->order->id,
            'order_status' => $this->order->status,
            'confirmation_url' => route('orders.confirm', $this->order->id),
        ];
    }

}
