# 自定义后台登录页
默认情况下，后台首页定义于AntOA/frontend/src/pages/login/Login.vue中。如果您想自定义登录页，您可以在您的项目根目录建立AntOALogin.vue文件，详情请见AntOA/frontend/src/pages/login/Login.vue。

# 登录流程

1.进入页面时您需要POST请求"/api/antoa/auth/auth"接口，以验证是否已经鉴权过，如果响应值status为1则可以直接跳转到你指定的任意后台页面。

2.使用下列方法对用户名密码进行登录鉴权，该鉴权接口status为1时，data字段为用户令牌，需要使用setLoginToken方法存储。

```
const res = await this.$api("/api/antoa/auth/login")
	.param({
		username: this.username,
		password: this.password
	})
	.method("POST")
	.call();
if(res.status == 1)
	this.$api.setLoginToken(res.data);
```

# 组件库调用
该页面可以使用ant-design相关的所有组件，可用库参考AntOA/frontend/package.json。如果你希望使用额外的js库，可以在对应的vue文件目录处填写自己需要的package.json并install，AntOA进行编译时会自动将其引入。