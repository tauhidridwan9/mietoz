<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $orderItems;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderItems)
    {
        $this->orderItems = $orderItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Orderan Anda Siap Diambil')
            ->view('emails.order_completed')
            ->with([
                'orderItems' => $this->orderItems,
            ]);
    }
}

