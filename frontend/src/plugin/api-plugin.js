import axios from "axios";
import Cookie from 'js-cookie'
import {
	setAuthorization,
	removeAuthorization,
	checkAuthorization
} from "../utils/request.js"

class mod {
	_method = "POST";
	_param = {};
	isBlob = false;
	_url = null;

	constructor(url) {
		this._url = url;
	}

	param(param) {
		Object.assign(this._param, param);
		return this;
	}

	method(method) {
		this._method = method.toUpperCase();
		return this;
	}
	
	setBlob(bool){
		this.isBlob = bool;
		return this;
	}

	_getUrlParam() {
		const itemArr = Object.keys(this._param).map((key) => {
			return key + "=" + this._param[key];
		});
		return itemArr.join("&");
	}

	setLoginToken(token) {
		return setAuthorization({token: token});
	}

	removeLoginToken() {
		return removeAuthorization();
	}
	checkAuthorization(){
		return checkAuthorization();
	}

	async call() {
		const headers = {};
		if (Cookie.get("AuthToken"))
			headers.Authorization = Cookie.get("AuthToken");
		let res;
		const config = {headers: headers};
		if(this.isBlob)
			config.responseType = 'blob';
		if (this._method === "POST")
			res = await axios.post(this._url, this._param, config);
		if (this._method === "GET") {
			if (this._url.includes("?"))
				res = await axios.get(this._url + "&" + this._getUrlParam(), config);
			else
				res = await axios.get(this._url + "?" + this._getUrlParam(), config);
		}
		return res.data;
	}
}

const install = (Vue) => {
	Vue.prototype.$api = (url) => {
		return new mod(url);
	};
}
export default {
	install
};
