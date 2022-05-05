import TabsView from '@/layouts/tabs/TabsView'
import BlankView from '@/layouts/BlankView'
import PageView from '@/layouts/PageView'
import HomePage from '@/pages/home/Home.js';
export default [{
	path: '-',
	name: 'ANTOA_BASE_ROUTE',
	component: TabsView,
	children: [{
		path: '/home',
		name: '首页',
		component: () => HomePage
	}, {
		path: '/*/list',
		name: '列表页',
		component: () => import('@/pages/common/list.vue')
	}, {
		path: '/*/create',
		name: '创建页',
		component: () => import('@/pages/common/create.vue')
	}, {
		path: '/*/edit',
		name: '编辑页',
		component: () => import('@/pages/common/edit.vue')
	}, {
		path: '/antoa/user/change_password',
		name: '修改密码',
		component: () => import('@/pages/common/change_password.vue')
	}]
}]
