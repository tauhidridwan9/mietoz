<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
{
    use Queueable;
     protected $order;
    protected $productName;
    protected $quantity;
    protected $totalAmount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $productName, $quantity, $totalAmount)
    {
        $this->order = $order;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->totalAmount = $totalAmount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
   

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'product_name' => $this->productName,
            'quantity' => $this->quantity,
            'total_amount' => $this->totalAmount,
            'message' => 'Your order has been cancelled due to insufficient stock.'
        ];
    }
}
