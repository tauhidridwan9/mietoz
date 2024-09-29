<?php

namespace App\Listeners;

use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class LogUserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)

    {

        UserSession::create([

            'user_id' => $event->user->id,

            'login_at' => Carbon::now('Asia/Jakarta'),

        ]);
    }
}
