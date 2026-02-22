<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    Account\AuthController
};

Route::prefix('v1')->group(function () {
    
    // Account Authentication
    Route::post('/account/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware(['auth:santum'])->group(function() {

            

    }); // End protected routes



}); // End of v1.0 api routes

