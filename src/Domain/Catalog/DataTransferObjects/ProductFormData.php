<?php

namespace Domain\Catalog\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Spatie\LaravelData\Data;

class ProductFormData extends Data
{
    function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $description,
    ) {}

    static function rules(Request $request) :array
    {
        $store = $request?->route('store');

        return [
            'slug' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('products')
                ->where(fn($query) => $query->where('store_id', $store->id))
            ]
        ];
    }
}