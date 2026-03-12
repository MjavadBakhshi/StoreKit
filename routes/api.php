<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    Account\AuthController,
    Store\StoreController,
    Catalog\ProductController,
    Catalog\ProductVariantController,
    Catalog\Shop\ShopProductController,
    Cart\Shop\ShopCartController,
};
// Middlewares
use Domain\Catalog\Middleware\{ShopProductBinder, ShopProductVariantBinder};
use Domain\Store\Middleware\{
    DefaultStoreResolver, 
    DomainStoreResolver, 
    EntityStoreOwnershipChecker
};

Route::prefix('v1')->group(function () {
    
    // Account Authentication
    Route::post('/account/login', [AuthController::class, 'login']);


    ### START Store owner panel routes ###

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function() {

        // Store
        Route::post('/stores', [StoreController::class, 'store']);

        Route::middleware([DefaultStoreResolver::class])
            ->group(function(){
              
                // Catalog

                // products
                Route::apiResource(
                    '/products', 
                    ProductController::class
                );
                
                // product variants
                Route::apiResource(
                    '/produccts/{product}/variants',
                    ProductVariantController::class
                )
                ->names('products.variants')
                ->parameters(['variants' => 'product_variant'])
                ->middleware(EntityStoreOwnershipChecker::class.':product')
                ->except(['index', 'show']);

            });
            

    }); // End protected routes

    ### END Store owner panel routes ###


    ### START shop routes ###

    Route::prefix('/shop')
        ->middleware([DomainStoreResolver::class])
        ->name('shop.')
        ->group(function(){

        /** Catalog */

        Route::get(
            '/products', 
            [ShopProductController::class, 'index']
        )
        ->name('products.index');

        Route::get(
            '/products/{shop_product}', 
            [ShopProductController::class, 'show']
        )
        ->middleware([ShopProductBinder::class])
        ->name('products.show');   

        /** Cart */
        Route::post(
            '/carts/{shop_product_variant}', 
            [ShopCartController::class, 'update']
        )
        ->middleware(ShopProductVariantBinder::class)
        ->name('carts.update');   

    });
    ### END shop routes ###

}); // End of v1.0 api routes

