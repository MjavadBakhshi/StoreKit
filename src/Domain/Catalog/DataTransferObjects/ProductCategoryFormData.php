<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductCategoryFormData extends Data
{
    function __construct(
        public readonly ?string $public_id,
        public readonly ?string $parent_public_id,
        public readonly string $title,
        public readonly string $slug,
        public readonly bool $status,
        public readonly bool $menu_visibility,

        // seo fields
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
        $productPublicId = $context->fullPayload['public_id'] ?? null;

        return [
            'slug' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('product_categories')
                ->ignore($productPublicId, 'public_id')
                ->where(fn($query) => $query->where('store_id', $store->id))
            ],
            'parent_public_id' => [
                'nullable',
                'string',
                Rule::exists('product_categories', 'public_id')
                ->where(fn($query) => $query->where('store_id', $store->id))
            ],
            // seo fields
            'page_title' => 'nullable|string|max:60',
            'meta_keywords' => 'nullable|string|max:155',
            'canonical_url' => 'nullable|string|url',
            'redirect301_url' => 'nullable|string|url',
        ];
    }
}