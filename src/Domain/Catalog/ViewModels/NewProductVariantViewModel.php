<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Shared\ViewModels\ViewModel;

class NewProductVariantViewModel extends ViewModel
{
    function __construct(
        protected ProductVariant $productVariant
    ){}

    function productVariant() :array
    {
        return [
            ...$this->productVariant->except([
                'id', 
                'product_id',
                'is_default_variant'
            ])
        ];
    }

}