<?php

namespace Domain\Catalog\Actions;

use Domain\Catalog\DataTransferObjects\ProductVariantFormData;
use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\Product;
use Domain\FileManager\Models\FileUpload;
use Domain\Catalog\ViewModels\ProductVariantViewModel;

class UpsertProductVariantAction
{
    static function execute(
        ProductVariantFormData $data,
        Product $product
    ): ProductVariantViewModel|ActionException
    {
        try {
            // Get internal image id for stroing in DB
            $imageId = self::getImageId($data, $product);

            $variantData = [
                ...$data->except('public_id', 'image_id')->toArray(),
                'image_id' => $imageId,
                'sku' => $data->sku ?? self::generateRandomSKU($product->id)
            ];
            
            // Checking it is a create or update operation ?
            if($data->public_id !== null)
            {
                $productVariant = $product->variants()
                ->where('public_id', $data->public_id)
                ->firstOrFail();
                
                // Get previous attributes and merge with new attributes
                $variantData['attributes'] = [
                    // old attributes list
                    ...($productVariant->attributes ?? []),
                    // updated items (it might add or update current items)
                    ...($data->attributes ?? [])
                ];

                $productVariant->update($variantData);
            }
            else
            {
                $productVariant = $product->variants()->create($variantData);
            }

            return new ProductVariantViewModel($productVariant);
        }
        catch(\Exception $e)
        {
            logger()->info($e->getMessage());
            return  ActionException::from($e);
        }
    }

    private static function generateRandomSKU(int $productId) :string
    {
        $random = explode('-', \Str::uuid()->toString())[0];
        return $productId.'-'.$random;
    }

    private static function getImageId(
        ProductVariantFormData &$data, 
        Product &$product
    ) {

        if(
            !is_null($data->image_id) &&
            !is_null($product->images) && 
            !empty($product->images)
        )
        {
            // Get product image ids.
            $imageIdsList = FileUpload::getFilesGroupedByPublicId($product->images);
            
            return  isset($imageIdsList[$data->image_id])
                    ? $imageIdsList[$data->image_id]['id']
                    : null;
        }

        return null;
    }
}