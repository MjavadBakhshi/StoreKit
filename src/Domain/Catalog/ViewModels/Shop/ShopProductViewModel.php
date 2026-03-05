<?php

namespace Domain\Catalog\ViewModels\Shop;

use Domain\Catalog\Models\Product;
use Domain\Shared\ViewModels\ViewModel;

class ShopProductViewModel extends ViewModel
{
    function __construct(
        public readonly Product $product
    ) {}

    function product() :array
    {
        // Data manipulation here.
        return $this->product->only([
            'title', 
            'description',
            'public_id'
        ]);
    }
}