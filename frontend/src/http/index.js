import { HttpRequest, HttpContentType, createGetHook, createPostHook } from 'muni-http'

const http = new HttpRequest().setBaseURL('https://hy.zhangzhe.icu/api/').setContentType(HttpContentType.form)

export const devHttp = new HttpRequest().setBaseURL('http://localhost:5555/').setContentType(HttpContentType.form)

export const get = createGetHook(http)

export const post = createPostHook(http)

export default http
