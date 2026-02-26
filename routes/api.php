<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    Account\AuthController,
    Store\StoreController,
    Catalog\ProductController,
};

Route::prefix('v1')->group(function () {
    
    // Account Authentication
    Route::post('/account/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function() {

            // Store
            Route::post('/stores', [StoreController::class, 'store']);

            // Catalog
            Route::post('/stores/{store}/product', [ProductController::class, 'store']);

    }); // End protected routes



}); // End of v1.0 api routes

