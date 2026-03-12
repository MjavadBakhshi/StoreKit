<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class ProductVariantFormData extends Data
{
    function __construct(
        public readonly ?string $public_id,
        public readonly ?float $price,
        public readonly ?float $discounted_price,
        public readonly int $stock = 0,
        public readonly ?string $sku,
        public readonly ?array $attributes,
        public readonly bool $is_default_variant = false,
    ) {}


    static function fromRequest(Request $request) :static
    {
        // In case of update operation public_id is attached to DTO.
        if($request->route('product_variant'))
            return self::from([
                ...$request->all(),
                'public_id' => $request->product_variant->public_id,
            ]);

        return self::from($request->all());
    }
}