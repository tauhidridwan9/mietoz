<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockDepletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    // Specify that we want to send notifications via the database and email
    public function via($notifiable)
    {
        return ['database'];
    }

    // The data that will be stored in the notifications table
    public function toArray($notifiable)
    {
        return [
            'message' => 'Stock for product ' . $this->product->name . ' habis.',
            'product_id' => $this->product->id,
        ];
    }

    
}
