import Vue from 'vue'
import App from './App'
import {
	initRouter
} from './router'
import './theme/index.less'
import Antd from 'ant-design-vue'
import Viser from 'viser-vue'
import '@/mock'
import store from './store'
import 'animate.css/source/animate.css'
import Plugins from './plugin'
import bootstrap from './bootstrap'
import 'moment/locale/zh-cn'
import Mock from 'mockjs';

Vue.config.productionTip = false;
const router = initRouter(store.state.setting.asyncRoutes)

Vue.use(Antd)
Vue.use(Viser)
Vue.use(Plugins)

bootstrap({
	router,
	store,
	message: Vue.prototype.$message
})

/* eslint-disable no-new */
const app = new Vue({
	router: router,
	store: store,
	render: h => h(App)
})

Mock.setup({
	timeout: 800 // setter delay time
});
Mock.XHR.prototype.__send = Mock.XHR.prototype.send;
Mock.XHR.prototype.send = function () {
	try {
		if (this.custom.xhr)
			this.custom.xhr.responseType = this.responseType;
	} finally {
		this.__send.apply(this, arguments);
	}
};
window.XMLHttpRequest.prototype.upload = new window._XMLHttpRequest().upload;

app.$mount('#app')
