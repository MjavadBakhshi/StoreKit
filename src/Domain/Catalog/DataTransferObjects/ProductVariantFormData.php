<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\LaravelData\Data;

use Domain\Catalog\Enums\ProductType;

class ProductVariantFormData extends Data
{
    function __construct(
        #[WithCast(EnumCast::class)]
        public readonly ProductType $product_type,
        public readonly ?string $public_id,
        public readonly ?float $price,
        public readonly ?float $discounted_price,
        public readonly int $stock = 0,
        public readonly ?string $image_id,
        public readonly ?string $sku,
        public readonly ?array $attributes,
        public readonly bool $is_default_variant = false,
    ) {}

    static function rules(ValidationContext $context) :array
    {
        // Get default attirbures rules
        $productType = $context->fullPayload['product_type'];

        $attributesRules = self::getProductTypeDefaultAttributeRules($productType);
        // attirbutes.df-store-weight => [...],
        // attributes.df-store-files => [...] 
        return [
            ...$attributesRules
        ];
    }


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

    private static function getProductTypeDefaultAttributeRules(
        ProductType $productType
    ): array
    {
        $attributesRules = $productType->defaultVariantAttributes();
        // adding "attributes." prefix to adopt validation rulse.
        $attributesRules = 
        collect($attributesRules)
            ->mapWithKeys(
                fn($rules, $key) => ["attributes.$key" => $rules]
            )->toArray();

        // attirbutes.df-store-weight => [...],
        // attributes.df-store-files => [...] 
        return $attributesRules;
    }
}