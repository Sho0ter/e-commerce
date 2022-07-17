<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController as APICartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\StoreController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware("localization")->group(function () {

    //get user loggedIn info    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/register', [AuthController::class, 'registerUser'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        //general 
        Route::post('/logout',  [AuthController::class, 'logout']);
    });

    Route::middleware(['auth:sanctum', 'consumer'])->group(function () {
        Route::get('products/list', [ProductController::class, 'list_products']);
        Route::resource('carts', APICartController::class);
    });

    Route::middleware(['auth:sanctum', 'merchant'])->group(function () {
        Route::put('/stores/update', [StoreController::class, 'update']);
        Route::resource('/products', ProductController::class);
    });
});
