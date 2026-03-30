<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Catalog\Actions\{
    UpsertProductAction,
    DeleteProductAction,
    UpsertProductCategoryAction
};
use Domain\Catalog\DataTransferObjects\ProductCategoryFormData;
use Domain\Catalog\Models\ProductCategory;
use Domain\Store\Models\Store;

class ProductCategoryController extends Controller
{

    public function index(Store $store)
    {
        //
    }

    public function store(ProductCategoryFormData $data, Request $request)
    {
        $result = UpsertProductCategoryAction::execute($data, $request->store);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: $result->toArray());
    }

    public function update(ProductCategory $product_category, Request $request)
    {
        $productCategoryFormData = ProductCategoryFormData::validateAndCreate([
            ...$request->all(),
            'public_id' => $product_category->public_id,
        ]);

        return $this->store($productCategoryFormData, $request);
    }

    public function destroy(ProductCategory $product)
    {
    }
}
