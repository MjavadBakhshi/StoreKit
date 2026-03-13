<?php 

namespace Domain\Catalog\Enums;

use Domain\Catalog\DataTransferObjects\Concerns\ProductVariantDefaultAttributes;
use Domain\Shared\Enums\BackedEnum;

enum ProductType :string
{
    use BackedEnum;

    case Digital = 'Digital';
    case Physical = 'Physical';
    case Service = 'Service';


    function defaultVariantAttributes(bool $onlyAttributeNames = false) :array
    {
        $rules = ProductVariantDefaultAttributes::getRules($this);   

        return $onlyAttributeNames ? array_keys($rules) : $rules;
    }
}