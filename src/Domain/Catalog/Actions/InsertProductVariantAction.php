<?php

namespace Domain\Catalog\Actions;

use Domain\Catalog\DataTransferObjects\ProductVariantFormData;
use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\ProductVariant;

class InsertProductVariantAction
{
    static function execute(
        ProductVariantFormData $data,
        Product $product
    ): ProductVariant|false
    {
        try {

            $data = [
                ...$data->toArray(),
                'sku' => $data->sku ?? self::generateRandomSKU($product->id)
            ];
            
            $productVariant = $product->variants()->create($data);

            return $productVariant;
        }
        catch(\Exception $e)
        {
            logger()->info($e->getMessage());
            return false;
        }
    }

    private static function generateRandomSKU(int $productId) :string
    {
        $random = explode('-', \Str::uuid()->toString())[0];
        return $productId.'-'.$random;
    }
}