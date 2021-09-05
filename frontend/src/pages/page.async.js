//import TabsView from '@/layouts/tabs/TabsView'
import BlankView from '@/layouts/BlankView'
import PageView from '@/layouts/PageView'
export default {
	parent: {
		name: '父级路由',
		component: PageView
	},
	bparent: {
		name: '父级空路由',
		component: BlankView
	},
	home: {
		path: '/home',
		name: '首页',
		component: () => import('@/pages/home/Home.vue')
	},
	list: {
		path: '/list',
		name: '列表页',
		component: () => import('@/pages/common/list.vue')
	},
	create: {
		path: '/create',
		name: '创建页',
		component: () => import('@/pages/common/create.vue')
	},
	edit: {
		path: '/edit',
		name: '编辑页',
		component: () => import('@/pages/common/edit.vue')
	},
	'/software/test/diy_list': {
		path: '/software/test/diy_list',
		name: '自定义列表页',
		component: () => import('@/pages/diy/list.vue')
	}
}
