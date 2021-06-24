<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ijcController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});
Route::get('/landingpage', function () {
    return view('landingpage');
});

Route::group(['prefix' => 'ijc'], function(){
    Route::get('/', [ijcController::class, 'index']);
    Route::get('transaction', [ijcController::class, 'transaction']);
    Route::get('order', [ijcController::class, 'order']);
});

Route::group(['prefix' => 'ijclocal'], function(){
    Route::get('/', [ijcController::class, 'ijclokal']);
    Route::get('edit/{id}', [ijcController::class, 'edit']);
    Route::post('update', [ijcController::class, 'update']);
});
