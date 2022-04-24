import Vue from 'vue'
import Router from 'vue-router'
import App from './App'
import router from './router'
import './theme/index.less'
import Antd from 'ant-design-vue'
import store from './store'
import 'animate.css/source/animate.css'
import Plugins from './plugin'
import 'moment/locale/zh-cn'
import external_module from "../external_module_js.js"

Vue.config.productionTip = false;

Vue.use(Router);
Vue.use(Antd);
Vue.use(Plugins);
console.log(external_module);

/* eslint-disable no-new */
const app = new Vue({
	router: router,
	store: store,
	render: h => h(App)
})

app.$mount('#app')
