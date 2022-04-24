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
	// webpack build externals
	externals: {
		vue: 'Vue',
		'vue-router': 'VueRouter',
		vuex: 'Vuex',
		nprogress: 'NProgress',
		'@antv/data-set': 'DataSet',
		'js-cookie': 'Cookies'
	},
	css: [],
	js: [
		'//cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.min.js',
		'//cdn.jsdelivr.net/npm/vue-router@3.3.4/dist/vue-router.min.js',
		'//cdn.jsdelivr.net/npm/vuex@3.4.0/dist/vuex.min.js',
		'//cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js',
		'//cdn.jsdelivr.net/npm/@antv/data-set@0.11.4/build/data-set.min.js',
		'//cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.min.js'
	]
}

module.exports = {
	devServer: {
		// proxy: {
		//   '/api': { //此处要与 /services/api.js 中的 API_PROXY_PREFIX 值保持一致
		//     target: process.env.VUE_APP_API_BASE_URL,
		//     changeOrigin: true,
		//     pathRewrite: {
		//       '^/api': ''
		//     }
		//   }
		// }
	},
	pluginOptions: {
		'style-resources-loader': {
			preProcessor: 'less',
			patterns: [path.resolve(__dirname, "./src/theme/theme.less")],
		}
	},
	configureWebpack: config => {
	    let moduleListConfigFile = path.resolve(__dirname, "../../../modules_statuses.json");
	    let moduleListExportFile = path.resolve(__dirname, "./external_module_js.js");
	    let modulesList = "";
    	let exportScripts = "let ret = {};\n";
	    if(fs.existsSync(moduleListConfigFile)){
    	    let modules = fs.readFileSync(moduleListConfigFile);
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
