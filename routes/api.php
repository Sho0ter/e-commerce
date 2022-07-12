<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Auth;

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
        // can add products to cart. 
        // processing order.

    });

    Route::middleware(['auth:sanctum', 'merchant'])->group(function () {
        //CRUD operation for products.
        //update own store info.

    });
});
