<?php

namespace App\Listeners;

use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogout
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
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)

    {

        $session = UserSession::where('user_id', $event->user->id)

            ->whereNull('logout_at')

            ->latest()

            ->first();


        if ($session) {
            logger('Found session with null logout_at: ', $session->toArray());

            $session->logout_at = Carbon::now()->setTimezone('Asia/Jakarta');
            $session->save();

            logger('Updated session: ', $session->toArray());

        }
    }
}
