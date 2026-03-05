<?php

namespace Domain\Catalog\Builders;

use Illuminate\Database\Eloquent\Builder;

use Domain\Catalog\Enums\ProductStatus;
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

class ProductBuilder extends Builder
{
    function active()
    {
        return $this->where('status', ProductStatus::Active);
    }

    static function getShopProduct(
        string $bindingKey, 
        mixed $value, 
        Store $store
    ) :?Product
    {
        return Product::active()
                ->whereBelongsTo($store)
                ->where($bindingKey, $value)
                ->first();
    }
}
