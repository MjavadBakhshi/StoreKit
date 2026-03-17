<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

use Domain\Catalog\Enums\ProductType;

class ProductFormData extends Data
{
    function __construct(
        #[WithCast(EnumCast::class)]
        public readonly ProductType $product_type,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly ?float $price,
        public readonly int $stock = 0,
        public readonly ?array $images,
    ) {}

    static function rules(Request $request) :array
    {
        $store = $request->store;

        return [
            'slug' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('products')
                ->where(fn($query) => $query->where('store_id', $store->id))
            ],
            'images' => 'nullable|array',
            'images.*' => 'bail|file|mimes:jpg,jpeg,png,bmp,webp,gif|max:5120', # 5MB
        ];
    }
}