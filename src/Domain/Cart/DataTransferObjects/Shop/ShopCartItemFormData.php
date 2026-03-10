<?php

namespace Domain\Cart\DataTransferObjects\Shop;

use Spatie\LaravelData\Data;

use Domain\Catalog\Models\ProductVariant;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\WithoutValidation;

class ShopCartItemFormData extends Data
{
    function __construct(
        #[WithoutValidation]
        public readonly ProductVariant $product_variant,
        public readonly int $quantity
    ){}

    static function fromRequest(Request $request) :static
    {
        return self::from(
            $request->only(
                'quantity',
                'product_variant'
            )
        );
    }
}