<?php

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

Route::get('/', 'OutletMapController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('outlets', 'OutletController');

/*
 * Public Map Route
 */
Route::get('/our_outlets', 'OutletMapController@index')->name('outlet_map.index');


Route::get('/opd_data', 'OpdController@index')->name('opd_data');
Route::get('/opd_create', 'OpdController@opd_create')->name('opd_create');
Route::post('/opd_store', 'OpdController@store')->name('opd_store');