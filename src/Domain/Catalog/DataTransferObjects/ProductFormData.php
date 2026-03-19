<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

use Domain\Catalog\Enums\ProductType;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductFormData extends Data
{
    function __construct(
        public readonly ?string $public_id,
        #[WithCast(EnumCast::class)]
        public readonly ProductType $product_type,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly ?array $images,
        public readonly array $default_variant,

        // seo fields
        public readonly ?string $tags,
        public readonly ?string $page_title,
        public readonly ?string $meta_keywords,
        public readonly ?string $meta_description,
        public readonly ?string $canonical_url,
        public readonly ?string $redirect301_url,
        #[WithCast(EnumCast::class)]
        public readonly ?string $robot,
    ) {}

    static function rules(ValidationContext $context, Request $request) :array
    {
        // For checking slug is unique in the store scope.
        $store = $request->store;

        // For checking slug is unique in update operation.
        $productPublicId = $context->fullPayload['public_id'];

        return [
            'slug' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('products')
                ->ignore($productPublicId, 'public_id')
                ->where(fn($query) => $query->where('store_id', $store->id))
            ],
            'images' => 'nullable|array',
            'images.*' => 'bail|file|mimes:jpg,jpeg,png,bmp,webp,gif|max:5120', # 5MB
            // seo fields
            'page_title' => 'nullable|string|max:60',
            'meta_keywords' => 'nullable|string|max:155',
            'canonical_url' => 'nullable|string|url',
            'redirect301_url' => 'nullable|string|url',
        ];
    }
}