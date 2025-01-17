<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $data = [
        'name' => config('app.name'),
        'version' => config('app.version'),
        'framework' => $router->app->version(),
        'environment' => config('app.env'),
        'debug_mode' => config('app.debug'),
        'timestamp' => Carbon::now()->toDateTimeString(),
        'timezone' => config('app.timezone'),
    ];

    return response()->json($data, Response::HTTP_OK);
});

$router->post('/auth', 'AuthController@store');
$router->group(['middleware' => 'auth:api', 'prefix' => 'auth'], function ($router) {
    $router->get('/', 'AuthController@show');
    $router->get('/reports', 'AuthController@showReports');
    $router->put('/', 'AuthController@update');
    $router->delete('/', 'AuthController@destroy');
});

$router->group(['middleware' => ['auth:api', 'role:admin'], 'prefix' => 'users'], function ($router) {
    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@store');
    $router->get('/{id:[0-9]+}', 'UserController@show');
    $router->put('/{id:[0-9]+}', 'UserController@update');
    $router->patch('/{id:[0-9]+}', 'UserController@update');
    $router->delete('/{id:[0-9]+}', 'UserController@destroy');
});

$router->group(['middleware' => ['auth:api','role:admin|pegawai'], 'prefix' => 'dashboard'], function ($router) {
    $router->get('/summary', 'DashboardController@index');
    $router->get('/reports/status/{status}', 'DashboardController@getReports');
//    $router->post('/', 'DashboardController@store');
//    $router->get('/{id:[0-9]+}', 'DashboardController@get');
//    $router->put('/{id:[0-9]+}', 'DashboardController@update');
//    $router->patch('/{id:[0-9]+}', 'DashboardController@update');
//    $router->delete('/{id:[0-9]+}', 'DashboardController@destroy');
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'reports'], function ($router) {
    $router->post('/', 'ReportController@store');
    $router->group(['middleware' => 'report-check'], function ($router){
        $router->get('/{id:[0-9]+}', 'ReportController@get');
        $router->post('/{id:[0-9]+}/file', 'ReportController@fileUpload');
        $router->put('/{id:[0-9]+}', 'ReportController@update');
        $router->patch('/{id:[0-9]+}', 'ReportController@update');
    });
    $router->group(['middleware' => ['role:admin|pegawai']], function($router){
        $router->get('/', 'ReportController@index');
        $router->get('/export', 'ReportController@export');
        $router->post('/import', 'ReportController@import');
        $router->delete('/{id:[0-9]+}', 'ReportController@destroy');
    });
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'report_categories'], function ($router) {
    $router->get('/', 'ReportCategoryController@index');
    $router->group(['middleware' => ['role:admin|pegawai']], function($router) {
        $router->post('/', 'ReportCategoryController@store');
        $router->get('/{id:[0-9]+}', 'ReportCategoryController@get');
        $router->put('/{id:[0-9]+}', 'ReportCategoryController@update');
        $router->patch('/{id:[0-9]+}', 'ReportCategoryController@update');
        $router->delete('/{id:[0-9]+}', 'ReportCategoryController@destroy');
    });
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'districts'], function ($router) {
    $router->get('/', 'DistrictController@index');
    $router->get('/{id:[0-9]+}', 'DistrictController@get');
    $router->get('/{id:[0-9]+}/villages', 'DistrictController@getVillages');
    $router->group(['middleware' => ['role:admin|pegawai']], function($router) {
        $router->post('/', 'DistrictController@store');
        $router->patch('/{id:[0-9]+}', 'DistrictController@update');
        $router->put('/{id:[0-9]+}', 'DistrictController@update');
        $router->delete('/{id:[0-9]+}', 'DistrictController@destroy');
    });
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'villages'], function ($router) {
    $router->get('/', 'VillageController@index');
    $router->get('/{id:[0-9]+}', 'VillageController@get');
    $router->group(['middleware' => ['role:admin|pegawai']], function($router) {
        $router->post('/', 'VillageController@store');
        $router->put('/{id:[0-9]+}', 'VillageController@update');
        $router->patch('/{id:[0-9]+}', 'VillageController@update');
        $router->delete('/{id:[0-9]+}', 'VillageController@destroy');
    });
});

$router->group(['middleware' => ['auth:api', 'role:admin|pegawai'], 'prefix' => 'roles'], function ($router) {
    $router->get('/', 'RoleController@index');
//    $router->post('/', 'RoleController@store');
//    $router->get('/{id:[0-9]+}', 'RoleController@get');
//    $router->put('/{id:[0-9]+}', 'RoleController@update');
//    $router->patch('/{id:[0-9]+}', 'RoleController@update');
//    $router->delete('/{id:[0-9]+}', 'RoleController@destroy');
});
