<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth', 'namespace' => 'Api'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@user');
});

Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {

    /*
     * Public Endpoints (no auth required — for map display)
     */
    Route::get('outlets/geojson', 'OutletController@geojson')->name('outlets.geojson');
    Route::get('outlets/{id}',    'OutletController@show')->name('outlets.show');
    Route::get('outlets',         'OutletController@index')->name('outlets.index');
    Route::get('opd/all',         'OpdController@all')->name('opd.all');
    Route::get('opd/{id}',        'OpdController@show')->name('opd.show');
    Route::get('opd',             'OpdController@index')->name('opd.index');
    Route::get('dashboard/stats', 'DashboardController@stats')->name('dashboard.stats');

    /*
     * Protected Endpoints (require authentication)
     */
    Route::middleware('auth:api')->group(function () {
        // Aset Tanah (Outlets) CRUD
        Route::post('outlets',           'OutletController@store')->name('outlets.store');
        Route::put('outlets/{outlet}',   'OutletController@update')->name('outlets.update');
        Route::delete('outlets/{outlet}','OutletController@destroy')->name('outlets.destroy');

        // OPD CRUD
        Route::post('opd',              'OpdController@store')->name('opd.store');
        Route::put('opd/{id}',          'OpdController@update')->name('opd.update');
        Route::delete('opd/{id}',       'OpdController@destroy')->name('opd.destroy');
    });

});
