<?php
/**
 * FileName:RouteRegister.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/5
 * Time:14:56
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;

use Illuminate\Support\Facades\Route;


class RouteRegister {
    public static function register($route, $controller) {
        if(in_array("html",config("antoa.mode"))) {
            Route::get($route . "/list", $controller . "@list");
            Route::get($route . "/create", $controller . "@create");
            Route::get($route . "/edit", $controller . "@edit");
        }
    }

    public static function registerApi($route, $controller) {
        Route::any($route . "/list", $controller . "@api_list");
        Route::post($route . "/create", $controller . "@api_create");
        Route::any($route . "/detail", $controller . "@api_detail");
        Route::post($route . "/detail_column_list", $controller . "@api_detail_column_list");
        Route::post($route . "/save", $controller . "@api_save");
        Route::get($route . "/delete", $controller . "@api_delete");
        Route::post($route . "/column_change", $controller . "@api_column_change");
        Route::post($route . "/grid_config", $controller . "@api_grid_config");
    }
}
