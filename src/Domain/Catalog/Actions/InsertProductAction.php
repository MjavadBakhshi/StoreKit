<?php

namespace Domain\Catalog\Actions;

use Domain\Account\Models\User;
use Domain\Catalog\DataTransferObjects\ProductFormData;
use Domain\Catalog\ViewModels\NewProductViewModel;
use Domain\Shared\Exceptions\ActionException;
use Domain\Store\Models\Store;

class InsertProductAction
{
    static function execute(
        ProductFormData $data, 
        Store $store
    ) :ActionException|NewProductViewModel
    {
        try {
            $product = $store->products()->create($data->toArray());
            
            return new NewProductViewModel($product);
        }
        catch(\Exception $e)
        {
            logger()->error($e);
            return  ActionException::from($e);
        }

    }
}