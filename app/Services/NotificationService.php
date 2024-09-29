<?php
namespace App\Services;

use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Notifications\OrderNotification;

class NotificationService
{
    /**
     * Kirim notifikasi untuk pesanan baru
     *
     * @param User $user
     * @param mixed $order
     */
    public function sendOrderNotification(User $user, $order)
    {
        $user->notify(new OrderNotification($order));
    }

    /**
     * Kirim notifikasi umum lainnya
     *
     * @param User $user
     * @param mixed $notificationInstance
     */
    public function sendGeneralNotification(User $user, $notificationInstance)
    {
        $user->notify($notificationInstance);
    }
}
