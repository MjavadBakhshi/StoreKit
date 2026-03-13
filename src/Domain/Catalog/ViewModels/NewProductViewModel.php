<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Shared\ViewModels\ViewModel;

class NewProductViewModel extends ViewModel
{
    function __construct(
        protected Product $product,
        protected ProductVariant $defaultProductVariant,
    ){}

    function product() :array
    {
        return [
            ...$this->product->except(['id', 'product_type', 'status']),
            'product_type' => $this->product->product_type->value,
            'price' => $this->defaultProductVariant->price,
            'stock' => $this->defaultProductVariant->stock,
        ];
    }
}