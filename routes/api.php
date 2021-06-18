<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CallbackController;
use App\Http\Controllers\API\LazopController;

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


Route::get('callback', [CallbackController::class, 'index']);
Route::post('callback', [CallbackController::class, 'store']);
Route::get('seller', [LazopController::class, 'get_seller']);
Route::get('product', [LazopController::class, 'get_product']);
Route::get('transaction', [LazopController::class, 'get_transaction']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});