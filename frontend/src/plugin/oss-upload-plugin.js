import * as qiniu from 'qiniu-js';
const upload = (filef) => {
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
};
const install = (Vue) => {
  Vue.prototype.$oss = {
    upload
  }
}
export default {
  install
};
