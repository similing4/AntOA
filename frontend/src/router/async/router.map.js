import pageAsync from "../../pages/page.async.js";
// 视图组件
const view = {
	tabs: () => import('@/layouts/tabs'),
	blank: () => import('@/layouts/BlankView'),
	page: () => import('@/layouts/PageView')
}

// 路由组件注册
const routerMap = {
	login: {
		authority: '*',
		path: '/login',
		component: () => import('@/pages/login/Login.vue')
	},
	exp403: {
		authority: '*',
		name: 'exp403',
		path: '403',
		component: () => import('@/pages/exception/403')
	},
	exp404: {
		name: 'exp404',
		path: '404',
		component: () => import('@/pages/exception/404')
	},
	exp500: {
		name: 'exp500',
		path: '500',
		component: () => import('@/pages/exception/500')
	},
	root: {
		path: '/',
		name: '首页',
		redirect: '/login',
		component: view.tabs
	},
	exception: {
		name: '异常页',
		icon: 'warning',
		component: view.blank
	}
}
export default Object.assign(routerMap,pageAsync);