<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
   
/*
 * Call the function "allied" in the "IndexController" class.
 * 
 * The IndexController class is in the Playnet\WwiiOnline\Gazette namespace
 * as defined by the grouping function above
 * 
 * The "as" statement gives this route a name. We can hen use the name to auto
 * generate links
 */
Route::get('/allied', ['uses' => 'IndexController@allied', 'as' => 'allied-gazette']);

/*
 * Call the function "axis" in the "IndexController" class.
 * 
 */
Route::get('/axis', ['uses' => 'IndexController@axis', 'as' => 'axis-gazette']);

/*
 * Call the function "index" in the "IndexController" class.
 * 
 * The IndexController class is in the Playnet\WwiiOnline\Gazette namespace
 * as defined by the grouping function above
 */
Route::get('/', ['uses' => 'IndexController@index', 'as' => 'index-gazette']);
