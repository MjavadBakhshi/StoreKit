<?php

namespace Domain\Catalog\Actions;

use Illuminate\Support\Facades\DB;

use Domain\FileManager\Actions\UploadFilesAction;
use Domain\Catalog\DataTransferObjects\{
    ProductFormData,
    ProductVariantFormData
};
use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;
use Domain\Catalog\ViewModels\ProductViewModel;

class UpsertProductAction
{
    protected static bool $isUpdating = false;

    static function execute(
        ProductFormData $data, 
        Store $store
    ) :ActionException|ProductViewModel
    {
        self::$isUpdating = !is_null($data->public_id);

        try {
            DB::beginTransaction();

                if(self::$isUpdating) # UPDATE
                {
                    // put lock on the product record.
                    $product = $store->products()
                                ->lockForUpdate()
                                ->where('public_id', $data->public_id)
                                ->firstOrFail();
                     
                    // save changes
                    $product->update([
                        ...$data->except('product_type', 'images')->toArray(),
                        'product_type' => $data->product_type, 
                    ]);
                }
                else # INSERT
                {
                    // create new product record.
                    $product = $store->products()->create([
                        ...$data->except('product_type', 'images')->toArray(),
                        'product_type' => $data->product_type,
                    ]);
                }

                // Upsert deffault product variant.
                self::upsertDefaultProductVariant($data, $product);

                // uploading images of the product.
                self::uploadImages($data, $product, $store);

            DB::commit();

            return new ProductViewModel($product);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            logger()->error($e);
            return  ActionException::from($e);
        }

    }

    private static function upsertDefaultProductVariant(
        ProductFormData &$data, 
        Product &$product
    )
    {
        // Prepare DTO
        $productVariantFormData = ProductVariantFormData::validateAndCreate([
            ...$data->default_variant,
            'is_default_variant' => true,
            'product_type' => $data->product_type,
            'public_id' => 
            self::$isUpdating 
            ? $product->defaultVariant->public_id
            : null
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
    }

    private static function uploadImages(
        ProductFormData &$data, 
        Product &$product,
        Store &$store
    )
    {
        //TODO: it might upload iamges in the background to prevent latency
        // Uploading images 
        if(is_array($data->images) && !empty($data->images))
        {
            $filesList = UploadFilesAction::execute($data->images, $store);

            if(is_array($filesList) && !empty($filesList))
            {
                $oldImages = $product->images ?? [];
                $filesList = [...$oldImages, ...$filesList];
                $product->update(['images' => $filesList]);
            }   
        }
    }
}