<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Shared\ViewModels\ViewModel;

class NewProductViewModel extends ViewModel
{
    function __construct(
        protected Product $product
    ){}

    function product() :array
    {
        $defaultVariant = $this->product->variants()->defaultVariant()->first();

        return [
            ...$this->product->except(['id', 'product_type', 'status']),
            'product_type' => $this->product->product_type->value,
            'price' => $defaultVariant->price,
            'stock' => $defaultVariant->stock,
        ];
    }
}