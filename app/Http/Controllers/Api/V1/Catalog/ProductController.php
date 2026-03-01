<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;

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
    public function store(ProductFormData $data, Store $store)
    {
        $result = InsertProductAction::execute($data, $store);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store, Product $product)
    {
        //
    }
}
