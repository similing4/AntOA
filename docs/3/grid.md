## AntOAController
AntOAController定义于AntOA/Http/Controller/AntOAController.php中，你需要的后台页面均应继承自AntOAController。一个简单的后台控制器结构如下：
```
class UserController extends AntOAController {
    public function __construct(AuthInterface $auth) { //这个构造方法不能省略
        parent::__construct($auth);
    }

    public function grid(Grid $grid) { //这里编写grid相关操作
    }

    public function statistic(Request $req) { //这里返回统计信息
        return "";
    }

    protected function checkPower($uid) { //检查是否满足访问该控制器的权限要求，如果用户未登录则不走该方法
        return true;
    }
}
?>
```
该类一共提供了九个接口：
```
Route::any($route . "/list", $controller . "@api_list"); //获取列表页的列表数据
Route::post($route . "/create", $controller . "@api_create"); //创建页进行创建操作的接口
Route::any($route . "/detail", $controller . "@api_detail"); //获取编辑页待编辑行数据的接口
Route::post($route . "/detail_column_list", $controller . "@api_detail_column_list"); //获取ColumnChildrenChoose功能的对应列表数据信息
Route::post($route . "/save", $controller . "@api_save"); //编辑页进行保存修改操作的接口
Route::any($route . "/delete", $controller . "@api_delete"); //列表页进行删除操作的接口
Route::post($route . "/column_change", $controller . "@api_column_change"); //待监听的字段值发生改变时调用的钩子接口
Route::post($route . "/grid_config", $controller . "@api_grid_config"); //获取后台配置的列表页创建页编辑页结构信息
Route::post($route . "/upload", $controller . "@uploadFile"); //上传文件到服务端接口
```
为了方便开发，AntOA集成了这些路由的注册方法。假设需要注册UserController：
```
RouteRegister::registerApi("/user", "UserController");
```
那么你就有了如下这些接口：
```
[
    "/api/user/list" => "UserController@api_list", //获取列表页的列表数据
    "/api/user/create" => "UserController@api_create", //创建页进行创建操作的接口
    "/api/user/detail", "UserController@api_detail", //获取编辑页待编辑行数据的接口
    "/api/user/detail_column_list","UserController@api_detail_column_list", //获取ColumnChildrenChoose功能的对应列表数据信息
    "/api/user/save", "UserController@api_save", //编辑页进行保存修改操作的接口
    "/api/user/delete", "UserController@api_delete", //列表页进行删除操作的接口
    "/api/user/column_change", "UserController@api_column_change", //待监听的字段值发生改变时调用的钩子接口
    "/api/user/grid_config", "UserController@api_grid_config", //获取后台配置的列表页创建页编辑页结构信息
    "/api/user/upload", "UserController@uploadFile" //上传文件到服务端接口
]
```
这些接口是否可用受Grid对象控制。当然，你可以自己实现这些接口来实现功能，具体接口内容规范请参考《五、API文档》。

## 页面配置与Grid
一个AntOA的List页面由如下部分组成：
![/admin/work/list](grid_1.jpg)

Create与Edit页面均由纯表单组成。其中控制器的statistic方法返回的值会被展示在“统计信息”位置，其余内容均受Grid配置影响。具体配置方法详见《后台列表页》、《后台创建页》及《后台编辑页》三章。

## Grid的钩子
Grid实例共计5个钩子方法，分别对应如下：

### public function hookList($func);
设置一个列表页数据结果的钩子，该方法可以拦截列表查询结果，你可以通过这个钩子对查询结果进行修改后返回。
#### 参数：
* $func 实现了 ListHook 接口的对象，你可以将钩子的回调内容定义于其抽象方法的实现中
#### 返回值：
返回this供链式调用

### public function hookCreate($func);
设置一个创建页进行创建时的钩子，该方法可以拦截用户提交的表单内容，你可以通过这个钩子自行处理插入逻辑、拦截创建或返回修改后的数据让AntOA继续创建逻辑。
#### 参数：
* $func 实现了 CreateHook 接口的对象，你可以将钩子的回调内容定义于其抽象方法的实现中
#### 返回值：
返回this供链式调用

### public function hookDetail($func);
设置一个编辑页查询当前编辑的项时的钩子，该方法可以拦截编辑页根据主键内容查询的结果，你可以通过这个钩子自行修改查询结果。
#### 参数：
* $func 实现了 DetailHook 接口的对象，你可以将钩子的回调内容定义于其抽象方法的实现中
#### 返回值：
返回this供链式调用

### public function hookSave($func);
设置一个编辑页进行修改时的钩子，该方法可以拦截编辑页用户提交的表单内容，你可以通过这个钩子自行处理修改逻辑、拦截修改或返回修改后的数据让AntOA继续创建逻辑。
#### 参数：
* $func 实现了 SaveHook 接口的对象，你可以将钩子的回调内容定义于其抽象方法的实现中
#### 返回值：
返回this供链式调用

### public function hookDelete($func);
设置一个列表页进行删除时的钩子，该方法可以拦截列表页用户删除的主键信息，你可以通过这个钩子自行处理删除逻辑、拦截删除或返回修改后的数据让AntOA继续创建逻辑。
#### 参数：
* $func 实现了 DeleteHook 接口的对象，你可以将钩子的回调内容定义于其抽象方法的实现中
#### 返回值：
返回this供链式调用