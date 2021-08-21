import {
	loadRoutes,
	loadGuards,
	setAppOptions
} from '@/utils/routerUtil'
import {
	loadInterceptors
} from '@/utils/request'
import guards from '@/router/guards'
import interceptors from '@/utils/axios-interceptors'

/**
 * 启动引导方法
 * 应用启动时需要执行的操作放在这里
 * @param router 应用的路由实例
 * @param store 应用的 vuex.store 实例
 * @param message 应用的 message 实例
 */
function bootstrap({
	router,
	store,
	message
}) {
	// 设置应用配置
	setAppOptions({
		router,
		store
	})
	// 加载 axios 拦截器
	loadInterceptors(interceptors, {
		router,
		store,
		message
	})
	// 加载路由
	loadRoutes()
	// 加载路由守卫
	loadGuards(guards, {
		router,
		store,
		message
	})
}

export default bootstrap
