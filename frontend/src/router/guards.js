import Vue from "vue";
import NProgress from 'nprogress'

NProgress.configure({
	showSpinner: false
})

/**
 * 进度条开始
 * @param to
 * @param form
 * @param next
 */
const progressStart = (to, from, next) => {
	// start progress bar
	if (!NProgress.isStarted()) {
		NProgress.start()
	}
	next()
}

/**
 * 登录守卫
 * @param to
 * @param form
 * @param next
 * @param router
 */
const loginGuard = (to, from, next, router) => {
	if (!to.meta.noAuth && !localStorage.AuthToken) {
		Vue.prototype.$message.warning('登录已失效，请重新登录')
		next({
			path: '/login'
		})
	} else {
		next()
	}
}
/**
 * 进度条结束
 * @param to
 * @param form
 * @param options
 */
const progressDone = () => {
	// finish progress bar
	NProgress.done()
}

const onShowCall = (to) => {
	for (var i in to.matched)
		try {
			if (to.matched[i].instances.default.onShow)
				to.matched[i].instances.default.onShow();
		} catch (e) {
			continue;
		}
}
/**
 * 加载导航守卫
 * @param guards
 * @param router
 */
const loadGuards = function(guards, router) {
	const { beforeEach, afterEach } = guards
	beforeEach.forEach(guard => {
		if (guard && typeof guard === 'function') {
			router.beforeEach((to, from, next) => guard(to, from, next, router))
		}
	})
	afterEach.forEach(guard => {
		if (guard && typeof guard === 'function') {
			router.afterEach((to, from) => guard(to, from, router))
		}
	})
}

export default function(router) {
	return loadGuards({
		beforeEach: [progressStart, loginGuard],
		afterEach: [progressDone, onShowCall]
	}, router)
}
