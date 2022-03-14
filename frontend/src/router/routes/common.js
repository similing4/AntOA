// 视图组件
const view = {
	tabs: () => import('@/layouts/tabs'),
	blank: () => import('@/layouts/BlankView'),
	page: () => import('@/layouts/PageView')
}

// 路由组件注册
const routerMap = [{
	meta: {
		noAuth: true
	},
	path: '/',
	redirect: '/login',
	component: view.tabs
}, {
	meta: {
		noAuth: true
	},
	path: '/login',
	component: () => import('@/pages/login/Login.vue')
}, {
	meta: {
		noAuth: true
	},
	path: '/403',
	component: () => import('@/pages/exception/403')
}, {
	meta: {
		noAuth: true
	},
	path: '/404',
	component: () => import('@/pages/exception/404')
}, {
	meta: {
		noAuth: true
	},
	path: '/500',
	component: () => import('@/pages/exception/500')
}, {
	meta: {
		noAuth: true
	},
	path: '/*',
	redirect: '/404'
}]
export default routerMap;
