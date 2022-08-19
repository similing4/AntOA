import Vue from 'vue'
import Router from 'vue-router'
import App from './App'
import routerInit from './router'
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
let pluginRoutes = [];
let basePluginRoutes = [];
for(let i = 0; i < Object.values(external_module).length; i++){
    let plugin = Object.values(external_module)[i];
    Vue.use(plugin);
    if(plugin.routes)
        pluginRoutes = plugin.routes.concat(pluginRoutes);
    if(plugin.base_routes)
        basePluginRoutes = plugin.base_routes;
}
let router = routerInit(pluginRoutes, basePluginRoutes);
for(let i = 0; i < Object.values(external_module).length; i++){
    let plugin = Object.values(external_module)[i];
    if(plugin.dealRouter)
        plugin.dealRouter(router);
}

/* eslint-disable no-new */
const app = new Vue({
	router: router,
	store: store,
	render: h => h(App)
})

app.$mount('#app')
