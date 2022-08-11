## 安装

本项目是基于[nwidart/laravel-modules](https://github.com/nWidart/laravel-modules)进行的开发，故而您需要[安装并创建laravel6.0](https://learnku.com/docs/laravel/6.x/installation/5124)并在其中使用composer引入该库：

```bash
composer require nwidart/laravel-modules 7.3
```

为了能够轻松合并composer，需要安装wikimedia/composer-merge-plugin库：

```bash
composer require wikimedia/composer-merge-plugin
```

安装好laravel-modules模块后您需要使用命令行创建AntOA模块：

```bash
php artisan module:make AntOA
```

创建之后你需要修改项目根目录下的composer.json，修改autoload节点下的psr-4节点如下：

```json
{
    "App\\": "app/",
    "Modules\\": "Modules/"
}
```

修改项目根目录下的composer.json，修改extra节点如下：

```
{
    "laravel": {
        "dont-discover": []
    },
    "merge-plugin": {
        "include": [
            "Modules/*/composer.json"
        ],
        "recurse": true,
        "replace": false,
        "merge-dev": true,
        "merge-extra": false
    }
}
```

然后你需要在项目根目录执行如下命令更新Package：

```php
composer dump-autoload
```

然后你可以直接使用命令行创建注册本模块后覆盖

Windows下：

```bash
cd Modules
rmdir AntOA /S /Q
git clone https://github.com/similing4/AntOA.git
```

linux下：

```bash
cd Modules
rm AntOA -rf
git clone https://github.com/similing4/AntOA.git
```

安装结束后需要使用npm或yarn安装并打包页面到public下，否则无法访问页面：

使用npm：
```shell script
cd Modules/AntOA/frontend
npm install
npm run build
```
使用yarn：
```shell script
cd Modules/AntOA/frontend
yarn
yarn build
```
注意：每次新增带有AntOA模块的插件时（和页面相关的扩展功能）需要重新打包，即重新执行上述脚本。

如果你更新了子包的composer配置，那么你需要在项目根目录使用脚本更新使其生效：

```
composer update
```

## 初始配置及修改配置
### config.php

修改（没有就创建） 你的项目/config/antoa.php 文件以实现后台的菜单及页面配置，参考本项目的Config/config.php.example文件。
如果你使用了包含文件上传的功能如上传文件、上传图片、富文本Wangeditor的上传图片等，请在配置文件中设置七牛云信息。

### Seeder

模块的授权依赖于数据库中的管理员用户，因此需要创建超级管理员表。可通过以下脚本创建（创建前请修改好主项目的数据库配置）：
```shell script
php artisan module:seed AntOA
```

访问 http://你的域名/antoa/webpack/index.html 看看吧~