<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\User;
use App\Notifications\ChatNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(MessageSent $event)
    {
        // Logika untuk mengirim notifikasi
        // Misalnya, Anda bisa menggunakan notifikasi Laravel
        $customer = User::find($event->customerId);

        if ($customer) {
            $customer->notify(new ChatNotification($event->message, false));
        }
    }
}
