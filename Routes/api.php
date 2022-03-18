<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\AntOA\Http\Utils\RouteRegister;

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
/**
 * web模式及API模式下的通用接口
 */
Route::prefix('antoa')->group(function() {
    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/auth', 'AuthController@auth');
    Route::get('/auth/config', 'AuthController@api_config');
    Route::post("/user/change_password","AntOAUserController@changePassword");
});
