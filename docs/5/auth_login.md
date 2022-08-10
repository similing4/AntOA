> 请求地址

```
/api/antoa/auth/login
```

> 调用方式：

```
POST JSON
```

> 接口描述：

根据表antoa_user的用户的账户（username）密码（password）字段进行鉴权

> 请求参数:

* HEADER参数：

无

* GET参数:

无

* POST参数（JSON）:
| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| username | 用户名 | string | Y | - | 
| password | 密码 | string | Y | - |

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"data": "10_ae993a67043df32ed2de901c85140f37"
}
```

* 失败时

```json
{
	"status": 0,
	"msg": "用户名或密码错误"
}
```

> 请求返回结果参数说明:

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| status | 响应码 | integer | 一定存在 | 值为1时请求成功，否则请求失败 | 
| data | 用户令牌 | string | status为1时 | - |
| msg | 请求失败原因 | string | status为0时 | - |

> CURL请求示例

```
curl --location --request POST 'https://similing.gitee.io/api/antoa/auth/login' \
--header 'Content-Type: application/json' \
--data '{"username":"admin","password":"admin"}'
```