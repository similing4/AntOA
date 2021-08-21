# AntOA
 一个基于Vue AntD Admin二次开发与 Laravel 结合的后台OA管理系统

## 一、使用前准备
### 1.确定开发模式
本项目有两种开发模式：html模式与vue模式，二者在页面开发与展示效果上有些许不同。
#### html模式介绍
html模式下所有页面都是写在对应的.blade.php中的，系统自带的所有Vue组件都是经过处理写在js文件中的，在开发过程中可以自行编写html页面而无需刻意使用Vue来实现功能。本开发模式适合对Vue不是很熟悉的后端开发者编写页面。
由于html模式不能直接使用Vue组件，因此我对Vue-AntD-Admin中的组件进行了特殊处理，登录及后台页面进行了重写。
#### vue模式介绍
vue模式下页面是经过修改的Vue AntD Admin项目，采用单入口模式，需要使用npm手动打包，使用前端vue2的开发模式进行开发。vue模式并非基于laravel的webpack.mix.js因此如果你希望获取后端配置请通过接口方式获取。
经过修改，用npm run build部署会自动将页面部署到路由已经配置好的页面上。
### 2.是否适用
#### 有关云存储
如果您希望使用本框架，那么您最好有自己的七牛云账号及自己的七牛云域名绑定，本框架暂时没有提供直接上传到后台的方法，暂时只有由前端直接上传到七牛云的功能。后续我会新增配置功能，但无论何时我都不推荐直接上传文件到服务器！
#### 有关自定义页面
因有两种开发模式，针对不同模式的自定义页面有所不同。vue模式下使用的是动态路由注册的页面，因此对命名有一定的要求。html模式下使用的是web路由，因此自定义时需要自己创建页面路由。
#### 有关插件本身的可扩展性
框架本身受开发方式的局限性可能不是很适合做框架本身的插件开发，后续会完善针对框架的插件开发能力。

## 二、框架的使用
### 1.安装部署
#### laravel-modules
由于项目基于laravel-modules进行开发，因此您需要安装laravel-modules以支持本框架。laravel-modules安装方法：
https://nwidart.com/laravel-modules/v6/installation-and-setup
#### nodejs
如果您想使用vue开发模式进行开发，那么您需要安装对应版本的nodejs且保证npm能正常运行。
#### 创建AntOA模块并克隆覆盖
安装好laravel-modules模块后您可以直接使用命令行创建注册本模块后覆盖：

Windows下：
```shell script
$ php artisan module:make AntOA 
$ cd Modules
$ rmdir AntOA /S /Q
$ git clone https://github.com/similing4/AntOA.git
```
linux下：
```shell script
$ php artisan module:make AntOA 
$ cd Modules
$ rm AntOA -rf
$ git clone https://github.com/similing4/AntOA.git
```
### 2.修改配置
#### config.php
修改/Modules/AntOA/Config/config.php文件：
```php
return [
    'name'        => 'AntOA',
    'mode'        => ['vue', 'html'], //页面模式，可选项为vue与html，默认自带后台接口，如果设置vue则开启Ant Design Vue Admin页面开发，如果设置html则开启普通blade页面开发。无论该参数是否设置，开发的接口都会被开放。
    //菜单路由配置，uri为完整URL链接，title为在菜单上显示的内容，breadcrumbTitle为面包屑上显示的标题，不设置默认为title
    'menu_routes' => [
        //详见开始开发章节
        [
            "title"    => "首页",
            "isHome"   => true,
            "children" => [
                [
                    "uri"   => "/software/home/home",
                    "title" => "首页"
                ]
            ]
        ], [
            "title"    => "软件管理",
            "children" => [
                [
                    "uri"             => "/software/test/diy_list",
                    "title"           => "自定义页面",
                    "breadcrumbTitle" => "自定义列表页"
                ],
                [
                    "uri"             => "/software/home/list",
                    "title"           => "软件管理",
                    "breadcrumbTitle" => "软件管理列表页"
                ],
                [
                    "visible" => false,
                    "uri"     => "/software/home/create",
                    "title"   => "软件创建页"
                ],
                [
                    "visible" => false,
                    "uri"     => "/software/home/edit",
                    "title"   => "软件编辑页"
                ]
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
```
如果你使用了包含文件上传的功能比如上传文件，上传图片，富文本Wangeditor的上传图片等，请设置七牛云信息。
#### Seeder
模块的授权依赖于数据库中的管理员用户，因此需要创建超级管理员表。后续我计划将授权功能完全搬运到Service中以便二次开发。
```shell script
$ php artisan module:seed AntOA
```
#### ant-design-vue-admin
如果您使用vue模式，那么您需要配置vue的域名配置。编辑Modules/AntOA/frontend/.env文件修改VUE_APP_API_BASE_URL的值为你的域名（如http://www.baidu.com，注意末尾没有斜杠/）开发时需要将项目跨域。

设置方法：找到/public/index.php，在开头位置添加如下代码：
```php
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header('Access-Control-Allow-Origin:*');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Authorization, Content-Type, Accept, x-access-token, X-CSRF-TOKEN, token");
    header('Access-Control-Allow-Credentials:true');
    header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
    header('Access-Control-Max-Age:1728000');
    header('Content-Type:text/plain charset=UTF-8');
    header('Content-Length: 0', true);
    header('status: 200');
    header('HTTP/1.0 204 No Content');
    exit;
}else{
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
    header('Access-Control-Allow-Credentials:true');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Authorization, Content-Type, Accept, x-access-token, X-CSRF-TOKEN, token");
}
```
### 3.开始开发
这里我推荐在新的Module中进行开发以便项目分包处理。
```shell script
$ php artisan module:make SoftwareManager
```
其中SoftwareManager为模块名，可以随意起名，这里以SoftwareManager作为范例。
#### 控制器
你需要创建一个继承自AntOAController类的控制器Modules\AntOA\Http\Controllers\AntOAController并实现其方法，例如：
```php
namespace Modules\SoftwareManager\Http\Controllers;

use Illuminate\Http\Request;
use Modules\AntOA\Http\Controllers\AntOAController;
use Modules\AntOA\Http\Utils\AuthInterface;
use Modules\AntOA\Http\Utils\Grid;

class SoftwareManagerController extends AntOAController {
    public function __construct(AuthInterface $auth) {
        parent::__construct($auth);
    }
    /**
     * 初始化Grid对象
     * @param Grid $grid
     */
    public function grid(Grid $grid) {
        ;//自己实现
    }
    /**
     * 处理统计数据
     * @param Request $req 客户端请求参数
     * @return string 统计结果
     */
    public function statistic(Request $req) {
        return "";
    }
    //其他自定义方法页面等
}
```
其中Grid方法就是我们要编写的列表页、编辑页、创建页的内容控制位置。具体编写方法请看：[Grid的使用](./Grid.README.md)
#### 路由
首先需要在你的模块中配置你模块所需的路由：

需要修改/Modules/SoftwareManager/Routes/web.php：
```php
use Illuminate\Support\Facades\Route;
use Modules\AntOA\Http\Utils\RouteRegister;

Route::prefix('software')->group(function() { //这里的software为自定义的访问页面路径
    RouteRegister::register('/home', 'SoftwareManagerController'); //web模式下的列表、详情、创建页
    Route::get('/home/home', 'SoftwareManagerController@home'); //你控制器自己的页面，如果你不需要自定义页面那么可以不设置
});
//此范例注册了/software/home/list、/software/home/create、/software/home/list/software/home/edit路由
```
需要修改/Modules/SoftwareManager/Routes/api.php：
```php
use Illuminate\Support\Facades\Route;
use Modules\AntOA\Http\Utils\RouteRegister;

Route::prefix('software')->group(function() { //这里的software要与web中设置的一致，自己定义接口就不做例子了
    RouteRegister::registerApi('/home', 'SoftwareManagerController');
});
//此范例注册了/api/software/home/list、/api/software/home/create、/api/software/home/detail、/api/software/home/edit路由
```
其次要设置页面左侧导航的路由
/Modules/AntOA/Config/config.php中参照原有配置编写新页面的导航：
```php
//如果您使用vue模式，那么至少有一个一级导航栏设置isHome为true。
//如果您自定义页面，那么
return [
    'menu_routes' => [
        //一级导航
        //前端页面的路由，html模式下为页面绝对地址(注意，一定要是在web.php中注册过的路由路径)，vue模式下为路由path地址
        [
            "title"    => "首页",
            //title为页面名称，将显示在左侧导航栏及标签页上，如果不设置breadcrumbTitle也将设置为面包屑。
            "isHome"   => true,
            //isHome用于vue模式，指定此页面组为首页/首页，设置的children将被无视，如需设置首页内容需要自定义vue项目的home.vue文件，只能设置在第一层且只能设置一个。
            "children" => [
                //二级导航
                [
                    "uri"   => "/software/home/home",
                    "title" => "首页"
                ]
            ]
        ], [
            "title"    => "软件管理",
            "children" => [
                [
                    "uri"             => "/software/test/diy_list",
                    "title"           => "自定义页面",
                    "breadcrumbTitle" => "自定义列表页" //自定义面包屑显示内容
                ],
                [
                    "uri"             => "/software/home/list",
                    "title"           => "软件管理",
                    "breadcrumbTitle" => "软件管理列表页"
                ],
                [
                    "visible" => false, //设置左侧导航栏中不显示该页面
                    "uri"     => "/software/home/create",
                    "title"   => "软件创建页"
                ],
                [
                    "visible" => false,
                    "uri"     => "/software/home/edit",
                    "title"   => "软件编辑页"
                ]
            ]
        ]
    ]
    /*
        此路由配置后侧边导航栏为：
        首页
        └──首页
        软件管理
        ├──自定义页面
        └──软件管理
    */
];
```
### 4.调试与部署
