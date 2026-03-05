<?php

namespace Domain\Catalog\DataTransferObjects;

use Spatie\LaravelData\Data;

class ProductVariantFormData extends Data
{
    function __construct(
        public readonly ?float $price,
        public readonly int $stock = 0,
        public readonly ?string $sku,
        public readonly ?array $attributes,
        public readonly bool $is_default_variant = false,
    ) {}
}