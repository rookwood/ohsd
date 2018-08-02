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
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
