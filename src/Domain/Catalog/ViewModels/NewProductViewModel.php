<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Product;
use Domain\Shared\ViewModels\ViewModel;

class NewProductViewModel extends ViewModel
{
    function __construct(protected Product $product){}

    function product() :array
    {
        return $this->product->toArray();
    }
}