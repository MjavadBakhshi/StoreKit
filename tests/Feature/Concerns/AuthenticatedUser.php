<?php

namespace Tests\Feature\Concerns;

use Illuminate\Support\Facades\Cache;

use Domain\Store\Actions\ResolveDefaultStoreAction;
use Domain\Account\Models\User;
use Domain\Store\Models\Store;

trait AuthenticatedUser
{
    protected User $user;
    protected Store $store;

    protected function actingAsUser(?User $user = null)
    {
        Cache::flush();

        $this->user = $user ?? User::factory()->create();
        $this->actingAs($this->user);

        $store = Store::factory()->for($this->user)->create();
        $this->setDefaultStore($store);
    }

    protected function setDefaultStore(Store $store)
    {
        $this->store = $store;
        ResolveDefaultStoreAction::set($store, $store->user);
    }
}