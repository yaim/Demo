<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function ($router) {
    $router->group(['middleware' => 'guest'], function ($router) {
        $router->post('register', 'UserController@store');
        $router->post('login', 'AuthController@store');
    });

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->get('user', 'UserController@get');
        $router->get('successful-attempts', 'SuccessfulAttemptController@index');
    });
});

Route::group(['namespace' => 'Auth'], function ($router) {
    $router->middleware('auth')->get('/auth/successful-attempts', 'SuccessfulAttemptController@index');
});
