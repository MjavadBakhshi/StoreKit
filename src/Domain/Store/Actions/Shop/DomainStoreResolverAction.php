<?php

namespace Domain\Store\Actions\Shop;

use Illuminate\Support\Facades\Cache;

use Domain\Store\Models\Store;

/**
 * This action class is responsible for fetching store data 
 * from cache/database by host address.
 */
class DomainStoreResolverAction 
{
    static function execute(string $domainName) :Store|false
    {
        // setup cache key.
        $cacheKey = 'store_id:'.$domainName;

        try {
            // Cache store data or resolve from cache immediately.
            $storeData = Cache::remember(
                $cacheKey, 
                now()->addDay(), 
                fn() => self::resolveDomain($domainName)->id
            );

            $store = new Store;
            $store->id = $storeData;
            return $store;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    private static function resolveDomain(string $domainName) :Store
    {
        $store = Store::select('id')
                    ->where('domain_name', $domainName)
                    ->firstOrFail();

        return $store;
    }

}