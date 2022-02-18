<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AmendmentController;

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

Route::get('/user/login', [LoginController::class, 'login']);
Route::get('/user/auto/{token}', [LoginController::class, 'autologin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'user']);
    Route::get('/assemblies', [AssemblyController::class, 'assemblies']);
    Route::middleware('assembly')->group(function () {
        Route::get('/assembly/{assembly:ref}', [AssemblyController::class, 'assembly']);
        Route::get('/assembly/{assembly:ref}/amendments/{document:ref}', [AmendmentController::class, 'amendments']);
        Route::post('/assembly/{assembly:ref}/amendments/{document:ref}/new', [AmendmentController::class, 'new']);
        Route::get('/assembly/{assembly:ref}/amendment/{amendment:ref}', [AmendmentController::class, 'amendment']);
        Route::post('/assembly/{assembly:ref}/amendment/{amendment:ref}/support', [AmendmentController::class, 'support']);
    });
});
