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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'user']);
    Route::get('/assemblies', [AssemblyController::class, 'assemblies']);
    Route::get('/assemblies/{assembly:ref}', [AssemblyController::class, 'assembly']);
    Route::post('/assemblies/{assembly:ref}/amendments/new', [AmendmentController::class, 'new']);
});
