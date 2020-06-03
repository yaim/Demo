<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogInDenied
{
    use Dispatchable, SerializesModels;

    public $user;
    public $attemptedAt;

    public function __construct($user, $attemptedAt)
    {
        $this->user        = $user;
        $this->attemptedAt = $attemptedAt;
    }
}
