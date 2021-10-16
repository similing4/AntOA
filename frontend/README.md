# AntOA

### 一个基于 Vue AntD Admin 二次开发与 Laravel 结合的后台 OA 管理系统

Vue AntD Admin 项目地址：https://github.com/iczer/vue-antd-admin

## 使用

### 预配置：

在安装运行之前需要你去设置域名及模块相关配置：

#### 1.设置该文件夹下的.env 文件

VUE_APP_API_BASE_URL=你的域名（如http://www.baidu.com，注意末尾没有斜杠/）

#### 2.设置模块的 config.php 文件及初始化模块数据库 Seeder

详见模块的 Wiki 或模块的 Readme.md

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

### 你可以将菜单的路由JSON改为本地的JSON用于调试：

修改这个文件：src/pages/login/Login.vue

更改 goHome 方法为如下

```javascript
async goHome() {
    try {
        if (routerConfig.mode != 'dev') {
            var e = await this.$api('/api/antoa/auth/config').method('GET').call();
            if (!e.status) throw e.msg;
            loadRoutes(e.routes);
            for (var i in e.routes[0].children) {
            if (e.routes[0].children[i].meta && e.routes[0].children[i].meta.is_home) localStorage.homeVueApi = e.routes[0].children[i].meta.vue_api;
            }
            this.$router.push('/home');
        } else {
            var e = await getMenuConfig();
            console.log(e.routes);
            if (!e.status) throw e.msg;
            loadRoutes(e.routes);
            for (var i in e.routes[0].children) {
            if (e.routes[0].children[i].meta && e.routes[0].children[i].meta.is_home) localStorage.homeVueApi = e.routes[0].children[i].meta.vue_api;
            }
            this.$router.push('/home');
            console.log(this.$router);
        }
    } catch (e) {
        this.$message.error(e + '', 5);
    }
},
```

在生产环境下该方法为:

```javascript
async goHome() {
    try {
    var e = await this.$api('/api/antoa/auth/config').method('GET').call();
    if (!e.status) throw e.msg;
    loadRoutes(e.routes);
    for (var i in e.routes[0].children) {
    if (e.routes[0].children[i].meta && e.routes[0].children[i].meta.is_home) localStorage.homeVueApi = e.routes[0].children[i].meta.vue_api;
    }
    this.$router.push('/home');

    } catch (e) {
    this.$message.error(e + '', 5);
    }
},
```

配置成功后运行如下代码：

```
$ npm install
$ npm run serve
```

运行成功后浏览器访问http://localhost:8080/antoa/webpack/index 即可进行开发调试。

### 部署模式：

```
$ npm install
$ npm run build
```

运行成功后浏览器访问 你的域名/antoa/webpack/index 即可查看部署效果。

详细开发方式详见模块 Wiki
