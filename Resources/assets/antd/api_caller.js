let mod = function (url) {
    this._method = "POST";
    this._param = {};
    this._url = url;
	this.isBlob = false;
    this.param = (param) => {
        Object.assign(this._param, param);
        return this;
    }
    this.method = (method) => {
        this._method = method.toUpperCase();
        return this;
    }
    this._getUrlParam = () => {
        const itemArr = Object.keys(this._param).map((key) => {
            return key + "=" + this._param[key];
        });
        return itemArr.join("&");
    }
	this.setBlob = (bool) => {
		this.isBlob = bool;
		return this;
	}
    this.call = async () => {
        const headers = {};
        if (localStorage.token)
            headers.Authorization = localStorage.token;
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
};
Vue.prototype.$api = (url) => {
    return new mod(url);
}

Vue.prototype.$oss = {
    upload: (filef) => {
        return new Promise((resolve, reject) => {
            Vue.prototype.$api("/api/antoa/auth/config").method("GET").call().then((res) => {
                if (!res.status)
                    return reject(res.msg);
                const token = res.token;
                const host = res.host;
                const key = null;
                const putExtra = {};
                const config = {
                    useCdnDomain: true, //使用cdn加速
                };
                const observable = qiniu.upload(filef, key, token, putExtra, config);
                observable.subscribe({
                    next: (result) => {
                        console.warn(result);
                    },
                    error: (e) => {
                        return reject(e);
                    },
                    complete: (res) => {
                        let key = res.key;
                        const data = {
                            url: host + key,
                            type: filef.name ? filef.name.substring(filef.name.lastIndexOf('.') + 1) : 'html'
                        };
                        resolve(data)
                    }
                });
            });
        })
    }
}
