import axios from "axios";
const serverURL = process.env.VUE_APP_API_BASE_URL;

class DataModel {
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

	setBlob(bool) {
		this.isBlob = bool;
		return this;
	}

	_getUrlParam() {
		const itemArr = Object.keys(this._param).map((key) => {
			return key + "=" + this._param[key];
		});
		return itemArr.join("&");
	}

	checkAuthorization() {
		return !!localStorage.AuthToken;
	}

	async call() {
		const headers = {};
		if (localStorage.AuthToken)
			headers.Authorization = localStorage.AuthToken;
		let res;
		const config = { headers: headers };
		if (this.isBlob)
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
	async upload(file){
		const headers = {
			'Content-Type': 'multipart/form-data'
		};
		if (localStorage.AuthToken)
			headers.Authorization = localStorage.AuthToken;
		let formData = new FormData();
	    formData.append('file', file);
	    for(let i in this._param)
	    	formData.append(i, this._param[i]);
	    let res = await axios({
	        url: this._url,
	        method: 'POST',
	        data: formData,
	        headers
	    });
	    return res.data;
	}
}

const install = (Vue) => {
	Vue.prototype.$api = (url) => {
		return new DataModel(serverURL + url);
	};
	Vue.prototype.$api.setLoginToken = (token) => {
		localStorage.AuthToken = token;
	};
	Vue.prototype.$api.removeLoginToken = () => {
		delete localStorage.AuthToken;
	};
};
export default {
	install
};
