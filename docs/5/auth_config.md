> 请求地址

```
/api/antoa/auth/config
```

> 调用方式：

```
GET
```

> 接口描述：

获取如页面路由相关信息、七牛云上传所需配置信息等全局配置信息

> 请求参数:

* HEADER参数：

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| Authorization | Token请求头 | string | Y | 通过登录授权接口获取 | 

* GET参数:

无

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"host": "",
	"token": "XXX:XXX:XXX",
	"routes": [{
		"name": "用户管理",
		"children": [{
			"path": "/user/normal_user/list",
			"name": "普通用户管理"
		}]
	}],
	"title_map": [{
		"path": "/user/normal_user/list",
		"name": "普通用户管理"
	}],
	"home_page": null
}
```

* 失败时

```json
{
	"status": 0,
	"msg": "登录失效"
}
```

> 请求返回结果参数说明:

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| status | 响应码 | integer | 一定存在 | 值为1时请求成功，否则请求失败 | 
| host | 七牛云文件链接Host | string | status为1时 | 在antoa.php中配置 |
| token | 七牛云上传文件凭证 | string | status为1时 | 只有在设置了七牛云相关配置后才有效 |
| home_page | 登录后跳转的首页地址 | string | status为1时 | 在antoa.php中配置，如未配置该字段则登录后跳转到home页面 |
| routes | 前台路由 | array\<Route\> | status为1时 | 详见Route解释 |
| title_map | 页面对应标题 | array\<RouteTitle\> | status为1时 | 详见RouteTitle解释 |
| msg | 请求失败原因 | string | status为0时 | - |

* Route

* RouteTitle

> CURL请求示例

```
curl --location --request GET 'https://similing.gitee.io/api/antoa/auth/config' \
--header 'Authorization: 11_f42332f725ee9101a8b0ce39e55acdd1'
```