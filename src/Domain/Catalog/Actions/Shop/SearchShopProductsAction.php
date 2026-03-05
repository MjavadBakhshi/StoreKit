<?php

namespace Domain\Catalog\Actions\Shop;

use Illuminate\Support\Collection;

use Domain\Store\Models\Store;

class SearchShopProductsAction
{
    static function execute(
        Store $store,
        array $filters = []
    ) :Collection{

        // #TODO filters will be applied later.

        return $store->products()
            ->active()
            ->get();
    }
}