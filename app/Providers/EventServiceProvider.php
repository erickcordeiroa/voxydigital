<?php

namespace App\Providers;

use App\Listeners\SetTenantIdSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Login::class => [
            SetTenantIdSession::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
