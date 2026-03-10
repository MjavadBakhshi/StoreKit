<?php

namespace Domain\Catalog\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Domain\Catalog\Models\ProductVariant;

/**
 * This is responsible for attaching product variant model into request object
 * by applying filters to ensure getting current store active product variant.
 */
class ShopProductVariantBinder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $bindingKey = 'public_id'): Response
    {
        $productVariant = ProductVariant::getShopProductVariant(
            bindingKey: $bindingKey,
            value: $request->shop_product_variant,
            store: $request->store
        );

         // In case which product variant does not belong to the store.
        if(is_null($productVariant)) return abort(404);

        $request->merge([
            'product_variant' => $productVariant
        ]);

        return $next($request);
    }
}
