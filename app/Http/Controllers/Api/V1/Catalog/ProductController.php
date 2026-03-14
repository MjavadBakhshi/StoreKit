<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use Domain\Catalog\Actions\DeleteProductAction;
use Illuminate\Http\Request;

use Domain\Catalog\Actions\InsertProductAction;
use Domain\Catalog\DataTransferObjects\ProductFormData;
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Store $store)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormData $data, Request $request)
    {
        $result = InsertProductAction::execute($data, $request->store);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: $result->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store, Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Store $store, Product $product)
    {
        //
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
