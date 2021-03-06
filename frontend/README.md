# AntOA

### 一个基于 Vue AntD Admin 二次开发与 Laravel 结合的后台 OA 管理系统

Vue AntD Admin 项目地址：https://github.com/iczer/vue-antd-admin

## 使用

### 预配置：

在安装运行之前需要你去设置域名及模块相关配置：

#### 1.设置该文件夹下的.env 文件

VUE_APP_API_BASE_URL=你的域名（如http://www.baidu.com，注意末尾没有斜杠/）

#### 2.设置模块的 config.php 文件及初始化模块数据库 Seeder

详见根目录的 Readme.md

### 开发模式：

开发时需要将项目跨域，设置方法：
找到/public/index.php
在开头位置添加如下代码：

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

配置成功后运行如下代码（推荐使用yarn，速度更快）：

```
$ yarn
$ yarn serve
```

运行成功后浏览器访问http://localhost:8080/antoa/webpack/ 即可进行开发调试。

### 部署模式：

```
$ yarn
$ yarn build
```

运行成功后浏览器访问 你的域名/antoa/webpack/ 即可查看部署效果。

详细开发方式详见AntOA开发文档。
