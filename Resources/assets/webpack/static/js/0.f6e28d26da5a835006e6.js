webpackJsonp([0],{"9L9X":function(e,t){throw new Error("Module build failed: ModuleBuildError: Module build failed: TypeError: loaderContext.getResolve is not a function\n    at createWebpackLessPlugin (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\utils.js:30:33)\n    at getLessOptions (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\utils.js:147:28)\n    at Object.lessLoader (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\index.js:29:49)\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\webpack\\lib\\NormalModule.js:195:19\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:367:11\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:233:18\n    at runSyncOrAsync (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:143:3)\n    at iterateNormalLoaders (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:232:2)\n    at iterateNormalLoaders (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:221:10)\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:236:3\n    at Object.context.callback (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:111:13)\n    at Object.module.exports (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\vue-loader\\lib\\selector.js:21:8)")},"9X7P":function(e,t){},hAsR:function(e,t){throw new Error("Module build failed: ModuleBuildError: Module build failed: TypeError: loaderContext.getResolve is not a function\n    at createWebpackLessPlugin (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\utils.js:30:33)\n    at getLessOptions (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\utils.js:147:28)\n    at Object.lessLoader (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\less-loader\\dist\\index.js:29:49)\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\webpack\\lib\\NormalModule.js:195:19\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:367:11\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:233:18\n    at runSyncOrAsync (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:143:3)\n    at iterateNormalLoaders (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:232:2)\n    at iterateNormalLoaders (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:221:10)\n    at C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:236:3\n    at Object.context.callback (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\loader-runner\\lib\\LoaderRunner.js:111:13)\n    at Object.module.exports (C:\\Users\\wolves\\Desktop\\soft.shengxinyustudio.com\\Modules\\AntOA\\frontend\\node_modules\\vue-loader\\lib\\selector.js:21:8)")},"n/G1":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n("Xxa5"),o=n.n(s),r=n("exGp"),a=n.n(r),i={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"and-admin-footer"},[n("div",{staticClass:"links"},e._l(e.linkList,function(t,s){return n("a",{key:s,attrs:{target:"_blank",href:t.link?t.link:"javascript: void(0)"}},[t.icon?n("a-icon",{attrs:{type:t.icon}}):e._e(),e._v("\n      "+e._s(t.name)+"\n    ")],1)}),0),e._v(" "),n("div",{staticClass:"copyright"},[e._v("\n    Copyright\n    "),n("a-icon",{attrs:{type:"copyright"}}),e._v("\n    "+e._s(e.copyright)+"\n  ")],1)])},staticRenderFns:[]};var u={name:"BaseLayout",data:function(){return{loadFinished:!1,is_side_menu_open:!0,menuList:[],breadcrumb:[],breadcrumbTitle:"",user:{avatar:"",username:"后台用户"},token:localStorage.token}},components:{PageFooter:n("VU/8")({name:"PageFooter",data:function(){return{linkList:[{link:"https://iczer.gitee.io/vue-antd-admin-docs",name:"项目首页"},{link:"https://github.com/iczer/vue-antd-admin",icon:"github"},{link:"https://www.shengxinyustudio.com",name:"作者主页"}],copyright:"Vue Antd Admin 2021"}}},i,!1,function(e){n("hAsR")},"data-v-029dd169",null).exports},created:function(){var e=this;return a()(o.a.mark(function t(){var n;return o.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.checkAuth();case 2:return t.next=4,e.getConfig();case 4:n=t.sent,console.log(n);case 6:case"end":return t.stop()}},t,e)}))()},methods:{checkAuth:function(){var e=this;return a()(o.a.mark(function t(){return o.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:if(t.prev=0,localStorage.token){t.next=3;break}throw"登录失败";case 3:return t.next=5,e.$api("/api/antoa/auth/auth").param({token:localStorage.token}).method("POST").call();case 5:if(t.sent.status){t.next=8;break}throw"登录失效";case 8:t.next=13;break;case 10:t.prev=10,t.t0=t.catch(0),e.$router.push({path:"/antoa/auth/login"});case 13:case"end":return t.stop()}},t,e,[[0,10]])}))()},getConfig:function(){var e=this;return a()(o.a.mark(function t(){var n;return o.a.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:if(t.prev=0,localStorage.token){t.next=3;break}throw"登录失败";case 3:return t.next=5,e.$api("/api/antoa/auth/config").method("POST").call();case 5:if((n=t.sent).status){t.next=8;break}throw n.msg;case 8:return t.abrupt("return",n.data);case 11:t.prev=11,t.t0=t.catch(0),e.$message.error(t.t0+"",3);case 14:return t.abrupt("return",null);case 15:case"end":return t.stop()}},t,e,[[0,11]])}))()},goUrl:function(e,t){e.startsWith("/")||(e="/"+e),this.$router.push({path:e})}}},d={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[e.loadFinished?n("div",{staticClass:"appMain"},[n("div",{staticClass:"home-sidemenu beauty-scroll",style:{width:e.is_side_menu_open?"256px":"80px"}},[n("div",{staticClass:"logo"},[e.is_side_menu_open?n("h1",[e._v("后台管理系统")]):e._e()]),e._v(" "),n("div",{staticClass:"menu"},[n("a-menu",{attrs:{mode:"inline",theme:"dark","inline-collapsed":!e.is_side_menu_open}},[e._l(e.menuList,function(t){return!1!==t.visible?[0===t.children.filter(function(e){return!1!==e.visible}).length?n("a-menu-item",{key:t.id,on:{click:function(n){return e.goUrl(t.uri,[t.id])}}},[n("a-icon",{attrs:{type:"pie-chart"}}),e._v(" "),n("span",[e._v(e._s(t.title))])],1):e._e(),e._v(" "),t.children.filter(function(e){return!1!==e.visible}).length>0?n("a-sub-menu",{key:t.id},[n("span",{attrs:{slot:"title"},slot:"title"},[n("a-icon",{attrs:{type:"appstore"}}),e._v(" "),n("span",[e._v(e._s(t.title))])],1),e._v(" "),e._l(t.children,function(s){return!1!==s.visible?n("a-menu-item",{key:s.id,on:{click:function(n){return e.goUrl(s.uri,[t.id,s.id])}}},[e._v("\n                "+e._s(s.title)+"\n              ")]):e._e()})],2):e._e()]:e._e()})],2)],1)]),e._v(" "),n("div",{staticClass:"home-right-wrapper beauty-scroll"},[n("div",[n("div",{staticClass:"home-header"},[n("div",{staticClass:"menu-toggle-btn"},[e.is_side_menu_open?n("a-icon",{attrs:{type:"menu-fold"},on:{click:function(t){e.is_side_menu_open=!1}}}):e._e(),e._v(" "),e.is_side_menu_open?e._e():n("a-icon",{attrs:{type:"menu-unfold"},on:{click:function(t){e.is_side_menu_open=!0}}})],1),e._v(" "),n("div",{staticClass:"menu-header-icon-avatar"},[n("a-dropdown",[n("div",{staticClass:"header-avatar",staticStyle:{cursor:"pointer"}},[n("a-avatar",{staticClass:"avatar",attrs:{size:"small",shape:"circle",src:e.user.avatar}}),e._v(" "),n("div",{staticClass:"name"},[e._v(e._s(e.user.username))])],1),e._v(" "),n("a-menu",{class:["avatar-menu"],attrs:{slot:"overlay"},slot:"overlay"},[n("a-menu-item",{on:{click:function(t){return e.goUrl(e.menuList[0].uri)}}},[n("a-icon",{attrs:{type:"user"}}),e._v(" "),n("span",[e._v("返回管理首页")])],1),e._v(" "),n("a-menu-divider"),e._v(" "),n("a-menu-item",{on:{click:function(t){return e.goUrl("/antoa/auth/logout?token="+e.token)}}},[n("a-icon",{staticStyle:{"margin-right":"8px"},attrs:{type:"poweroff"}}),e._v(" "),n("span",[e._v("退出登录")])],1)],1)],1)],1)]),e._v(" "),e.breadcrumb.length>0||e.breadcrumbDefault.length>0?n("div",{staticClass:"home-main-breadcrumb"},[n("div",{staticClass:"breadcrumb-wrapper"},[e.breadcrumb.length>0?n("a-breadcrumb",[n("a-breadcrumb-item",[n("span",[e._v("后台管理系统")])]),e._v(" "),e._l(e.breadcrumb,function(t,s){return n("a-breadcrumb-item",{key:s},[n("span",[e._v(e._s(t))])])})],2):e._e()],1),e._v(" "),n("div",{staticClass:"breadcrumb-detail"},[e._v("\n            "+e._s(e.breadcrumbTitle)+"\n          ")])]):e._e()]),e._v(" "),n("div",{staticClass:"home-main-content"},[e._v("\n        @yield('content')\n      ")]),e._v(" "),n("div",{staticClass:"home-footer"},[n("page-footer")],1)])]):e._e()])},staticRenderFns:[]};var l={name:"Home",components:{BaseLayout:n("VU/8")(u,d,!1,function(e){n("9L9X")},"data-v-0905e6d7",null).exports}},c={render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",[t("BaseLayout")],1)},staticRenderFns:[]};var m=n("VU/8")(l,c,!1,function(e){n("9X7P")},"data-v-7c302ec5",null);t.default=m.exports}});