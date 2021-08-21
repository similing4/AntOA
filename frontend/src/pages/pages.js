import TabsView from '@/layouts/tabs/TabsView'
//import BlankView from '@/layouts/BlankView'
import PageView from '@/layouts/PageView'
export default [{
	path: '/login',
	name: '登录页',
	component: () => import('@/pages/login/Login.vue')
}, {
	path: '*',
	name: '404',
	component: () => import('@/pages/exception/404'),
}, {
	path: '/403',
	name: '403',
	component: () => import('@/pages/exception/403'),
}, {
	path: '500',
	name: 'Exp500',
	component: () => import('@/pages/exception/500')
}]
