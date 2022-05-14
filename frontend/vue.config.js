let fs = require('fs');
let path = require('path')
const webpack = require('webpack')
const ThemeColorReplacer = require('webpack-theme-color-replacer')
const {getThemeColors, modifyVars} = require('./src/utils/themeUtil')
const {resolveCss} = require('./src/utils/theme-color-replacer-extend')
const CompressionWebpackPlugin = require('compression-webpack-plugin')

const productionGzipExtensions = ['js', 'css']
const isProd = process.env.NODE_ENV === 'production'

const assetsCDN = {
	// 这些CDN容易挂掉，影响稳定性
	// webpack build externals
	externals: {/*
		vue: 'Vue',
		'vue-router': 'VueRouter',
		vuex: 'Vuex',
		nprogress: 'NProgress',
		'@antv/data-set': 'DataSet',
		'js-cookie': 'Cookies'*/
	},
	css: [],
	js: [/*
		'//cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.min.js',
		'//cdn.jsdelivr.net/npm/vue-router@3.3.4/dist/vue-router.min.js',
		'//cdn.jsdelivr.net/npm/vuex@3.4.0/dist/vuex.min.js',
		'//cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js',
		'//cdn.jsdelivr.net/npm/@antv/data-set@0.11.4/build/data-set.min.js',
		'//cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.min.js'*/
	]
}

module.exports = {
	devServer: {
	},
	pluginOptions: {
		'style-resources-loader': {
			preProcessor: 'less',
			patterns: [path.resolve(__dirname, "./src/theme/theme.less")],
		}
	},
	configureWebpack: config => {
	    let homeVueFile = path.resolve(__dirname, "../../../AntOAHome.vue");
	    let homeImportFile = path.resolve(__dirname, "./src/pages/home/Home.js");
	    let moduleListConfigFile = path.resolve(__dirname, "../../../modules_statuses.json");
	    let moduleListExportFile = path.resolve(__dirname, "./external_module_js.js");
	    let modulesList = "";
    	let exportScripts = "let ret = {};\n";
    	let modules = [];
    	if(!fs.existsSync(homeVueFile))
    	    fs.writeFileSync(homeImportFile, "export default import('@/pages/home/Home.vue');");
    	else
    	    fs.writeFileSync(homeImportFile, "export default import('@/../../../../AntOAHome.vue');");
	    if(fs.existsSync(moduleListConfigFile)){
    	    modules = fs.readFileSync(moduleListConfigFile);
    	    modules = JSON.parse(modules);
    	    modules = Object.keys(modules).filter((k)=>modules[k] && k != "AntOA");
    	    modulesList = modules.map((t)=>{
    	        let moduleJsFilePath = path.resolve(__dirname, "../../" + t + "/antoa_plugin.js");
    	        if(!fs.existsSync(moduleJsFilePath))
    	            return "";
    	        exportScripts += "ret['" + t + "'] = " + t + ";\n";
    	        return "import " + t + " from '../../" + t + "/antoa_plugin.js';";
    	    }).join("\n");
	    }
    	modulesList += "\n" + exportScripts + "\nexport default ret;";
    	fs.writeFileSync(moduleListExportFile, modulesList);
    	
	    let ListFilterPluginGeneralFile = path.resolve(__dirname, "./src/pages/common/components/ListFilterPluginGeneral.vue");
	    let ListTableColumnPluginGeneralFile = path.resolve(__dirname, "./src/pages/common/components/ListTableColumnPluginGeneral.vue");
	    let CreateColumnPluginGeneralFile = path.resolve(__dirname, "./src/pages/common/components/CreateColumnPluginGeneral.vue");
	    let EditColumnPluginGeneralFile = path.resolve(__dirname, "./src/pages/common/components/EditColumnPluginGeneral.vue");
	    
	    let ListFilterPluginGeneralFileComponentsList = [];
	    let ListTableColumnPluginGeneralFileComponentsList = [];
	    let CreateColumnPluginGeneralFileComponentsList = [];
	    let EditColumnPluginGeneralFileComponentsList = [];
    	
    	for(let i = 0;i < modules.length;i ++){
    	    let moduleName = modules[i];
    	    let pluginListFilterPath = path.resolve(__dirname, "../../" + moduleName + "/antoa_components/PluginListFilter");
    	    let pluginListTableColumnPath = path.resolve(__dirname, "../../" + moduleName + "/antoa_components/PluginListTableColumn");
    	    let pluginCreateColumnPath = path.resolve(__dirname, "../../" + moduleName + "/antoa_components/PluginCreateColumn");
    	    let pluginEditColumnPath = path.resolve(__dirname, "../../" + moduleName + "/antoa_components/PluginEditColumn");
    	    if(fs.existsSync(pluginListFilterPath)){
    	        ListFilterPluginGeneralFileComponentsList = ListFilterPluginGeneralFileComponentsList.concat(fs.readdirSync(pluginListFilterPath).filter((t)=>{
    	            return fs.lstatSync(pluginListFilterPath + "/" + t).isFile() && t.endsWith(".vue") && t.startsWith("PluginListFilter");
    	        }).map((t)=>{
    	            return {
    	                name: t.substring(0, t.length - 4),
    	                path: "../../../../../../" + moduleName + "/antoa_components/PluginListFilter/" + t
    	            };
    	        }));
    	    }
    	    if(fs.existsSync(pluginListTableColumnPath)){
    	        ListTableColumnPluginGeneralFileComponentsList = ListTableColumnPluginGeneralFileComponentsList.concat(fs.readdirSync(pluginListTableColumnPath).filter((t)=>{
    	            return fs.lstatSync(pluginListTableColumnPath + "/" + t).isFile() && t.endsWith(".vue") && t.startsWith("PluginListTableColumn");
    	        }).map((t)=>{
    	            return {
    	                name: t.substring(0, t.length - 4),
    	                path: "../../../../../../" + moduleName + "/antoa_components/PluginListTableColumn/" + t
    	            };
    	        }));
    	    }
    	    if(fs.existsSync(pluginCreateColumnPath)){
    	        CreateColumnPluginGeneralFileComponentsList = CreateColumnPluginGeneralFileComponentsList.concat(fs.readdirSync(pluginCreateColumnPath).filter((t)=>{
    	            return fs.lstatSync(pluginCreateColumnPath + "/" + t).isFile() && t.endsWith(".vue") && t.startsWith("PluginCreateColumn");
    	        }).map((t)=>{
    	            return {
    	                name: t.substring(0, t.length - 4),
    	                path: "../../../../../../" + moduleName + "/antoa_components/PluginCreateColumn/" + t
    	            };
    	        }));
    	    }
    	    if(fs.existsSync(pluginEditColumnPath)){
    	        EditColumnPluginGeneralFileComponentsList = EditColumnPluginGeneralFileComponentsList.concat(fs.readdirSync(pluginEditColumnPath).filter((t)=>{
    	            return fs.lstatSync(pluginEditColumnPath + "/" + t).isFile() && t.endsWith(".vue") && t.startsWith("PluginEditColumn");
    	        }).map((t)=>{
    	            return {
    	                name: t.substring(0, t.length - 4),
    	                path: "../../../../../../" + moduleName + "/antoa_components/PluginEditColumn/" + t
    	            };
    	        }));
    	    }
    	}
    	
    	fs.writeFileSync(ListFilterPluginGeneralFile, `<template>` + ListFilterPluginGeneralFileComponentsList.map((t)=>`<` + t.name + ` v-if="item.type == '` + t.name + `'" :item="item" :value="value" @input="$emit('input',$event)" />`).join("") + `</template><script>` + ListFilterPluginGeneralFileComponentsList.map((t)=>`import ` + t.name + ` from "` + t.path + `";`).join("") + `export default{props:["item","value"],data(){return {};},components:{` + ListFilterPluginGeneralFileComponentsList.map((t)=> t.name + `,`).join("") + `}}</script>`);
    	fs.writeFileSync(ListTableColumnPluginGeneralFile, `<template>` + ListTableColumnPluginGeneralFileComponentsList.map((t)=>`<` + t.name + ` v-if="item.type == '` + t.name + `'" :item="item" :value="value" @input="$emit('input',$event)" />`).join("") + `</template><script>` + ListTableColumnPluginGeneralFileComponentsList.map((t)=>`import ` + t.name + ` from "` + t.path + `";`).join("") + `export default{props:["item","value"],data(){return {};},components:{` + ListTableColumnPluginGeneralFileComponentsList.map((t)=> t.name + `,`).join("") + `}}</script>`);
    	fs.writeFileSync(CreateColumnPluginGeneralFile, `<template>` + CreateColumnPluginGeneralFileComponentsList.map((t)=>`<` + t.name + ` v-if="item.type == '` + t.name + `'" :column="column" :gridApiObject="gridApiObject" :value="value" @input="$emit('input',$event)"><slot /></` + t.name + `>`).join("") + `</template><script>` + CreateColumnPluginGeneralFileComponentsList.map((t)=>`import ` + t.name + ` from "` + t.path + `";`).join("") + `export default{props:["column","gridApiObject","value"],data(){return {};},components:{` + CreateColumnPluginGeneralFileComponentsList.map((t)=> t.name + `,`).join("") + `}}</script>`);
    	fs.writeFileSync(EditColumnPluginGeneralFile, `<template>` + EditColumnPluginGeneralFileComponentsList.map((t)=>`<` + t.name + ` v-if="item.type == '` + t.name + `'" :column="column" :gridApiObject="gridApiObject" :value="value" @input="$emit('input',$event)"><slot /></` + t.name + `>`).join("") + `</template><script>` + EditColumnPluginGeneralFileComponentsList.map((t)=>`import ` + t.name + ` from "` + t.path + `";`).join("") + `export default{props:["column","gridApiObject","value"],data(){return {};},components:{` + EditColumnPluginGeneralFileComponentsList.map((t)=> t.name + `,`).join("") + `}}</script>`);
    	
		config.entry.app = ["babel-polyfill", "whatwg-fetch", "./src/main.js"];
		config.performance = {
			hints: false
		}
		config.plugins.push(
			new ThemeColorReplacer({
				fileName: 'css/theme-colors-[contenthash:8].css',
				matchColors: getThemeColors(),
				injectCss: true,
				resolveCss
			})
		)
		// Ignore all locale files of moment.js
		config.plugins.push(new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/))
		// 生产环境下将资源压缩成gzip格式
		if (isProd) {
			// add `CompressionWebpack` plugin to webpack plugins
			config.plugins.push(new CompressionWebpackPlugin({
				algorithm: 'gzip',
				test: new RegExp('\\.(' + productionGzipExtensions.join('|') + ')$'),
				threshold: 10240,
				minRatio: 0.8
			}))
		}
		// if prod, add externals
		if (isProd) {
			config.externals = assetsCDN.externals
		}
	},
	chainWebpack: config => {
		config.module.rule('pug')
			.test(/\.pug$/)
			.use('pug-html-loader')
			.loader('pug-html-loader')
			.end()
		// 生产环境下关闭css压缩的 colormin 项，因为此项优化与主题色替换功能冲突
		if (isProd) {
			config.plugin('optimize-css')
				.tap(args => {
					args[0].cssnanoOptions.preset[1].colormin = false
					return args
				})
		}
		// 生产环境下使用CDN
		if (isProd) {
			config.plugin('html')
				.tap(args => {
					args[0].cdn = assetsCDN
					return args
				})
		}
	},
	css: {
		loaderOptions: {
			less: {
				lessOptions: {
					modifyVars: modifyVars(),
					javascriptEnabled: true
				}
			}
		}
	},
	runtimeCompiler: true,
	publicPath: process.env.VUE_APP_PUBLIC_PATH,
	outputDir: path.resolve(__dirname, '../../../public/antoa/webpack'),
	assetsDir: 'static',
	productionSourceMap: false
}
