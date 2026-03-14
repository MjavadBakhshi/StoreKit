<?php

namespace Domain\Catalog\Actions;

use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\Product;

class DeleteProductAction
{
    static function execute(
        Product $product,
        bool $forceDelete = false
    ) :ActionException|true
    {
        try {
            if($forceDelete)
                $product->forceDelete();
            else
                $product->delete();

            #TODO: in the future it might rais an event for cascading soft deletes
            // such as reviews, variants or rates.

            return true;
        }
        catch(\Exception $e)
        {
            return ActionException::from($e);
        }
    }
}