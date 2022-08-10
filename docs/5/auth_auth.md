> 请求地址

```
/api/antoa/auth/auth
```

> 调用方式：

```
POST JSON
```

> 接口描述：

获取当前登录用户的基本个人信息，也用于判断用户令牌是否失效

> 请求参数:

* HEADER参数：

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| Authorization | Token请求头 | string | Y | 通过登录授权接口获取 | 

* GET参数:

无

* POST参数（JSON）:

无（需传空对象{}）

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"data": "11",
	"role": [
		"2"
	]
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
| data | 用户UID | string | status为1时 | 表antoa_user的主键 |
| role | 用户权限 | array\<string\> | status为1时 | 用户拥有的权限id数组 |
| msg | 请求失败原因 | string | status为0时 | - |

> CURL请求示例

```
curl --location --request POST 'https://similing.gitee.io/api/antoa/auth/auth' \
--header 'Authorization: 11_f42332f725ee9101a8b0ce39e55acdd1' \
--header 'Content-Type: application/json' \
--data '{}'
```