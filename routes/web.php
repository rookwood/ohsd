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

Route::get('/', function () {
    return view('welcome');
});



Route::group(['middleware' => 'auth'], function () {
    Route::get('audiograms/{patient}/create', 'AudiogramsController@create')->name('audiograms.create');
    Route::post('audiograms/{patient}/store', 'AudiogramsController@store')->name('audiograms.store');

    Route::get('patients', 'PatientsController@index')->name('patients.index');
    Route::get('patient/{patient}', 'PatientsController@show')->name('patients.show');

    Route::post('patients/new', 'PatientsController@store')->name('patients.store');

    Route::post('users/new', 'UsersController@store')->name('users.store');

    Route::post('intake/{patient}', 'IntakeFormController@store')->name('intake.store');
    Route::get('intake/{patient}/new', 'IntakeFormController@create')->name('intake.create');
    Route::post('intake/{form}/update', 'IntakeFormController@update')->name('intake.update');
});

Route::get('users/register', 'UsersController@create')->name('users.create');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('registration/{token}', 'RegistrationController@create')->name('registration.create');
Route::post('registration/complete', 'RegistrationController@store')->name('registration.store');


Auth::routes();
