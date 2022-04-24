# AntOA使用教程

## 一、使用前准备
### 有关云存储
现只支持七牛云上传，服务端上传功能实现中。。。
### 有关自定义页面
自定义页面功能已完成，详见 四、自定义页面及插件
### 有关插件本身的可扩展性
自定义插件功能已完成，详见 四、自定义页面及插件
### 有关默认值相关功能
正在编写中。。。

## 二、框架的使用
### 1.安装部署
#### laravel-modules
由于项目基于laravel-modules进行开发，因此您需要安装laravel-modules以支持本框架。laravel-modules安装方法：
https://nwidart.com/laravel-modules/v6/installation-and-setup
#### nodejs
由于项目后台页面需要使用npm或yarn打包，故而您开发或部署时需要安装nodejs且保证npm能正常运行。
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

安装结束后需要使用npm或yarn安装并打包页面到public下，否则无法访问页面：

使用npm：
```shell script
$ cd Modules/AntOA/frontend
$ npm install
$ npm run build
```
使用yarn：
```shell script
$ cd Modules/AntOA/frontend
$ yarn
$ yarn build
```
注意：每次新增带有AntOA模块的插件时（和页面相关的扩展功能）需要重新打包，即重新执行上述脚本。

### 2.修改配置
#### config.php
修改/config/antoa.php文件以实现后台的菜单及页面配置，详见本项目的Config/config.php.example文件。
如果你使用了包含文件上传的功能如上传文件、上传图片、富文本Wangeditor的上传图片等，请在配置文件中设置七牛云信息。
#### Seeder
模块的授权依赖于数据库中的管理员用户，因此需要创建超级管理员表：
```shell script
$ php artisan module:seed AntOA
```
#### 有关前端独立开发
我们不推荐您这样进行开发，因为这样会导致AntOA失去原子性且可能发生不能应用其它扩展的问题。我们推荐您使用 四、自定义页面及插件 的方式开发AntOA的扩展以实现自定义功能。
如果您执意要独立开发，您可以修改前端项目的域名配置文件：Modules/AntOA/frontend/.env，修改VUE_APP_API_BASE_URL的值为你的域名（如http://www.baidu.com，注意末尾没有斜杠/）开发时需要将后台项目跨域。

跨域设置方法：找到/public/index.php，在开头位置添加如下代码：
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
只创建控制器没有对应的后台路由，其对应的接口是不能访问的。因此您需要在对应控制器所在模块中配置所需的路由：
如修改/Modules/SoftwareManager/Routes/api.php（web.php不需要修改）：
```php
use Illuminate\Support\Facades\Route;
use Modules\AntOA\Http\Utils\RouteRegister;

Route::prefix('software')->group(function() { //这里的software要与web中设置的一致，自己定义接口就不做例子了
    RouteRegister::registerApi('/home', 'SoftwareManagerController');
});
//此范例注册了/api/software/home/list、/api/software/home/create、/api/software/home/detail、/api/software/home/edit等路由，即SoftwareManagerController控制器所需要的框架相关的前端接口。
```
然后打开你的域名/antoa/webpack/index.html看看效果吧~

## 四、自定义页面及插件
自定义插件主要由路由管理和插件两部分组成。不论是哪部分，都需要您去新建模块来实现。

### 自定义路由
路由管理用于管理路由与自定义页面，路由守卫等。

如果你需要自定义路由，那么你需要在你的模块所在根目录下创建一个antoa_plugin.js文件，内容如下：
```js
const install = (Vue) => {
    
};

const routes = [{
    path: '/software/test/diy_list',
    name: '测试列表页',
    component: () => import('./test.vue')
}];

const dealRouter = (router)=>{
    
};
export default {
  install,
  routes,
  dealRouter
};
```
这里的install会被main.js通过Vue.use调用。routes配置请参考我的写法进行引用