<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderFinishEmail extends Mailable
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
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Order Finish Email',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->subject('Your Order Has Been Delivered')
<<<<<<< HEAD
        ->view('emails.order_finish')
=======
        ->view('emails.order_completed')
>>>>>>> 1edaa399096f5f4e46a96e678e12c725e175237d
        ->with([
            'orderItems' => $this->orderItems,
        ]);
    }
}


<<<<<<< HEAD
=======

>>>>>>> 1edaa399096f5f4e46a96e678e12c725e175237d
