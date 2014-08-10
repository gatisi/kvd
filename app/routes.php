<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::controller('welcome', 'WelcomeController');
Route::controller('users', 'UserController');
Route::controller('shiftplan', 'ShiftPatternController');
Route::controller('shiftplaner', 'ShiftPlanerController');

Route::group(array('before' => 'auth'), function()
{
	Route::get('/', 'ShiftPatternController@getIndex');
	Route::controller('shiftplan', 'ShiftPatternController');
});