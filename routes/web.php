<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'DashboardController@index')->middleware('auth');

Auth::routes();

Route::resource('consultations','ConsultationController')->middleware('auth');

Route::get('/generate/{nbrequetes}', 'DefaultController@generaterequest');

Route::get('/{reqtype}/{phonenum}/', 'DefaultController@defaultrequest');

Route::get('/selectmoretypedemandes', 'ConsultationController@selectmoretypedemandes')->middleware('auth');
Route::get('/selectmorestatutrequetes', 'ConsultationController@selectmorestatutrequetes')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');
