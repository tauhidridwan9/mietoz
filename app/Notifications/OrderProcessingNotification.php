<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class OrderProcessingNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // Atau metode lain yang Anda inginkan
    }

    public function toArray($notifiable)
    {
        $productNames = $this->order->orderItems->pluck('product_name')->implode(', ');
        return ['message'=> 'Pesanan ' .$productNames. ' sedang diproses.'];
            
    }
}


