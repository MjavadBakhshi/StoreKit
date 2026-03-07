<?php

namespace Domain\Store\Actions;

use Domain\Account\Models\User;
use Domain\Store\Models\Store;

class ResolveDefaultStoreAction
{
    static function set(Store $store, User $user)
    {
        session(['current_store:'.$user->id => $store->id]);
    }

    static function get(User $user) :?Store
    {
        $storeId = session('current_store:'.$user->id);
        
        if(is_null($storeId)) return null;

        $store = new Store;
        $store->id = $storeId;

        return $store;
    }
}