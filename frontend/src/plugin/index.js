import TabsPagePlugin from './tabs-page-plugin'
import ApiPlugin from './api-plugin';
import OssUploadPlugin from './oss-upload-plugin';

const Plugins = {
	install: function (Vue) {
		Vue.use(TabsPagePlugin)
		Vue.use(ApiPlugin)
		Vue.use(OssUploadPlugin)
	}
}
export default Plugins
