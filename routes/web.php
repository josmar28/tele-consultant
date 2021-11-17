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
Route::Auth();
Route::get('/', 'Auth\LoginController@index');
Route::post('login', 'Auth\LoginController@login');
Route::get('/logout', function(){
    $user = \Illuminate\Support\Facades\Session::get('auth');
    \Illuminate\Support\Facades\Session::flush();
    if(isset($user)){
        \App\User::where('id',$user->id)
            ->update([
                'login_status' => 'logout'
            ]);
        $logout = date('Y-m-d H:i:s');
        $logoutId = \App\Login::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->first()
            ->id;

        \App\Login::where('id',$logoutId)
            ->update([
                'status' => 'login_off',
                'logout' => $logout
            ]);
    }
    return redirect('/');
});
// SuperSuperadmin Module
Route::get('superadmin','Superadmin\HomeController@index');
Route::get('/users', 'Superadmin\ManageController@indexUser');
Route::post('/user-deactivate/{id}', 'Superadmin\ManageController@deactivateUser');
Route::post('/user-store', 'Superadmin\ManageController@storeUser');
Route::get('/facilities', 'Superadmin\ManageController@indexFacility');
Route::get('/facilities/{id}/{type}', 'Superadmin\ManageController@getMunandBrgy');
Route::post('/facility-store', 'Superadmin\ManageController@storeFacility');
Route::post('/facility-delete/{id}', 'Superadmin\ManageController@deleteFacility');
Route::get('/provinces', 'Superadmin\ManageController@indexProvince');
Route::post('/province-store', 'Superadmin\ManageController@storeProvince');
Route::post('/province-delete/{id}', 'Superadmin\ManageController@deleteProvince');
Route::match(['GET','POST'],'/municipality/{province_id}/{province_name}','Superadmin\ManageController@viewMunicipality');
Route::post('/municipality-store', 'Superadmin\ManageController@storeMunicipality');
Route::post('/municipality-delete/{id}', 'Superadmin\ManageController@deleteMunicipality');
Route::match(['GET','POST'],'/barangay/{prov_id}/{prov_name}/{mun_id}/{mun_name}','Superadmin\ManageController@viewBarangay');
Route::post('/barangay-store', 'Superadmin\ManageController@storeBarangay');
Route::post('/barangay-delete/{id}', 'Superadmin\ManageController@deleteBarangay');

//Admin Module
Route::get('admin','Admin\HomeController@index');

// Doctor Module
Route::get('doctor','Doctor\HomeController@index');

// Patient Module 
Route::get('patient','Patient\HomeController@index');
Route::get('patient/list','Patient\PatientController@patientList');
Route::post('doctor/patient/update','Patient\PatientController@patientUpdate');
Route::get('location/barangay/{muncity_id}','Patient\PatientController@getBaranggays');

