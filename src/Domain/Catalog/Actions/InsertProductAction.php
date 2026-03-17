<?php

namespace Domain\Catalog\Actions;

use Illuminate\Support\Facades\DB;

use Domain\Catalog\DataTransferObjects\{
    ProductFormData,
    ProductVariantFormData
};
use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Store\Models\Store;
use Domain\Catalog\ViewModels\NewProductViewModel;
use Domain\FileManager\Actions\UploadFilesAction;

class InsertProductAction
{
    static function execute(
        ProductFormData $data, 
        Store $store
    ) :ActionException|NewProductViewModel
    {
        try {
            DB::beginTransaction();

                $product = $store->products()->create([
                    ...$data->except('product_type', 'images')->toArray(),
                    'product_type' => $data->product_type,
                ]);
                
                // Insert deffault product variant.
                $productVariant = self::insertDefaultProductVariant($data, $product);

                //TODO: it might upload iamges in the background to prevent latency
                // Uploading images 
                if(is_array($data->images) && !empty($data->images))
                {
                    $filesList = UploadFilesAction::execute($data->images, $store);
                    if(is_array($filesList) && !empty($filesList))
                        $product->update(['images' => $filesList]);
                }

            DB::commit();

            return new NewProductViewModel($product, $productVariant);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            logger()->error($e);
            return  ActionException::from($e);
        }

    }

    private static function insertDefaultProductVariant(
        ProductFormData &$data, 
        Product &$product
    ) :ProductVariant
    {
        // Prepare DTO
        $productVariantFormData = ProductVariantFormData::validateAndCreate([
            'product_type' => $data->product_type,
            'stock' => $data->stock,
            'price' => $data->price,
            'is_default_variant' => true,
        ]);

        // Store default product variant.
        $defaultProductVariant = UpsertProductVariantAction::execute(
            $productVariantFormData,
            $product
        );

        throw_if(
            !$defaultProductVariant,
            new ActionException("The default product variant is not stored successfully.")
        );

        return $defaultProductVariant;
    }
}