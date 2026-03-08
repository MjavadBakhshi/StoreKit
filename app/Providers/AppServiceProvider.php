<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

use Domain\Shared\Actions\SessionAction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SessionAction::class, function ($app) {
            return new SessionAction(
                $app->make(SessionManager::class),
                $app->make(Request::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
