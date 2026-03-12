<?php

namespace Domain\Catalog\Actions;

use Domain\Catalog\DataTransferObjects\ProductVariantFormData;
use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\ProductVariant;

class UpsertProductVariantAction
{
    static function execute(
        ProductVariantFormData $data,
        Product $product
    ): ProductVariant|false
    {
        try {
            $variantData = [
                ...$data->except('public_id')->toArray(),
                'sku' => $data->sku ?? self::generateRandomSKU($product->id)
            ];
            
            // Checking it is a create or update operation ?
            if($data->public_id !== null)
            {
                $productVariant = $product->variants()
                    ->where('public_id', $data->public_id)
                    ->firstOrFail();

                $productVariant->update($variantData);
            }
            else
            {
                $productVariant = $product->variants()->create($variantData);
            }

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