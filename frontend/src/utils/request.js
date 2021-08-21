import axios from 'axios'
import Cookie from 'js-cookie'
import md5 from 'js-md5';

// 跨域认证信息 header 名
const xsrfHeaderName = 'AuthToken'

axios.defaults.timeout = 5000
axios.defaults.withCredentials = true
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;

// 认证类型
const AUTH_TYPE = {
	BEARER: 'Bearer',
	BASIC: 'basic',
	AUTH1: 'auth1',
	AUTH2: 'auth2',
}

// http method
const METHOD = {
	GET: 'get',
	POST: 'post'
}

function logout() {
	localStorage.removeItem(process.env.VUE_APP_ROUTES_KEY)
	localStorage.removeItem(process.env.VUE_APP_PERMISSIONS_KEY)
	localStorage.removeItem(process.env.VUE_APP_ROLES_KEY)
	removeAuthorization()
}

/**
 * axios请求
 * @param url 请求地址
 * @param method {METHOD} http method
 * @param params 请求参数
 * @returns {Promise<AxiosResponse<T>>}
 */
async function request(url, method, params, config) {
	if (!config)
		config = {};
	var userToken = Cookie.get(xsrfHeaderName);
	var headers = {
		'X-Requested-With': 'XMLHttpRequest',
		'Content-Type': 'application/x-www-form-urlencoded',
	};
	if (userToken) {
		var time = parseInt((new Date()).valueOf() / 1000);
		headers.TOKEN_UID = userToken.split("_")[1];
		headers.TOKEN_TIME = time;
		headers.TOKEN_ACCESSSTR = md5("OIKHNSDKFFHGIORY54345276" + md5(userToken.split("_")[0] +
			time));
	}
	switch (method) {
		case METHOD.GET:
			return axios.get(url, {
				params,
				headers,
				...config
			}).then((res) => {
				if (res.data.code == 0 && res.data.msg == "登录失效") {
					logout();
				}
				return new Promise((recv) => {
					recv(res);
				})
			})
		case METHOD.POST:
			return axios.post(url, params, {
				headers,
				transformRequest: [function(data) {
					var ret = []
					for (var i in data) {
						ret.push(encodeURIComponent(i) + '=' + encodeURIComponent(data[i]));
					}
					return ret.join("&");
				}],
				...config
			}).then((res) => {
				if (res.data.code == 0 && res.data.msg == "登录失效") {
					logout();
				}
				return new Promise((recv) => {
					recv(res);
				})
			})
		default:
			return axios.get(url, {
				params,
				headers,
				...config
			}).then((res) => {
				if (res.data.code == 0 && res.data.msg == "登录失效") {
					logout();
				}
				return new Promise((recv) => {
					recv(res);
				})
			})
	}
}

/**
 * 设置认证信息
 * @param auth {Object}
 * @param authType {AUTH_TYPE} 认证类型，默认：{AUTH_TYPE.BEARER}
 */
function setAuthorization(auth, authType = AUTH_TYPE.BEARER) {
	switch (authType) {
		case AUTH_TYPE.BEARER:
			Cookie.set(xsrfHeaderName, auth.token, {
				expires: auth.expireAt
			})
			break
		case AUTH_TYPE.BASIC:
		case AUTH_TYPE.AUTH1:
		case AUTH_TYPE.AUTH2:
		default:
			break
	}
}

/**
 * 移出认证信息
 * @param authType {AUTH_TYPE} 认证类型
 */
function removeAuthorization(authType = AUTH_TYPE.BEARER) {
	switch (authType) {
		case AUTH_TYPE.BEARER:
			Cookie.remove(xsrfHeaderName)
			break
		case AUTH_TYPE.BASIC:
		case AUTH_TYPE.AUTH1:
		case AUTH_TYPE.AUTH2:
		default:
			break
	}
}

/**
 * 检查认证信息
 * @param authType
 * @returns {boolean}
 */
function checkAuthorization(authType = AUTH_TYPE.BEARER) {
	switch (authType) {
		case AUTH_TYPE.BEARER:
			if (Cookie.get(xsrfHeaderName)) {
				return true
			}
			break
		case AUTH_TYPE.BASIC:
		case AUTH_TYPE.AUTH1:
		case AUTH_TYPE.AUTH2:
		default:
			break
	}
	return false
}

/**
 * 加载 axios 拦截器
 * @param interceptors
 * @param options
 */
function loadInterceptors(interceptors, options) {
	const {
		request,
		response
	} = interceptors
	// 加载请求拦截器
	request.forEach(item => {
		let {
			onFulfilled,
			onRejected
		} = item
		if (!onFulfilled || typeof onFulfilled !== 'function') {
			onFulfilled = config => config
		}
		if (!onRejected || typeof onRejected !== 'function') {
			onRejected = error => Promise.reject(error)
		}
		axios.interceptors.request.use(
			config => onFulfilled(config, options),
			error => onRejected(error, options)
		)
	})
	// 加载响应拦截器
	response.forEach(item => {
		let {
			onFulfilled,
			onRejected
		} = item
		if (!onFulfilled || typeof onFulfilled !== 'function') {
			onFulfilled = response => response
		}
		if (!onRejected || typeof onRejected !== 'function') {
			onRejected = error => Promise.reject(error)
		}
		axios.interceptors.response.use(
			response => onFulfilled(response, options),
			error => onRejected(error, options)
		)
	})
}

/**
 * 解析 url 中的参数
 * @param url
 * @returns {Object}
 */
function parseUrlParams(url) {
	const params = {}
	if (!url || url === '' || typeof url !== 'string') {
		return params
	}
	const paramsStr = url.split('?')[1]
	if (!paramsStr) {
		return params
	}
	const paramsArr = paramsStr.replace(/&|=/g, ' ').split(' ')
	for (let i = 0; i < paramsArr.length / 2; i++) {
		const value = paramsArr[i * 2 + 1]
		params[paramsArr[i * 2]] = value === 'true' ? true : (value === 'false' ? false : value)
	}
	return params
}

export {
	METHOD,
	AUTH_TYPE,
	request,
	setAuthorization,
	removeAuthorization,
	checkAuthorization,
	loadInterceptors,
	parseUrlParams
}
