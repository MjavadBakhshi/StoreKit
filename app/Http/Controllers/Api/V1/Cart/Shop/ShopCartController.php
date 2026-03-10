<?php

namespace App\Http\Controllers\Api\V1\Cart\Shop;

use App\Http\Controllers\Controller;
use Domain\Cart\Actions\Shop\UpdateShopCartAction;
use Domain\Cart\DataTransferObjects\Shop\ShopCartItemFormData;
use Illuminate\Http\Request;

class ShopCartController extends Controller
{
    function index()
    {}

    function update(ShopCartItemFormData $data)
    {
        $result = UpdateShopCartAction::execute($data);

        if($this->isActionExeption($result))
            return $this->failedResponse(
                        message: $result->getMessage(),
                        data: $result->getData()
                    );

        return $this->successResponse(data: $result->toArray());
    }
}
