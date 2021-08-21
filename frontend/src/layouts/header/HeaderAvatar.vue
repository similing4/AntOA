<template>
	<a-dropdown>
		<div class="header-avatar" style="cursor: pointer">
			<img class="avatar" src="@/assets/img/logo.png" />
			<span class="name">后台用户</span>
		</div>
		<a-menu :class="['avatar-menu']" slot="overlay">
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
			}
		}
	}
</script>

<style lang="less">
	.header-avatar {
		display: inline-flex;
		height: 64px;
		display: flex;
		flex-direction: row;
		align-items: center;
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
	.avatar{
		width: 24px;
		height: 24px;
		border-radius: 12px;
		background-color: #BFBFBF;
	}
</style>
