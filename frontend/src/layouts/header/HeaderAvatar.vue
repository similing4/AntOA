<template>
	<a-dropdown>
		<div class="header-avatar" style="cursor: pointer">
			<a-avatar class="avatar" size="small" shape="circle" />
			<span class="name">后台用户</span>
		</div>
		<a-menu :class="['avatar-menu']" slot="overlay">
			<a-menu-item @click="goHome">
				<a-icon type="user" />
				<span>返回管理首页</span>
			</a-menu-item>
			<a-menu-item @click="goPasswordSetting">
				<a-icon type="setting" />
				<span>修改密码</span>
			</a-menu-item>
			<a-menu-divider />
			<a-menu-item @click="logout">
				<a-icon style="margin-right: 8px;" type="poweroff" />
				<span>退出登录</span>
			</a-menu-item>
		</a-menu>
	</a-dropdown>
</template>

<script>
	import {
		mapGetters
	} from 'vuex'

	export default {
		name: 'HeaderAvatar',
		computed: {
			...mapGetters('account', ['user']),
		},
		methods: {
			logout() {
				this.$api().removeLoginToken();
				this.$router.push('/login')
			},
			goHome(){
				this.$router.push('/home')
			},
			goPasswordSetting(){
				this.$router.push('/antoa/user/change_password')
			}
		}
	}
</script>

<style lang="less">
	.header-avatar {
		display: inline-flex;
		height: 64px;

		.avatar,
		.name {
			align-self: center;
		}

		.avatar {
			margin-right: 8px;
		}

		.name {
			font-weight: 500;
		}
	}

	.avatar-menu {
		width: 150px;
	}
</style>
