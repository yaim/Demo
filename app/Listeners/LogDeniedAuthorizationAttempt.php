<?php

namespace App\Listeners;

use App\LoginAttempt;

class LogDeniedAuthorizationAttempt
{
    public function handle($event)
    {
        LoginAttempt::forceCreate([
            'user_id'      => $event->user->id,
            'attempted_at' => $event->attemptedAt,
            'successful'   => 0,
        ]);
    }
}
