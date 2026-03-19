<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Catalog\Actions\{
    UpsertProductAction,
    DeleteProductAction
};
use Domain\Catalog\DataTransferObjects\ProductFormData;
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

class ProductController extends Controller
{

    public function index(Store $store)
    {
        //
    }

    public function store(ProductFormData $data, Request $request)
    {
        $result = UpsertProductAction::execute($data, $request->store);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: $result->toArray());
    }

    public function show(Store $store, Product $product)
    {
        //
    }

    public function update(Product $product, Request $request)
    {
        $productFormData = ProductFormData::validateAndCreate([
            ...$request->all(),
            'public_id' => $product->public_id,
            'product_type' => $product->product_type,
        ]);

        return $this->store($productFormData, $request);
    }

    public function destroy(Product $product)
    {
        $result = DeleteProductAction::execute($product);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: [
            'public_id' => $product->public_id,
        ]);
    }
}
