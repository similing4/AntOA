## 路由
路由主要分为两部分，一部分为《前端页面路由》，另一部分为《后端接口路由》。

### 后端接口路由
后端接口路由分为框架自身路由、控制器相关路由及自定义接口路由三部分。

#### 框架自身路由
框架自身路由定义在AntOA/Routes/api.php中，包括如下几个接口：

```php
Route::prefix('antoa')->group(function() {
    Route::post('/auth/login', 'AuthController@login'); //登录获取授权信息接口
    Route::post('/auth/auth', 'AuthController@auth'); //登录页面用于判断是否已经授权登录的接口
    Route::get('/auth/config', 'AuthController@api_config'); //获取路由配置、七牛云配置等信息的接口
    Route::post("/user/change_password","AntOAUserController@changePassword"); //修改当前登录用户的密码的接口
});
```

接口详细信息请参考API文档

#### 控制器相关路由
这里的控制器相关路由特指继承自\Modules\AntOA\Http\Controllers\AntOAController类的控制器。
如果定义了这种控制器，你需要在你对应模块的Routes/api.php中注册该控制器对应的接口路由，定义方式：
```php
	//假设你定义的路由路径是home，控制器名为HomeController，那么你应该这么定义：
	RouteRegister::registerApi('/home', 'HomeController');
	//此时你访问 /antoa/webpack/#/race/home/list 即可访问该控制器对应路由
```

这里只是做了简单介绍，详见 三、Grid的使用->控制器与Grid。

#### 自定义接口路由
定义在模块内自己定义的路由内容。对应路由指向方法只要是定义在继承自\Modules\AntOA\Http\Controllers\AntOAController类的控制器中，就可以享有后台授权鉴权功能。详情请参考API文档。

### 前端接口路由
前端接口路由主要由全局的配置文件antoa.php配置。antoa配置文件如下：
```
<?php
//这个函数用于鉴权，正常情况下配置文件的每项的role_limit字段应当返回一个参数为$user（antoa_user表行数据，用于标识已授权的用户）的回调函数用于鉴别用户权限是否符合，如果符合应返回true，否则返回false。
//这里根据传入的角色名进行鉴权。你也可以自定义鉴权方法。
$roleFunc = function ($role) {
    return function ($user) use ($role) {
        return in_array($role, json_decode($user['role'], true));
    };
};
return [
	//name字段为固定内容，不要动
    'name'        => 'AntOA',
    //菜单路由配置，path为完整URL链接，name为在菜单上显示的内容，breadcrumbname为面包屑上显示的标题，不设置默认为name
    'menu_routes' => [
        //前端页面的路由，html模式下为页面绝对地址，vue模式下为路由path地址
        [
            "name"   => "首页", //页面名称，将显示在侧边栏及标签页上，如果不设置breadcrumbname也将设置为面包屑。
            "isHome" => true, //使用ant-design-vue-admin时此指定此页面组为首页/首页，设置的children将被无视，如需设置首页内容需要自定义vue项目的home.vue文件，只能设置在第一层且只能设置一个。
        ],
        [
            "name"       => "用户管理",
            "role_limit" => $roleFunc(1), //权限限制，不设置为不限制
            "children"   => [
                [
                    "path"       => "/admin/user/list",
                    "name"       => "用户列表",
                    "role_limit" => $roleFunc(1)
                ],
                [
                    "visible"    => false, //设置左侧导航栏中不显示该页面
                    "path"       => "/admin/user/edit",
                    "name"       => "用户编辑",
                    "role_limit" => $roleFunc(1)
                ],
            ]
        ]
    ],
    //七牛云的配置，如果你使用了七牛云文件上传那么该项必填
    'config'      => [
        'qiniu' => [
            'access_key' => '',  //七牛云的AccessKey，可以在用户头像下的秘钥管理处获取
            'secret_key' => '',  //七牛云的SecretKey，同上
            'bucket'     => '',  //Bucket名称，七牛云的对象存储-空间管理中的空间名称即为Bucket名称
            'url'        => '' //访问域名，如https://qn.github.com/，注意要带末尾的斜杠。可以在七牛云的对象存储-空间管理中的域名中绑定，http或https由空间配置决定，请尽量不要用测试域名避免因过期而造成困扰。
        ]
    ]
];
?>
```

上述配置文件会被AntOA解析并通过/auth/config接口传给前端用于渲染页面、侧边栏及组件等。

## 页面

页面分为控制器配置页面和自定义页面两种。

### 自定义页面

详见 四、功能扩展->扩展页面

### 控制器配置页面

通过RouteRegister::registerApi方法定义的且在antoa.php中配置的页面即可访问。控制器配置的页面主要包括：

1.list页面

list页面为后台管理的列表页，可通过Grid对应的方法来配置页面展示信息与交互。

2.create页面

create页面为后台管理的创建页，可通过Grid对应的方法来配置页面展示信息与交互。

3.edit页面

edit页面为后台管理的编辑页，可通过Grid对应的方法来配置页面展示信息与交互。

它们对应的接口有：

```php
	//注：这里对应的接口前有/api/前缀。
    public static function registerApi($route, $controller) {
	    Route::any($route . "/list", $controller . "@api_list"); //获取列表页的列表数据
	    Route::post($route . "/create", $controller . "@api_create"); //创建页进行创建操作的接口
	    Route::any($route . "/detail", $controller . "@api_detail"); //获取编辑页待编辑行数据的接口
	    Route::post($route . "/detail_column_list", $controller . "@api_detail_column_list"); //获取ColumnChildrenChoose功能的对应列表数据信息
	    Route::post($route . "/save", $controller . "@api_save"); //编辑页进行保存修改操作的接口
	    Route::any($route . "/delete", $controller . "@api_delete"); //列表页进行删除操作的接口
	    Route::post($route . "/column_change", $controller . "@api_column_change"); //待监听的字段值发生改变时调用的钩子接口
	    Route::post($route . "/grid_config", $controller . "@api_grid_config"); //获取后台配置的列表页创建页编辑页结构信息
        Route::post($route . "/upload", $controller . "@uploadFile"); //上传文件到服务端接口
    }
```

对应的接口文档详见API文档。