<?php
// App\Mail\OrderCancelledNotification.php
namespace App\Mail;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $item;

    public function __construct(Order $order, OrderItem $item)
    {
        $this->order = $order;
        $this->item = $item;
    }

    public function build()
    {
        return $this->view('emails.order_cancelled')
            ->with([
                'productName' => $this->item->product->name,
                'quantity' => $this->item->quantity,
                'totalAmount' => $this->order->total_amount,
            ]);
    }
}

