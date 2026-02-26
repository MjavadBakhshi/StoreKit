<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;

use Domain\Catalog\Actions\InsertProductAction;
use Domain\Catalog\DataTransferObjects\ProductFormData;
use Domain\Store\Models\Store;

class ProductController extends Controller
{
    function store(ProductFormData $data, Store $store)
    {
        $result = InsertProductAction::execute($data, $store);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: $result->toArray());
    }
}
