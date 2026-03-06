<?php

namespace Domain\Catalog\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Domain\Catalog\Models\Product;

/**
 * This is responsible for attaching product model into request object
 * by applying filters to ensure getting current store active product.
 * Note: product slug is not unique globally and it is unique per store.
 */
class ShopProductBinder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $bindingKey = 'slug'): Response
    {
        $product = Product::getShopProduct(
            bindingKey: $bindingKey,
            value: $request->shop_product,
            store: $request->store
        );

        // In case which product is not active or product does not belong to the store.
        if(is_null($product)) return abort(404);

        $request->merge([
            'product' => $product
        ]);

        return $next($request);
    }
}
