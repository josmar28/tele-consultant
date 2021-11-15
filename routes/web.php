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

Route::get('/', 'Auth\LoginController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Admin Module
Route::get('/users', 'Admin\ManageController@indexUser');
Route::post('/user-store', 'Admin\ManageController@storeUser');
Route::get('/facilities', 'Admin\ManageController@indexFacility');
Route::get('/facilities/{id}/{type}', 'Admin\ManageController@getMunandBrgy');
Route::post('/facility-store', 'Admin\ManageController@storeFacility');
Route::post('/facility-delete/{id}', 'Admin\ManageController@deleteFacility');
Route::get('/provinces', 'Admin\ManageController@indexProvince');
Route::post('/province-store', 'Admin\ManageController@storeProvince');
Route::post('/province-delete/{id}', 'Admin\ManageController@deleteProvince');
Route::match(['GET','POST'],'/municipality/{province_id}/{province_name}','Admin\ManageController@viewMunicipality');
Route::post('/municipality-store', 'Admin\ManageController@storeMunicipality');
Route::post('/municipality-delete/{id}', 'Admin\ManageController@deleteMunicipality');
Route::match(['GET','POST'],'/barangay/{prov_id}/{prov_name}/{mun_id}/{mun_name}','Admin\ManageController@viewBarangay');

