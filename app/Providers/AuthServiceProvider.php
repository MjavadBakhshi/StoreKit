<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Domain\Store\Models\Store;
use Domain\Account\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('store-ownership', function(User $user, Store $store){
            return $user->id == $store->user_id;
        });
    }
}
