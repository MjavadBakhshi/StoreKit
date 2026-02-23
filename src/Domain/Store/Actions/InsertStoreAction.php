<?php

namespace Domain\Store\Actions;

use Domain\Shared\Exceptions\ActionException;
use Domain\Store\DataTransferObjects\StoreFormData;
use Domain\Account\Models\User;
use Domain\Store\ViewModels\NewStoreViewModel;

class InsertStoreAction
{
    static function execute(
        StoreFormData $data, 
        User $user
    ) :ActionException|NewStoreViewModel
    {
        try {

            $store = $user->stores()->create($data->toArray());

            return new NewStoreViewModel($store);
        }
        catch(ActionException $e)
        {
            return $e;
        }

    }
}