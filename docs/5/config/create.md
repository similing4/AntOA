> 请求地址

```
/api{$配置路由}/create
```

> 调用方式：

```
POST JSON
```

> 接口描述：

根据当前配置路由对应的创建页结构创建数据

> 请求参数:

* HEADER参数：

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| Authorization | Token请求头 | string | Y | 通过登录授权接口获取 | 

* GET参数:

无

* POST参数（JSON）:

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| ... | 表单项 | - | Y | 根据配置的column系列方法变化而变化，具体应为《获取配置路由的配置信息》接口的grid属性的create属性的createColumnCollection响应字段每一项的col属性 |

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"data": "创建成功"
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
| data | 请求成功提示 | string | status为1时 | - |
| msg | 请求失败原因 | string | status为0时 | - |

> CURL请求示例

* 假设配置路由为/admin/user，表单项只有name字段，则curl请求示例如下：

```
curl --location --request POST 'https://similing.gitee.io/api/admin/user/create' \
--header 'Authorization: 11_f42332f725ee9101a8b0ce39e55acdd1' \
--header 'Content-Type: application/json' \
--data '{"name":""}'
```