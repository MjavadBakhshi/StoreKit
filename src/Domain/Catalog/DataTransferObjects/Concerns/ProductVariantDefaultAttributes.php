<?php

namespace Domain\Catalog\DataTransferObjects\Concerns;

use Domain\Catalog\Enums\ProductType;

/**
 * This is responsible for keeping default attributes names and 
 * their validation rules of each product type.
 */
class ProductVariantDefaultAttributes
{

    // This is a factory method which handle  
    static function getRules(ProductType $productType) :array
    {
        $getterFunction = strtolower($productType->value).'Product';

        return self::$getterFunction();
    }


    protected static function physicalProduct() :array
    {
        return [
            'df-store-weight' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected static function digitalProduct() :array
    {
        return [
            'df-store-files' => [
                'nullable',
                'array', 
                'each' => [  // Apply rules to each file in the array
                    'mimes:png,jpg,pdf,xls,xlsx,doc,docx,zip,rar,mkv,mp4,mp3,txt', // Allow only JPG and PNG extensions
                    'max:'.(300 * 1024), // Max file size: 2048 KB (2 MB)
                ],
            ]
        ];
    }

    protected static function serviceProduct() :array
    {
        return [];
    }
}