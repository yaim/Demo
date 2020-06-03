<?php

namespace App\Providers;

use App\Events\LogInDenied;
use App\Events\LoggedInSuccessfully;
use App\Listeners\LogDeniedAuthorizationAttempt;
use App\Listeners\LogSucceededAuthorizationAttempt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LoggedInSuccessfully::class => [
            LogSucceededAuthorizationAttempt::class,
        ],
        LogInDenied::class => [
            LogDeniedAuthorizationAttempt::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
