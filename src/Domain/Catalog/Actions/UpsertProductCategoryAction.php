<?php

namespace Domain\Catalog\Actions;

use Illuminate\Support\Facades\DB;

use Domain\Catalog\DataTransferObjects\ProductCategoryFormData;
use Domain\Shared\Exceptions\ActionException;
use Domain\Catalog\Models\ProductCategory;
use Domain\Store\Models\Store;
use Domain\Catalog\ViewModels\ProductCategoryViewModel;

class UpsertProductCategoryAction
{
    protected static bool $isUpdating = false;

    static function execute(
        ProductCategoryFormData $data, 
        Store $store
    ) :ActionException|ProductCategoryViewModel
    {
        self::$isUpdating = !is_null($data->public_id);

        try {
            DB::beginTransaction();

                if(self::$isUpdating) # UPDATE
                {
                    // put lock on the product record.
                    $productCategory = $store->productCategories()
                                ->with('parent')
                                ->lockForUpdate()
                                ->where('public_id', $data->public_id)
                                ->firstOrFail();

                    // save changes
                    $productCategory->fill([
                        ...$data->except('parent_public_id')->toArray(),
                    ]);
                }
                else # INSERT
                {
                    // create new product category record.
                    $productCategory = $store->productCategories()->make([
                        ...$data->toArray(),
                    ]);
                }


                if($data->parent_public_id != $productCategory->parent?->public_id)
                {
                    // calling a method to check if this change cause a loop?
                    $productCategory->parent_id = 
                    ProductCategory::where('public_id', $data->parent_public_id)
                        ->value('id');
                }

                $productCategory->save();
               
            DB::commit();

            // Load parent relationship before sending response as ViewModel.
            $productCategory->load('parent');
            return new ProductCategoryViewModel($productCategory);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            logger()->error($e);
            return  ActionException::from($e);
        }
    }


    private static function checkLoop() {}
}