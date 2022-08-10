* RouteRegister类定义如下:

```php
namespace Modules\AntOA\Http\Utils;
use Illuminate\Support\Facades\Route;
class RouteRegister {
    public static function registerApi($route, $controller) {
        Route::any($route . "/list", $controller . "@api_list");
        Route::post($route . "/create", $controller . "@api_create");
        Route::any($route . "/detail", $controller . "@api_detail");
        Route::post($route . "/detail_column_list", $controller . "@api_detail_column_list");
        Route::post($route . "/save", $controller . "@api_save");
        Route::any($route . "/delete", $controller . "@api_delete");
        Route::post($route . "/column_change", $controller . "@api_column_change");
        Route::post($route . "/grid_config", $controller . "@api_grid_config");
        Route::post($route . "/upload", $controller . "@uploadFile");
    }
}
```

后端开发管理功能时需要使用该类的registerApi静态方法对对应的继承自AntOAController的控制器注册路由。比如定义了一个AdminUserController继承自AntOAController，那么在路由文件api.php中需要这样定义：

```php
RouteRegister::registerApi("/admin/user","AdminUserController");
```

其中"/admin/user"，即RouteRegister::registerApi的第一个参数我们暂且称之为配置路由，每进行如此配置一次，就对应注册了9个其相关接口。相关描述可以参考《AntOAController与Grid》章节介绍。

对应配置路由的页面（列表、创建、编辑）在加载过程中会通过当前页面的路由地址获取其配置路由信息，以用于获取其所有相关接口信息。