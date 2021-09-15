<?php

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

use Illuminate\Support\Facades\Route;
use Modules\AntOA\Http\Utils\RouteRegister;

Route::prefix('antoa')->group(function () {
    $callback = function ($base_dir) {
        return [function ($file) use ($base_dir) {
            $path = __DIR__ . "/../Resources/assets/antd" . $base_dir . $file;
            if (strstr($file, '..') !== false)
                return response()->json([], 404);
            if (is_file($path)) {
                $mime = [
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'css' => 'text/css',
                    'js'  => 'text/javascript'
                ];
                $ext = substr($file, strrpos($file, ".") + 1);
                if (!array_key_exists($ext, $mime))
                    return response()->file($path);
                return response()->file($path, [
                    "Content-Type" => $mime[$ext]
                ]);
            }
            return response()->json([], 404);
        }];
    };
    $config = include(__DIR__ . "/../Config/config.php");
    if (in_array("html", $config["mode"])) {
        Route::get('/auth/logout', 'AuthController@page_logout'); //web模式下的注销页面
        Route::get('/auth/login', 'AuthController@page_login'); //web模式下的登录页面
        Route::get('/assets/{file}', $callback('/')); //web模式下的前台资源
        Route::get('/assets/components/{file}', $callback('/components/')); //web模式下的组件资源
        Route::get("/antoa/user/change_password", "AntOAUserController@changePasswordPage");
    }
    if (in_array("vue", $config["mode"])) {
        Route::get('/webpack/static/css/{file}', $callback('/../webpack/static/css/')); //vue模式下的资源css目录
        Route::get('/webpack/static/js/{file}', $callback('/../webpack/static/js/')); //vue模式下的资源js目录
        Route::get('/webpack/static/img/{file}', $callback('/../webpack/static/img/')); //vue模式下的资源img目录
        Route::get('/webpack/index', 'AntOAWebpackController@index'); //vue模式下部署后的首页
    }
});
