<?php

namespace Domain\Catalog\Actions;

use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\ProductVariant;

class DeleteProductVariantAction
{
    static function execute(
        ProductVariant $productVariant,
        bool $forceDelete = false
    ) :ActionException|true
    {
        try {
            if($forceDelete)
                $productVariant->forceDelete();
            else
                $productVariant->delete();

            return true;
        }
        catch(\Exception $e)
        {
            return ActionException::from($e);
        }
    }
}