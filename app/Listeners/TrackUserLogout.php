<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use App\Models\User;


class TrackUserLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event)
    {
        $user = $event->user;
        if ($user) {
            $updatedUser = User::find($user->id);
            $updatedUser->is_login = 0;
            $updatedUser->gmail_login = 0;
            $updatedUser->save();
        }
    }
}
