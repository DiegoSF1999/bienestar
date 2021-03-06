<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', 'UsersController')->middleware('token');
Route::apiResource('apps', 'AppsController')->middleware('token');
Route::apiResource('locations', 'LocationsRelationController')->middleware('token');
Route::apiResource('usages', 'UsagesRelationController')->middleware('token');
Route::apiResource('restrictions', 'RestrictionsRelationController')->middleware('token');


Route::POST('login', 'UsersController@login');
Route::POST('recover', 'UsersController@recover_password');
Route::POST('reset', 'UsersController@reset_password');
Route::POST('users', 'UsersController@store');
Route::GET('getmyUser', 'UsersController@getmyUser');
Route::POST('assignapp', 'UsersController@link_user_app')->middleware('token');
Route::GET('myapps', 'UsersController@show_user_apps')->middleware('token');
Route::GET('totaluse', 'UsagesRelationController@get_total_use')->middleware('token');
Route::POST('todayuse', 'UsagesRelationController@get_today_use')->middleware('token');
Route::GET('averageuse', 'UsagesRelationController@get_average_use')->middleware('token');
Route::GET('stadistics', 'UsagesRelationController@get_stadistics')->middleware('token');
Route::GET('annualuse', 'UsagesRelationController@get_annual_use')->middleware('token');
Route::GET('weeklyuse', 'UsagesRelationController@get_weekly_use')->middleware('token');
Route::GET('lastlocation', 'LocationsRelationController@get_last_location')->middleware('token');
Route::POST('restrictionremove', 'RestrictionsRelationController@remove')->middleware('token');
Route::POST('restrictionupdate', 'RestrictionsRelationController@change')->middleware('token');





Route::GET('users', 'UsersController@index');
Route::POST('getusages', 'UsagesRelationController@index')->middleware('token');






