<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Auth API's
 * @return json
 */
Route::post('login','App\Http\Controllers\Api\V1\LoginController@login')->name('login');
Route::get('login','App\Http\Controllers\Api\V1\LoginController@login')->name('login');

$router->group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () use ($router) {

    /**
     * Student API's
     * @return json
     */
    $router->get('list-students','App\Http\Controllers\Api\V1\StudentController@list');
    $router->get('get-student','App\Http\Controllers\Api\V1\StudentController@get');
    $router->post('add-student','App\Http\Controllers\Api\V1\StudentController@create');
    $router->patch('edit-student','App\Http\Controllers\Api\V1\StudentController@edit');
    $router->delete('delete-student','App\Http\Controllers\Api\V1\StudentController@delete');
    $router->get('get-token','App\Http\Controllers\Api\V1\StudentController@getToken');

    $router->get('logout','App\Http\Controllers\Api\V1\LoginController@logout');
});
