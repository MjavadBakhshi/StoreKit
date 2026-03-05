<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    Account\AuthController,
    Store\StoreController,
    Catalog\ProductController,
    Catalog\Shop\ShopProductController,
};
// Middlewares
use Domain\Catalog\Middleware\ShopProductBinder;
use Domain\Store\Middleware\DomainStoreResolver;

Route::prefix('v1')->group(function () {
    
    // Account Authentication
    Route::post('/account/login', [AuthController::class, 'login']);


    ### START Store owner panel routes ###

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function() {

        // Store
        Route::post('/stores', [StoreController::class, 'store']);

        // Group rotues which requires store ownership before doing action.
        Route::middleware([
            'can:store-ownership,store'
        ])
        ->group(function(){
            
            // Catalog
            Route::apiResource(
                '/stores/{store}/products', 
                ProductController::class
            );
            
            
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
            '/products/{shop_product:slug}', 
            [ShopProductController::class, 'show']
        )
        ->middleware([ShopProductBinder::class])
        ->name('products.show');

    });
    ### END shop routes ###

}); // End of v1.0 api routes

