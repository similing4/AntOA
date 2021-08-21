<!DOCTYPE html>
<html lang="zh-cn" class="beauty-scroll">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width,initial-scale=1"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>后台管理系统</title>
	<link rel="stylesheet" href="{{ asset('/antoa/assets/beauty-scroll.css') }}"/>
	<link rel="stylesheet" href="{{ asset('/antoa/assets/antd.min.css') }}"/>
	{{--    <script src="{{ asset('/antoa/assets/vue.min.js') }}"></script>--}}
	<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
	<script src="{{ asset('/antoa/assets/moment-with-locales.min.js') }}"></script>
	<script src="{{ asset('/antoa/assets/antd-with-locales.min.js') }}"></script>
	<script src="{{ asset('/antoa/assets/axios.min.js') }}"></script>
	<script src="{{ asset('/antoa/assets/api_caller.js') }}"></script>
	<script src="{{ asset('/antoa/assets/components/page-footer.js') }}"></script>
	<script src="{{ asset('/antoa/assets/qiniu.min.js') }}"></script>
	@yield('head')
</head>
<body>
<div class="appMain">
	<div class="home-sidemenu beauty-scroll" :style="{width: is_side_menu_open?'256px':'80px'}" id="appMenu">
		<div class="logo">
			<h1 v-if="is_side_menu_open">后台管理系统</h1>
		</div>
		<div class="menu">
			<a-menu :default-selected-keys="activeId" :open-keys.sync="openKeys" mode="inline" theme="dark"
					:inline-collapsed="!is_side_menu_open">
				<template v-for="menuItem in menuList" v-if="menuItem.visible !== false">
					<a-menu-item :key="menuItem.id"
								 v-if="menuItem.children.filter((t)=>{return t.visible !== false;}).length === 0"
								 @click="goUrl(menuItem.uri,[menuItem.id])">
						<a-icon type="pie-chart"></a-icon>
						<span>@{{menuItem.title}}</span>
					</a-menu-item>
					<a-sub-menu :key="menuItem.id"
								v-if="menuItem.children.filter((t)=>{return t.visible !== false;}).length > 0">
                			<span slot="title">
                				<a-icon type="appstore"></a-icon>
                				<span>@{{menuItem.title}}</span>
                			</span>
						<a-menu-item :key="menuItemChildItem.id" v-for="menuItemChildItem in menuItem.children"
									 v-if="menuItemChildItem.visible !== false"
									 @click="goUrl(menuItemChildItem.uri,[menuItem.id,menuItemChildItem.id])">
							@{{menuItemChildItem.title}}
						</a-menu-item>
					</a-sub-menu>
				</template>
			</a-menu>
		</div>
	</div>
	<div class="home-right-wrapper beauty-scroll">
		<div id="appNavHeader">
			<div class="home-header">
				<div class="menu-toggle-btn">
					<a-icon type="menu-fold" v-if="window.appMenuVue.is_side_menu_open"
							@click="window.appMenuVue.is_side_menu_open = false"></a-icon>
					<a-icon type="menu-unfold" v-if="!window.appMenuVue.is_side_menu_open"
							@click="window.appMenuVue.is_side_menu_open = true"></a-icon>
				</div>
				<div class="menu-header-icon-avatar">
					<a-dropdown>
						<div class="header-avatar" style="cursor: pointer">
							<a-avatar class="avatar" size="small" shape="circle" :src="user.avatar"></a-avatar>
							<div class="name">@{{user.username}}</div>
						</div>
						<a-menu :class="['avatar-menu']" slot="overlay">
							<a-menu-item @click="goUrl(menuList[0].uri)">
								<a-icon type="user"></a-icon>
								<span>返回管理首页</span>
							</a-menu-item>
							<a-menu-divider></a-menu-divider>
							<a-menu-item @click="goUrl('/antoa/auth/logout?token=' + token)">
								<a-icon style="margin-right: 8px;" type="poweroff"></a-icon>
								<span>退出登录</span>
							</a-menu-item>
						</a-menu>
					</a-dropdown>
				</div>
			</div>
			<div class="home-main-breadcrumb" v-if="breadcrumb.length > 0 || breadcrumbDefault.length > 0">
				<div class="breadcrumb-wrapper">
					<a-breadcrumb v-if="breadcrumb.length > 0">
						<a-breadcrumb-item>
							<span>后台管理系统</span>
						</a-breadcrumb-item>
						<a-breadcrumb-item :key="index" v-for="(item, index) in breadcrumb">
							<span>@{{ item }}</span>
						</a-breadcrumb-item>
					</a-breadcrumb>
				</div>
				<div class="breadcrumb-detail">
					@{{ breadcrumbTitle }}
				</div>
			</div>
		</div>
		<div class="home-main-content">
			@yield('content')
		</div>
		<div class="home-footer" id="appNavFooter">
			<page-footer></page-footer>
		</div>
	</div>
</div>
<script>
	const fillMenuId = function (menu) {
		let id = 1;
		for (let i in menu) {
			menu[i].id = id++;
			if (!menu[i].uri)
				menu[i].uri = "";
			if (!menu[i].children)
				menu[i].children = [];
			if (!menu[i].breadcrumbTitle)
				menu[i].breadcrumbTitle = menu[i].title;
			for (let j in menu[i].children) {
				menu[i].children[j].id = id++;
				if (!menu[i].children[j].uri)
					menu[i].children[j].uri = "";
				if (!menu[i].children[j].breadcrumbTitle)
					menu[i].children[j].breadcrumbTitle = menu[i].children[j].title;
				if (!menu[i].children[j].children)
					menu[i].children[j].children = [];
				for (let k in menu[i].children[j].children)
					if (!menu[i].children[j].children[k].breadcrumbTitle)
						menu[i].children[j].children[k].breadcrumbTitle = menu[i].children[j].children[k].title;
			}
		}
		return menu;
	}
	const menuList = fillMenuId({!! json_encode(config('antoa.menu_routes')) !!});
	const currentRoute = "/{!! $api['path'] !!}";
	window.appMenuVue = new Vue({
		el: '#appMenu',
		data() {
			return {
				is_side_menu_open: true,
				menuList: menuList, //根据uri判断默认值
				openKeys: []
			}
		},
		computed: {
			activeId() {
				for (let i in this.menuList) {
					if (currentRoute === this.getPath(this.menuList[i].uri))
						return [this.menuList[i].id];
					for (let j in this.menuList[i].children) {
						if (currentRoute === this.getPath(this.menuList[i].children[j].uri))
							return [this.menuList[i].id, this.menuList[i].children[j].id];
						for (let k in this.menuList[i].children[j].children)
							if (currentRoute === this.getPath(this.menuList[i].children[j].children[k].uri))
								return [this.menuList[i].id, this.menuList[i].children[j].id, this.menuList[i].children[j].children[k].id];
					}
				}
				return [this.menuList[0].id];
			}
		},
		created() {
			this.checkAuth();
			document.addEventListener('visibilitychange', () => {
				if (document.visibilityState === 'visible')
					this.checkAuth();
			});
		},
		methods: {
			async checkAuth() {
				try {
					if (!localStorage.token)
						throw "登录失败";
					this.openKeys = (() => {
						const ret = this.activeId.slice(0);
						if (ret.length === 0)
							return [];
						ret.pop();
						return ret;
					})();
					const res = await this.$api("/api/antoa/auth/auth")
						.param({
							token: localStorage.token
						})
						.method("POST")
						.call();
					if (!res.status)
						throw "登录失效";
				} catch (e) {
					console.log(e);
					//location.href = "/antoa/auth/login";
				}
			},
			goUrl(url, storageId) {
				if (!url.startsWith("/"))
					url = "/" + url;
				location.href = url;
			},
			getPath(str) {
				if (str.includes("?"))
					return str.substring(0, str.indexOf("?"));
				return str;
			}
		}
	});
	window.appNavHeaderVue = new Vue({
		el: "#appNavHeader",
		data() {
			return {
				user: {
					avatar: "",
					username: "后台用户"
				},
				menuList: menuList, //根据uri判断默认值
				window: window,
				token: localStorage.token
			}
		},
		computed: {
			breadcrumb() {
				for (let i in this.menuList) {
					if (currentRoute === this.getPath(this.menuList[i].uri))
						return [this.menuList[i].title];
					for (let j in this.menuList[i].children) {
						if (currentRoute === this.getPath(this.menuList[i].children[j].uri))
							return [this.menuList[i].title, this.menuList[i].children[j].title];
						for (let k in this.menuList[i].children[j].children)
							if (currentRoute === this.getPath(this.menuList[i].children[j].children[k].uri))
								return [this.menuList[i].title, this.menuList[i].children[j].title, this.menuList[i].children[j].children[k].title];
					}
				}
				return [this.menuList[0].id];
			},
			breadcrumbTitle() {
				for (let i in this.menuList) {
					if (currentRoute === this.getPath(this.menuList[i].uri))
						return this.menuList[i].breadcrumbTitle;
					for (let j in this.menuList[i].children) {
						if (currentRoute === this.getPath(this.menuList[i].children[j].uri))
							return this.menuList[i].children[j].breadcrumbTitle;
						for (let k in this.menuList[i].children[j].children)
							if (currentRoute === this.getPath(this.menuList[i].children[j].children[k].uri))
								return this.menuList[i].children[j].children[k].breadcrumbTitle;
					}
				}
				return menuList[0].breadcrumbTitle;
			}
		},
		created() {
			document.title = this.breadcrumbTitle;
		},
		methods: {
			goUrl(url, storageId) {
				if (!url.startsWith("/"))
					url = "/" + url;
				if (storageId)
					localStorage.ADMINSIDEBAR_ID = JSON.stringify(storageId);
				location.href = url;
			},
			getPath(str) {
				if (str.includes("?"))
					return str.substring(0, str.indexOf("?"));
				return str;
			}
		}
	});
	window.appNavFooter = new Vue({
		el: "#appNavFooter"
	});
</script>
@yield('script')
<style>
	body {
		margin: 0;
		color: rgba(0, 0, 0, .65);
		font-size: 14px;
		font-family: -apple-system, BlinkMacSystemFont, Segoe UI, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Helvetica Neue, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
		font-variant: tabular-nums;
		line-height: 1.5;
		background-color: #fff;
	}

	.appMain {
		display: flex;
		flex-direction: row;
		width: 100vw;
		height: 100vh;
	}

	.ant-menu-dark {
		background: #032121;
	}

	.ant-menu.ant-menu-dark .ant-menu-item-selected {
		background-color: #13c2c2;
	}

	.home-sidemenu {
		width: 256px;
		height: 100%;
		background: #032121;
		overflow-y: scroll;
	}

	.home-sidemenu .logo {
		height: 64px;
		display: flex;
		justify-content: center;
		line-height: 64px;
		-webkit-transition: all .3s;
		transition: all .3s;
		overflow: hidden;
		background-color: #053434;
	}

	.home-sidemenu .logo h1 {
		color: #fefefe;
		font-size: 20px;
	}

	.home-sidemenu .menu {
		padding-top: 16px;
	}

	.home-right-wrapper {
		flex: 1;
		height: 100vh;
		overflow-y: scroll;
		background: #f0f2f5;
	}

	.home-right-wrapper .home-header {
		width: 100%;
		height: 64px;
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		padding-left: 24px;
		padding-right: 12px;
		z-index: 2;
		box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
		background: #fff;
	}

	.home-right-wrapper .home-header .menu-toggle-btn {
		font-size: 20px;
		line-height: 64px;
		padding: 0 24px;
		cursor: pointer;
		-webkit-transition: color .3s;
		transition: color .3s;
	}

	.home-right-wrapper .home-header .menu-header-icon-avatar {
		display: flex;
		flex-direction: row;
		align-items: center;
	}

	.header-avatar {
		display: inline-flex;
	}

	.header-avatar .avatar, .header-avatar .name {
		align-self: center;
	}

	.header-avatar .avatar {
		margin-right: 8px;
	}

	.header-avatar .name {
		font-weight: 500;
	}

	.avatar-menu {
		width: 150px;
	}

	.home-right-wrapper .home-main-breadcrumb {
		background: #fff;
		padding: 16px 24px;
		display: flex;
		flex-direction: column;
		margin-top: 45px;
	}

	.home-right-wrapper .home-main-breadcrumb .breadcrumb-wrapper {
		padding-bottom: 20px;
	}

	.home-right-wrapper .home-main-breadcrumb .breadcrumb-detail {
		font-size: 20px;
		color: rgba(0, 0, 0, .85);
		padding-bottom: 16px;
	}

	.home-right-wrapper .home-main-content {
		margin: 24px;
		background-color: #fff;
		border-radius: 4px;
		min-height: 100vh;
	}
</style>
</body>
</html>
