Vue.component("PageFooter",{
	name: 'PageFooter',
	data(){
	    return {
	        linkList: [{
    			link: 'https://iczer.gitee.io/vue-antd-admin-docs',
    			name: '项目首页'
    		},{
    			link: 'https://github.com/iczer/vue-antd-admin',
    			icon: 'github'
    		},{
    			link: 'https://www.shengxinyustudio.com',
    			name: '作者主页'
    		}],
	        copyright: "Vue Antd Admin 2021"
	    }
	},
	template: `<div class="and-admin-footer">
		<div class="links">
			<a target="_blank" :key="index" :href="item.link ? item.link : 'javascript: void(0)'"
				v-for="(item, index) in linkList">
				<a-icon v-if="item.icon" :type="item.icon" />{{item.name}}
			</a>
		</div>
		<div class="copyright">
			Copyright
			<a-icon type="copyright" />{{copyright}}
		</div>
	</div>`
});
document.write(`<style>
	.and-admin-footer {
    	padding:48px 16px 24px;
    	text-align:center;
    }
    .and-admin-footer .copyright {
    	color:rgba(0,0,0,0.45);
    	font-size:14px;
    }
    .and-admin-footer .copyright i {
    	margin:0 4px;
    }
    .and-admin-footer .links {
    	margin-bottom:8px;
    }
    .and-admin-footer .links a:not(:last-child) {
    	margin-right:40px;
    }
    .and-admin-footer .links a {
    	color:rgba(0,0,0,0.45);
    	-webkit-transition:all .3s;
    	transition:all .3s;
    }
</style>`);