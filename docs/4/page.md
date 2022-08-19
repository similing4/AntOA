扩展页面与全局插件均可通过模块根目录的antoa_plugin.js文件配置。
## 文件组成
假设你的模块名为AntOAPlugins，那么如果你想创建自定义插件，那么你需要创建AntOAPlugins/antoa_plugin.js文件。一个基本的antoa_plugin.js文件格式如下：
```
export default {
  install(Vue){
    ;
  },
  routes:[],
  base_routes:[],
  dealRouter(router){
  	;
  }
};
```
## 自定义后台子页面
如果你需要自定义后台子页面（登录后的小页面），那么你需要将你自定义的页面放到routes数组中。例如我们自定义了一个test.vue页面，需要通过/test路径访问，那么我们需要如下定义：
```
export default {
  install(Vue){
  },
  routes:[{
    path: '/test',
    name: '测试页',
    component: () => import('./test.vue')
  }]
};
```
这里的component字段就是你的页面了。name为标签页上显示的内容，path为你的页面访问地址。编写之后需要重新进入到AntOA/frontend文件夹使用yarn build编译。前端需要强制刷新才能看到效果。

## 自定义一整个页面
如果你需要自定义整个页面（类似登录页这种页面），那么你需要将你自定义的页面放到base_routes数组中。定义方式同自定义后台子页面。编写之后同样需要重新进入到AntOA/frontend文件夹使用yarn build编译。前端需要强制刷新才能看到效果。

**注意！在定义页面结束后你需要在全局的antoa.php页面配置文件中配置menu_routes，避免你在访问页面时不能正确展开侧边栏~**

## 自定义全局插件
如果你需要实现某些功能，比如把某些类实例挂载到Vue实例上，那么你可以在install方法中实现之。项目会直接调用Vue.use来加载你的插件。比如项目自带的API请求插件：
```
import axios from "axios";
const serverURL = process.env.VUE_APP_API_BASE_URL;

class DataModel {
	_method = "POST";
	_param = {};
	isBlob = false;
	_url = null;

	constructor(url) {
		this._url = url;
	}

	param(param) {
		Object.assign(this._param, param);
		return this;
	}

	method(method) {
		this._method = method.toUpperCase();
		return this;
	}

	setBlob(bool) {
		this.isBlob = bool;
		return this;
	}

	_getUrlParam() {
		const itemArr = Object.keys(this._param).map((key) => {
			return key + "=" + this._param[key];
		});
		return itemArr.join("&");
	}

	checkAuthorization() {
		return !!localStorage.AuthToken;
	}

	async call() {
		const headers = {};
		if (localStorage.AuthToken)
			headers.Authorization = localStorage.AuthToken;
		let res;
		const config = { headers: headers };
		if (this.isBlob)
			config.responseType = 'blob';
		if (this._method === "POST")
			res = await axios.post(this._url, this._param, config);
		if (this._method === "GET") {
			if (this._url.includes("?"))
				res = await axios.get(this._url + "&" + this._getUrlParam(), config);
			else
				res = await axios.get(this._url + "?" + this._getUrlParam(), config);
		}
		return res.data;
	}
	async upload(file){
		const headers = {
			'Content-Type': 'multipart/form-data'
		};
		if (localStorage.AuthToken)
			headers.Authorization = localStorage.AuthToken;
		let formData = new FormData();
	    formData.append('file', file);
	    for(let i in this._param)
	    	formData.append(i, this._param[i]);
	    let res = await axios({
	        url: this._url,
	        method: 'POST',
	        data: formData,
	        headers
	    });
	    return res.data;
	}
}

const install = (Vue) => {
	Vue.prototype.$api = (url) => {
		return new DataModel(serverURL + url);
	};
	Vue.prototype.$api.setLoginToken = (token) => {
		localStorage.AuthToken = token;
	};
	Vue.prototype.$api.removeLoginToken = () => {
		delete localStorage.AuthToken;
	};
};
export default {
	install
};
```
更多项目自带插件请查看AntOA/frontend/src/plugin文件夹下的各插件js文件。