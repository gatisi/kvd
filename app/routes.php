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
Route::controller('shiftplans', 'ShiftplansController');
Route::controller('home', 'HomeController');

Route::group(array('before' => 'auth'), function()
{
	Route::get('/', 'ShiftPatternController@getIndex');
	Route::controller('shiftplan', 'ShiftPatternController');
});
/*
Event::listen('illuminate.query', function($sql, $bindings, $time){
   // echo $sql;          // select * from my_table where id=? 
   // print_r($bindings); // Array ( [0] => 4 )
   // echo $time;         // 0.58 

    // To get the full sql query with bindings inserted
    $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
    $full_sql = vsprintf($sql, $bindings);


});
*/
