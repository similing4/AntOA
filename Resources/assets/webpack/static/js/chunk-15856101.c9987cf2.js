(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-15856101"],{"6ed5":function(o,t,e){"use strict";e("e130")},d995:function(o,t,e){"use strict";e.r(t);var l=function(){var o=this,t=o.$createElement,e=o._self._c||t;return e("a-card",[null!=o.api?e("a-form",[o._l(o.columns,(function(t,l){return["COLUMN_DISPLAY"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[o.form[t.col]?o._e():e("div",{domProps:{innerHTML:o._s(t.extra)}}),o.form[t.col]?e("div",{domProps:{innerHTML:o._s(o.form[t.col])}}):o._e(),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_TEXT"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-input",{attrs:{placeholder:"请填写"+t.tip},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_NUMBER_DIVIDE"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-input-number",{attrs:{placeholder:"请填写"+t.tip},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o._v(" "+o._s(t.extra.unit)+" "),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v("@"+o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_TEXTAREA"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-textarea",{attrs:{placeholder:"请填写"+t.tip,rows:"20","allow-clear":""},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_PASSWORD"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-input-password",{attrs:{placeholder:"请填写"+t.tip+"，不修改密码则请留空"},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_RADIO"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-radio-group",{on:{change:function(t){return o.$forceUpdate()}},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}},o._l(t.extra,(function(t,l){return e("a-radio",{key:l+"",attrs:{value:l+""}},[o._v(" "+o._s(t)+" ")])})),1),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_SELECT"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-select",{model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}},o._l(t.extra,(function(t,l){return e("a-select-option",{key:l+"",attrs:{value:l+""}},[o._v(" "+o._s(t)+" ")])})),1),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_CHECKBOX"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-checkbox-group",{on:{change:function(t){return o.$forceUpdate()}},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}},o._l(t.extra,(function(t,l){return e("a-checkbox",{key:l,attrs:{value:l}},[o._v(" "+o._s(t)+" ")])})),1),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_TIMESTAMP"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-date-picker",{attrs:{"show-time":"",format:"YYYY-MM-DD HH:mm:ss",placeholder:"请选择"+t.tip},on:{change:function(t){return o.$forceUpdate()}},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_RICHTEXT"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("wang-editor",{attrs:{id:o.form[t.col]},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_PICTURE"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[""!=o.form[t.col]?e("img",{staticStyle:{width:"200px"},attrs:{src:o.form[t.col]}}):o._e(),""!=o.form[t.col]?e("a-button",{attrs:{type:"danger"},on:{click:function(e){o.form[t.col]=""}}},[o._v("删除 ")]):o._e(),e("upload-button",{attrs:{accept:"image/*",multiple:!1},on:{uploadfinished:function(e){o.form[t.col]=e[0].response}}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_FILE"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[""!=o.form[t.col]?e("a-button",{attrs:{type:"primary"},on:{click:function(e){return o.openurl(o.form[t.col])}}},[o._v("下载 ")]):o._e(),""!=o.form[t.col]?e("a-button",{attrs:{type:"danger"},on:{click:function(e){o.form[t.col]=""}}},[o._v("删除 ")]):o._e(),e("upload-button",{attrs:{accept:"*/*",multiple:!1},on:{uploadfinished:function(e){o.form[t.col]=e[0].response}}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_PICTURES"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[o._l(o.form[t.col],(function(l,n){return e("div",{key:n},[e("img",{staticStyle:{width:"200px"},attrs:{src:l}}),e("a-button",{attrs:{type:"danger"},on:{click:function(e){o.form[t.col]=o.form[t.col].filter((function(o){return o!=l}))}}},[o._v(" 删除 ")])],1)})),e("upload-button",{attrs:{accept:"image/*",multiple:!0},on:{uploadfinished:function(e){o.form[t.col]=o.form[t.col].concat(e.map((function(o){return o.response})))}}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],2):o._e(),"COLUMN_FILES"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[o._l(o.form[t.col],(function(l,n){return e("div",{key:n},[e("a-button",{attrs:{type:"primary"},on:{click:function(t){return o.openurl(l)}}},[o._v("下载")]),e("a-button",{attrs:{type:"danger"},on:{click:function(e){o.form[t.col]=o.form[t.col].filter((function(o){return o!=l}))}}},[o._v(" 删除 ")])],1)})),e("upload-button",{attrs:{accept:"*/*",multiple:!0},on:{uploadfinished:function(e){o.form[t.col]=o.form[t.col].concat(e.map((function(o){return o.response})))}}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],2):o._e(),"COLUMN_CHOOSE"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-cascader",{attrs:{placeholder:"请选择"+t.tip,options:t.extra},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_CHILDREN_CHOOSE"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[void 0!==o.formTip[t.col]?e("column-children-choose",{attrs:{tip:o.formTip[t.col],column:t,api:o.api,pagetype:"edit"},on:{"update:tip":function(e){return o.$set(o.formTip,t.col,e)}},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}):o._e(),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e(),"COLUMN_TREE_CHECKBOX"==t.type?e("a-form-item",{directives:[{name:"show",rawName:"v-show",value:o.displayColumns.includes(t.col),expression:"displayColumns.includes(column.col)"}],attrs:{label:t.tip,"label-col":{span:7},"wrapper-col":{span:10}}},[e("a-tree-select",{staticStyle:{width:"100%"},attrs:{"tree-data":t.extra,"tree-checkable":"","search-placeholder":"请选择"+t.tip},model:{value:o.form[t.col],callback:function(e){o.$set(o.form,t.col,e)},expression:"form[column.col]"}}),o.getApiButtonByColumn(t.col)?e("a-button",{attrs:{type:o.getApiButtonByColumn(t.col).type},on:{click:function(e){o.callApi(o.getApiButtonByColumn(t.col).url)}}},[o._v(o._s(o.getApiButtonByColumn(t.col).title))]):o._e()],1):o._e()]})),e("a-form-item",{staticStyle:{display:"flex","justify-content":"center"}},[e("a-button",{attrs:{type:"primary"},on:{click:o.submit}},[o._v("修改")]),e("a-button",{staticStyle:{"margin-left":"8px"},attrs:{type:"primary"},on:{click:o.reset}},[o._v("重置")])],1)],2):o._e(),e("confirm-dialog",{ref:"confirmDialog"})],1)},n=[],c=e("1da1"),a=(e("96cf"),e("d81d"),e("4de4"),e("2ca0"),e("caad"),e("2532"),e("ac1f"),e("1276"),e("8a79"),e("c1df")),r=e.n(a),s=e("3521"),u=e("129a"),i=e("ba8c"),p=e("31ce"),m=e("4160"),f={data:function(){return{id:null,columns:null,apiButtons:null,form:null,formTip:null,api:null,displayColumns:[],formItemTip:{}}},components:{ConfirmDialog:u["a"],StandardTable:s["a"],WangEditor:i["a"],UploadButton:p["a"],ColumnChildrenChoose:m["a"]},mounted:function(){var o=this;return Object(c["a"])(regeneratorRuntime.mark((function t(){var e,l,n,c,a,s,u,i;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(t.prev=0,o.$route.query.id){t.next=3;break}throw"页面不存在，请检查传入参数！";case 3:return e=o.$route.path.substring(0,o.$route.path.length-"/edit".length),l="/api"+e+"/grid_config",t.next=7,o.$api(l).method("GET").call();case 7:if(n=t.sent,n.status){t.next=10;break}throw n.msg;case 10:c=n.grid.edit,a=n.api,s={},u={},i=function(t){return o.$route.query[t]?o.$route.query[t]:""},c.columns.map((function(t){o.displayColumns.push(t.col),"COLUMN_CHECKBOX"===t.type||"COLUMN_PICTURES"===t.type||"COLUMN_FILES"===t.type||"COLUMN_CHOOSE"===t.type||"COLUMN_TREE_CHECKBOX"===t.type?s[t.col]=""!==i(t.col)?JSON.parse(i(t.col)):[]:"COLUMN_TIMESTAMP"===t.type?s[t.col]=""!==i(t.col)?i(t.col):r()():s[t.col]=i(t.col),"COLUMN_CHILDREN_CHOOSE"===t.type&&(u[t.col]="")})),o.id=o.$route.query.id,o.columns=c.columns,o.apiButtons=c.columns_api_button,o.form=s,o.formTip=u,o.api=a,o.reset().then((function(){o.setWatchHook(c)})),t.next=28;break;case 25:t.prev=25,t.t0=t["catch"](0),o.$message.error("配置加载错误："+t.t0,5);case 28:case"end":return t.stop()}}),t,null,[[0,25]])})))()},methods:{setWatchHook:function(o){var t=this;o.change_hook&&(this.onHookCall(),o.change_hook.map((function(o){t.$watch("form."+o,(function(){t.onHookCall()}))})))},onHookCall:function(){var o=this;return Object(c["a"])(regeneratorRuntime.mark((function t(){var e,l,n,c;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:for(l in e={},o.columns.map((function(t){if("COLUMN_DISPLAY"!==t.type)return"COLUMN_NUMBER_DIVIDE"===t.type?e[t.col]=parseFloat(o.form[t.col])*t.divide:void(e[t.col]=o.form[t.col])})),e)e[l]instanceof r.a&&(e[l]=e[l].format("YYYY-MM-DD HH:mm:ss")),e[l]instanceof Array&&(e[l]=JSON.stringify(e[l]));return t.prev=3,t.next=6,o.$api(o.api.api_column_change).method("POST").param({type:"edit",form:e}).call();case 6:if(n=t.sent,!n.status){t.next=13;break}for(c in n=n.data,o.columns)void 0!==n.data[o.columns[c].col]&&("COLUMN_CHECKBOX"===o.columns[c].type||"COLUMN_PICTURES"===o.columns[c].type||"COLUMN_FILES"===o.columns[c].type||"COLUMN_CHOOSE"===o.columns[c].type||"COLUMN_TREE_CHECKBOX"===o.columns[c].type?o.form[o.columns[c].col]=JSON.parse(n.data[o.columns[c].col]):"COLUMN_TIMESTAMP"===o.columns[c].type?o.form[o.columns[c].col]=r()(n.data[o.columns[c].col],"YYYY-MM-DD HH:mm:ss"):"COLUMN_NUMBER_DIVIDE"===o.columns[c].type?o.form[o.columns[c].col]=parseFloat(n.data[o.columns[c].col])/o.columns[c].divide+"":o.form[o.columns[c].col]=n.data[o.columns[c].col]+""),"COLUMN_CHILDREN_CHOOSE"===o.columns[c].type&&(o.formItemTip[o.columns[c].col]?o.formTip[o.columns[c].col]=o.formItemTip[o.columns[c].col][o.columns[c].extra.displayColumn]:o.formTip[o.columns[c].col]="");if(n.display||n.display===[]){t.next=12;break}return t.abrupt("return");case 12:o.displayColumns=n.display;case 13:t.next=18;break;case 15:t.prev=15,t.t0=t["catch"](3),o.$message.error(t.t0+"",5);case 18:case"end":return t.stop()}}),t,null,[[3,15]])})))()},getApiButtonByColumn:function(o){var t=this.apiButtons.filter((function(t){return t.column===o}));return 0===t.length?null:t[0]},callApi:function(o){var t=this;return Object(c["a"])(regeneratorRuntime.mark((function e(){var l,n,c,a;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:for(n in l={},t.columns.map((function(o){if("COLUMN_DISPLAY"!==o.type)return"COLUMN_NUMBER_DIVIDE"===o.type?l[o.col]=parseFloat(t.form[o.col])*o.divide:void(l[o.col]=t.form[o.col])})),l)l[n]instanceof r.a&&(l[n]=l[n].format("YYYY-MM-DD HH:mm:ss")),l[n]instanceof Array&&(l[n]=JSON.stringify(l[n]));return e.prev=3,e.next=6,t.$api(o).method("POST").param(l).call();case 6:if(c=e.sent,c.status){e.next=9;break}throw c.msg;case 9:for(a in c.data)t.form[a]=c.data[a];c.msg&&t.$message.success(c.data),e.next=16;break;case 13:e.prev=13,e.t0=e["catch"](3),t.$message.error(e.t0+"",5);case 16:case"end":return e.stop()}}),e,null,[[3,13]])})))()},openurl:function(o){if(o.startsWith("http"))return window.open(o);var t=o,e="";o.includes("?")&&(t=o.split("?")[0],e=o.split("?")[1]),t.endsWith("/create")?(o="/create?path="+t.substring(0,t.length-"/create".length),""!=e&&(o+="&"+e)):t.endsWith("/edit")?(o="/edit?path="+t.substring(0,t.length-"/edit".length),""!=e&&(o+="&"+e)):t.endsWith("/list")&&(o="/list?path="+t.substring(0,t.length-"/list".length),""!=e&&(o+="&"+e)),this.$router.push(o)},reset:function(){var o=this;return Object(c["a"])(regeneratorRuntime.mark((function t(){var e,l,n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return e=function(t){return o.$route.query[t]?o.$route.query[t]:""},t.prev=1,t.next=4,o.$api(o.api.detail).method("GET").param({id:o.id}).call();case 4:if(l=t.sent,!l.status){t.next=10;break}for(n in o.columns)void 0!==l.data[o.columns[n].col]?"COLUMN_CHECKBOX"===o.columns[n].type||"COLUMN_PICTURES"===o.columns[n].type||"COLUMN_FILES"===o.columns[n].type||"COLUMN_CHOOSE"===o.columns[n].type||"COLUMN_TREE_CHECKBOX"===o.columns[n].type?o.form[o.columns[n].col]=JSON.parse(l.data[o.columns[n].col]):"COLUMN_TIMESTAMP"===o.columns[n].type?o.form[o.columns[n].col]=r()(l.data[o.columns[n].col],"YYYY-MM-DD HH:mm:ss"):o.form[o.columns[n].col]=l.data[o.columns[n].col]+"":"COLUMN_CHECKBOX"===o.columns[n].type||"COLUMN_PICTURES"===o.columns[n].type||"COLUMN_FILES"===o.columns[n].type?o.form[o.columns[n].col]=""!==e(o.columns[n].col)?JSON.parse(e(o.columns[n].col)):[]:"COLUMN_TIMESTAMP"===o.columns[n].type?o.form[o.columns[n].col]=""!==e(o.columns[n].col)?e(o.columns[n].col):r()():o.form[o.columns[n].col]=e(o.columns[n].col),"COLUMN_CHILDREN_CHOOSE"===o.columns[n].type&&(l.tip[o.columns[n].col]?o.formTip[o.columns[n].col]=l.tip[o.columns[n].col][o.columns[n].extra.displayColumn]:o.formTip[o.columns[n].col]="");o.formItemTip=l.tip,t.next=11;break;case 10:throw l.msg;case 11:t.next=16;break;case 13:t.prev=13,t.t0=t["catch"](1),o.$message.error(t.t0+"",5);case 16:case"end":return t.stop()}}),t,null,[[1,13]])})))()},submit:function(){var o=this;return Object(c["a"])(regeneratorRuntime.mark((function t(){var e,l,n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:for(l in e={},o.columns.map((function(t){if("COLUMN_DISPLAY"!==t.type)return"COLUMN_NUMBER_DIVIDE"===t.type?e[t.col]=parseFloat(o.form[t.col])*t.divide:void(e[t.col]=o.form[t.col])})),e)e[l]instanceof r.a&&(e[l]=e[l].format("YYYY-MM-DD HH:mm:ss")),e[l]instanceof Array&&(e[l]=JSON.stringify(e[l]));return t.prev=3,t.next=6,o.$api(o.api.save).method("POST").param(e).call();case 6:if(n=t.sent,!n.status){t.next=14;break}o.$message.success(n.data,5),o.reset(),o.$closePage(o.$route.path),o.$router.go(-1),t.next=15;break;case 14:throw n.msg;case 15:t.next=20;break;case 17:t.prev=17,t.t0=t["catch"](3),o.$message.error(t.t0+"",5);case 20:case"end":return t.stop()}}),t,null,[[3,17]])})))()}}},d=f,y=(e("6ed5"),e("2877")),C=Object(y["a"])(d,l,n,!1,null,"4e6ad572",null);t["default"]=C.exports},e130:function(o,t,e){}}]);