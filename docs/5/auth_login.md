# 请求类型
POST JSON
# 请求地址
```
/api/antoa/auth/login
```
# 接口说明
根据表antoa_user的用户的账户（username）密码（password）字段进行鉴权
# 参数
## username
用户名
## password
密码
# 返回值
返回值为JSON格式
## 成功
成功时status字段为1，data字段为用户授权token，该token需要在后续Authorization头中携带用以鉴权。
## 失败
失败时status字段为0，msg字段为错误信息
# CURL示例
```
curl --location --request POST 'https://similing.gitee.io/api/antoa/auth/login' \
--header 'Content-Type: application/json' \
--data '{"username":"admin","password":"admin"}'
```