<?php

namespace Domain\Catalog\Builders;

use Illuminate\Database\Eloquent\Builder;

use Domain\Catalog\Models\ProductVariant;
use Domain\Store\Models\Store;

class ProductVariantBuilder extends Builder
{
    static function getShopProductVariant(
        string $bindingKey, 
        mixed $value, 
        Store $store
    ) :?ProductVariant
    {
        return ProductVariant::whereHas(
                'product', 
                fn($query) => $query->where('store_id', $store->id)
            )->where($bindingKey, $value)
            ->first();
    }

    function defaultVariant()
    {
        return $this->where('is_default_variant', true);
    }
}
