<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;

use Domain\Catalog\Actions\{
    UpsertProductVariantAction,
    DeleteProductVariantAction
};
use Domain\Catalog\DataTransferObjects\ProductVariantFormData;
use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Catalog\ViewModels\NewProductVariantViewModel;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{

    public function store(Product $product, Request $request)
    {        
        $data = ProductVariantFormData::validateAndCreate([
            ...$request->all(),
            'product_type' => $product->product_type
        ]);
        
        $result = UpsertProductVariantAction::execute($data, $product);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        $viewModel = new NewProductVariantViewModel($result);
        return $this->successResponse(data: $viewModel->toArray());
    }

    public function update(
        Product $product, 
        ProductVariant $product_variant, 
        Request $request
    )
    {
        // Validate product-variant pair
        $this->isVariantForProduct($product, $product_variant);

        return $this->store($product, $request);
    }

    public function destroy(Product $product, ProductVariant $product_variant)
    {
        // Validate product-variant pair
        $this->isVariantForProduct($product, $product_variant);

        $result = DeleteProductVariantAction::execute($product_variant);

        if($this->isActionExeption($result))
            return $this->failedResponse(message: $result->getMessage());

        return $this->successResponse(data: [
            'public_id' => $product_variant->public_id,
        ]);

    }

    // Checking the variant belongs to product ?
    // Checking Url parameters correctness.
    private function isVariantForProduct(
        Product &$product, 
        ProductVariant &$productVariant
    ) :void
    {
        if($product->id != $productVariant->product_id) abort(403);
    }
}
