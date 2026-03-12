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

class InsertProductAction
{
    static function execute(
        ProductFormData $data, 
        Store $store
    ) :ActionException|NewProductViewModel
    {
        try {
            DB::beginTransaction();

                $product = $store->products()->create($data->toArray());
                
                // Insert deffault product variant.
                $productVariant = self::insertDefaultProductVariant($data, $product);

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