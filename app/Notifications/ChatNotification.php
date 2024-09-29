<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $isAdmin;

    public function __construct($message, $isAdmin)
    {
        $this->message = $message;
        $this->isAdmin = $isAdmin;
    }

    public function via($notifiable)
    {
        return ['database']; // Menyimpan notifikasi dalam database
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'is_admin' => $this->isAdmin,
        ];
    }
}
