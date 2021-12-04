<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

// migrate fresh db & db seed
Route::get('fresh-db', function () {
    Artisan::call('migrate:fresh --seed --force');

    return response()->json([
        'status' => 'success',
        'message' => 'Success fresh database.',
        'data' => null,
    ], 200);
});

// refresh cache
Route::get('refresh-cache', function () {
    Artisan::call('optimize');
    Artisan::call('optimize:clear');

    return response()->json([
        'status' => 'success',
        'message' => 'Success refresh cache.',
        'data' => null,
    ], 200);
});

Route::group(['prefix' => 'users'], function () {
    Route::post('/signup', 'RegisterController@index');
    Route::post('/signin', 'LoginController@login')->name('login');
});

Route::group(['middleware' => 'auth:api'], function () {
    // users route
    Route::get('/users', 'UserController@index');

    // shopping route
    Route::group(['prefix' => 'shopping'], function () {
        Route::get('/', 'ShoppingController@index');
        Route::post('/', 'ShoppingController@store');
        Route::get('/{id}', 'ShoppingController@show');
        Route::put('/{id}', 'ShoppingController@update');
        Route::delete('/{id}', 'ShoppingController@destroy');
    });
});
