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

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'api'], function()
{

	Route::post('authenticate', 'AuthenticateController@authenticate');
	Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
	Route::get('authenticate/organizations', 'AuthenticateController@getAvailableOrganizations');

    Route::get('admin/organizations', 'OrganizationController@index');
    Route::post('admin/organizations', 'OrganizationController@store');
    Route::get('admin/organizations/{id}', 'OrganizationController@show');
    Route::put('admin/organizations/{id}', 'OrganizationController@update');
    Route::delete('admin/organizations/{id}', 'OrganizationController@destroy');

    Route::get('admin/users', 'UserController@adminIndex');

    Route::get('admin/indicators', 'IndicatorController@adminIndex');

    Route::get('manager/users', 'UserController@managerIndex');

    Route::get('manager/indicators', 'IndicatorController@managerIndex');

    Route::get('manager/groups', 'GroupController@managerIndex');
    Route::post('manager/groups', 'GroupController@store');
    Route::get('manager/groups/{id}', 'GroupController@show');
    Route::put('manager/groups/{id}', 'GroupController@update');
    Route::delete('manager/groups/{id}', 'GroupController@destroy');

    Route::get('manager/reports', 'ReportController@managerIndex');
    Route::post('manager/reports', 'ReportController@store');
    Route::get('manager/reports/{id}', 'ReportController@show');
    Route::get('manager/reports/{id}/results', 'ReportController@results');
    Route::put('manager/reports/{id}', 'ReportController@update');
    Route::delete('manager/reports/{id}', 'ReportController@destroy');



});
