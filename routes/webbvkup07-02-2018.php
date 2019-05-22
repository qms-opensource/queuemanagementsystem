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
//use Session;

Route::get('/', function () {
    return view('auth.login');
});


/* Route::get('/logout', function(){
	Auth::logout();
	 Session::flush();
	return redirect('/');
});
*/
//Route::get('/logout', 'ManagePatientController@index')->name('home');
Auth::routes();
Route::get('/logout', 'ManageUserController@logout');
Route::get('/pagenotfound', 'HomeController@pageNotFound');
Route::get('/test', 'HomeController@myTest')->name('home');
Route::get('/manage-users/send-mail/','ManageUserController@sendMail');	
Route::group(['middleware' => 'revalidate'], function()
{
	Route::get('/home', 'ManagePatientController@index')->name('dashboardData');
	Route::get('/unauthorized-access', 'Controller@showUnauthorizedPage');  //unauthorized-access
	Route::get('/manage-patient', 'ManagePatientController@index')->name('home');
	Route::get('/manage-patient/add', 'ManagePatientController@add')->name('home');

	Route::get('/manage-patient/get-doctors/{id}', 'ManagePatientController@getDoctorsByDepartment')->name('home');
	Route::post('/manage-patient/save', 'ManagePatientController@save');
	Route::get('/manage-patient/edit/{id}', 'ManagePatientController@edit');
	Route::post('/manage-patient/update', 'ManagePatientController@update');
	Route::get('/manage-patient/delete/{id}', 'ManagePatientController@delete');
	Route::get('/manage-patient/fcm', 'ManagePatientController@fcm');

	// Routing for the Manage Halls
	Route::get('/manage-hall','ManageHallController@index');
	Route::get('/manage-hall/add','ManageHallController@add');
	Route::post('/manage-hall/save','ManageHallController@save');
	Route::get('/manage-hall/edit/{id}','ManageHallController@edit');
	Route::post('/manage-hall/update/','ManageHallController@update');
	Route::get('/manage-hall/delete/{id}','ManageHallController@deleteHall');
	Route::post('/manage-patient/checkadhar','ManagePatientController@checkAdhar');
	Route::post('/manage-patient/check-adhar','ManagePatientController@checkUniqueAdhar');
	Route::post('/manage-patient/check-crno','ManagePatientController@checkUniqueCrno');
	Route::post('/manage-patient/check-phone','ManagePatientController@checkUniquePhone');
	Route::post('/manage-patient/check-room-for-depart','ManagePatientController@checkRoomForDepart');
	Route::get('/manage-patient/get-room/{id}/{room_id}' ,'ManageRoomController@getRoomsByDepartment');

	//Manage Tokens

	Route::get('/token-status','TokenStatusController@index');

	/*
		API's
	*/

	Route::get('/get-patient/{crno}/{device_id}', 'ManagePatientController@patientData');
	Route::get('/is-doctor-valid/{doctor_phone}', 'ManagePatientController@isDoctorValid');
	Route::get('/token-status/{doctor_phone}', 'ManagePatientController@tokenStatus');

	/*
		End API's
	*/

	Route::get('/manage-patient/patient-status','ManagePatientController@patientStatus');

	Route::resource('/department','DepartmentController');
	Route::get('/department/edit/{id}','DepartmentController@edit');
	Route::post('/department/update/','DepartmentController@update');
	Route::get('/department/delete/{id}','DepartmentController@deleteDepartment');

	Route::get('/manage-doctors/treat-patient','DoctorController@treatPatient');
	Route::resource('/manage-doctors','DoctorController');
	Route::get('/manage-doctors/edit/{id}','DoctorController@edit');
	Route::post('/manage-doctors/update/','DoctorController@update');
	Route::get('/manage-doctors/delete/{id}','DoctorController@deleteDoctor');


	// Routing for the Manage Rooms allotment
	Route::resource('/manage-rooms','ManageRoomController');
	Route::get('/manage-rooms/edit/{id}','ManageRoomController@edit');
	Route::post('/manage-rooms/update/','ManageRoomController@update');
	Route::get('/manage-rooms/delete/{id}','ManageRoomController@deleteRoom');
	Route::get('/manage-rooms/get-hall/{id}','ManageRoomController@getHallForDepartment');
	Route::get('/manage-rooms/updateStatus/{id}/{status}','ManageRoomController@updateStatus');

	// Routing for the Manage Rooms allotment
	Route::resource('/manage-roles','ManageRoleController');
	Route::get('/manage-roles/edit/{id}','ManageRoleController@edit');
	Route::post('/manage-roles/update/','ManageRoleController@update');
	Route::get('/manage-roles/delete/{id}','ManageRoleController@deleteRole');
	Route::get('/manage-roles/checkRole/{id}','ManageRoleController@checkRole');

	// Routing for the Manage Rooms allotment
	Route::resource('/manage-users','ManageUserController');
	Route::get('/manage-users/edit/{id}','ManageUserController@edit');
	Route::post('/manage-users/update/','ManageUserController@update');
	Route::get('/manage-users/delete/{id}','ManageUserController@deleteRole');
	Route::post('manage-users/check-email/','ManageUserController@checkUniqueEmail');

	// Routing for the Manage Patient Appointments with radiologists
	Route::get('/manage-doctor/doctor-portal','ManageDoctorController@showDoctorPortal');
	Route::get('/manage-doctor/patient-appointment','ManageDashboardController@showDoctorAppointment');
	Route::get('/manage-doctor/manage-patient-appointment/inprocess/{crno}/{id}','ManageDashboardController@updateInProcess');
	Route::get('/manage-doctor/manage-patient-appointment/process/{crno}','ManageDashboardController@updateProcess');
	Route::get('/manage-doctor/manage-patient-appointment/skipp/{crno}','ManageDashboardController@updateSkipp');
	Route::get('/manage-patient/dashboardData/{id}','ManagePatientController@dashboardData');
	Route::get('/manage-doctor/manage-patient-appointment/requeue/{crno}/{room_no}' , 'ManageDashboardController@updateToInqueue');
	
});