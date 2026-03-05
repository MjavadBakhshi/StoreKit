<?php

namespace App\Http\Controllers\Api\V1\Catalog\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Catalog\ViewModels\Shop\ShopProductListViewModel;
use Domain\Catalog\ViewModels\Shop\ShopProductViewModel;

class ShopProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = []; #TODO later filters will be added.
        $products = new ShopProductListViewModel(
            $request->store, 
            $filters
        );

        return $this->successResponse(
            data: $products->toArray(),
        );
    }

    public function show(Request $request)
    {
        return $this->successResponse(
            data: (new ShopProductViewModel($request->product))->toArray()
        );
    }
}
