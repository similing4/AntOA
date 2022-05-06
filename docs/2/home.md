# 自定义后台首页
默认情况下，后台首页定义于AntOA/frontend/src/pages/home/Home.vue中。如果您想自定义首页，您可以在您的项目根目录建立AntOAHome.vue文件，例：
```
<template>
	<div class="new-page">
		这里是首页测试内容
	</div>
</template>
<script>
export default {
	data() {
		return {};
	},
}
</script>
<style scoped lang="less">
.new-page {
	background-color: @base-bg-color;
	border-radius: 4px;
	min-height: 100vh;
}
</style>
```

# 相关API调用
如果需要调用接口（需要使用到后台授权的），那么你可以在vue对应的方法中这样调用：
```
this.$api(你的接口URL).param(参数对象).method(请求方式).call();
```
例：
```
this.$api("/api/antoa/home/config").param(this.$route.query).method("POST").call();
//这里POST请求了/api/antoa/home/config接口，并将页面携带的url参数作为参数。该方法返回Promise对象。
```

# 组件库调用
该页面可以使用ant-design相关的所有组件，可用库参考AntOA/frontend/package.json。如果你希望使用额外的js库，可以在对应的vue文件目录处填写自己需要的package.json并install，AntOA进行编译时会自动将其引入。
