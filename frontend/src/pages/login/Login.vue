<template>
	<div>
		<div class="content">
			<div class="top">
				<div class="header">
					<span class="title">后台管理系统</span>
				</div>
				<div class="desc">后台管理系统登录</div>
			</div>
			<div class="login">
				<a-form @submit="onSubmit" :form="form">
					<a-form-item>
						<a-input autocomplete="autocomplete" name="username" size="large"
							:placeholder="'请输入' + username_placeholder" v-model="username">
							<a-icon slot="prefix" type="user" />
						</a-input>
					</a-form-item>
					<a-form-item>
						<a-input size="large" name="password" :placeholder="'请输入' + password_placeholder"
							autocomplete="autocomplete" type="password" v-model="password">
							<a-icon slot="prefix" type="lock" />
						</a-input>
					</a-form-item>
					<div>
						<a-checkbox v-model="isAutoLogin">记住密码</a-checkbox>
						<a style="float: right" @click="clearLoginStorage">清除记录</a>
					</div>
					<a-form-item>
						<a-button :loading="logging" class="submitbtn" style="width: 100%;margin-top: 24px" size="large"
							html-type="submit" type="primary">登录
						</a-button>
					</a-form-item>
				</a-form>
			</div>
			<PageFooter></PageFooter>
		</div>
	</div>
</template>

<script>
	import PageFooter from "../../components/PageFooter";
	import {
		loadRoutes
	} from '@/utils/routerUtil';

	export default {
		name: 'Login',
		data() {
			return {
				logging: false,
				error: '',
				form: this.$form.createForm(this),
				isAutoLogin: false,
				actionUrl: "/api/antoa/auth/login",
				username_placeholder: "请输入用户名",
				password_placeholder: "请输入密码",
				username: "",
				password: ""
			}
		},
		components: {
			PageFooter
		},
		async created() {
			try {
				if (!this.$api().checkAuthorization())
					throw "登录失败";
				const res = await this.$api("/api/antoa/auth/auth")
					.method("POST")
					.call();
				if (!res.status)
					throw "登录失效";
				this.goHome();
			} catch (e) {
				console.log(e);
			}
		},
		mounted() {
			try {
				let usernameAndPassword = localStorage.usernameAndPassword;
				if (!usernameAndPassword)
					throw "没有发现本地存储的用户名密码";
				usernameAndPassword = JSON.parse(usernameAndPassword);
				this.username = usernameAndPassword.username;
				this.password = usernameAndPassword.password;
			} catch (e) {
				console.log(e);
			}
		},
		methods: {
			async onSubmit(e) {
				e.preventDefault();
				try {
					this.form.validateFields((err) => {
						if (!err) {
							this.logging = true;
							return true;
						}
					})
					if (this.isAutoLogin)
						localStorage.usernameAndPassword = JSON.stringify({
							username: this.username,
							password: this.password
						});
					const res = await this.$api(this.actionUrl)
						.param({
							username: this.username,
							password: this.password
						})
						.method("POST")
						.call();
					if (!res.status)
						throw res.msg;
					this.$api().setLoginToken(res.data);
					this.logging = false;
					this.goHome();
				} catch (e) {
					this.$message.error(e + "");
					this.logging = false;
				}
			},
			onClose() {
				this.error = false
			},
			clearLoginStorage() {
				delete localStorage.usernameAndPassword;
			},
			async goHome() {
				try {
					var e = await this.$api("/api/antoa/auth/config").method("GET").call();
					if (!e.status)
						throw e.msg;
					loadRoutes(e.routes);
					for(var i in e.routes[0].children){
						if(e.routes[0].children[i].meta && e.routes[0].children[i].meta.is_home)
							localStorage.homeVueApi = e.routes[0].children[i].meta.vue_api;
					}
					this.$router.push("/home");
				} catch (e) {
					this.$message.error(e + "", 5)
				}
			}
		}
	}
</script>

<style lang="less" scoped>
	.content {
		display: flex;
		flex-direction: column;
		height: 100vh;
		overflow: auto;
		background-color: #f0f2f5;
		background-image: url('https://gw.alipayobjects.com/zos/rmsportal/TVYTbAXWheQpRcWDaDMu.svg');
		background-repeat: no-repeat;
		background-position-x: center;
		background-position-y: 110px;
		background-size: 100%;
		padding-top: 15vh;

		.top {
			text-align: center;

			.header {
				height: 44px;
				line-height: 44px;

				a {
					text-decoration: none;
				}

				.logo {
					height: 44px;
					vertical-align: top;
					margin-right: 16px;
				}

				.title {
					font-size: 33px;
					color: @title-color;
					font-family: 'Myriad Pro', 'Helvetica Neue', Arial, Helvetica, sans-serif;
					font-weight: 600;
					position: relative;
					top: 2px;
				}
			}

			.desc {
				font-size: 14px;
				color: @text-color-second;
				margin-top: 12px;
				margin-bottom: 40px;
			}
		}

		.login {
			width: 368px;
			margin: 0 auto;

			@media screen and (max-width: 576px) {
				width: 95%;
			}

			@media screen and (max-width: 320px) {
				.captcha-button {
					font-size: 14px;
				}
			}

			.icon {
				font-size: 24px;
				color: @text-color-second;
				margin-left: 16px;
				vertical-align: middle;
				cursor: pointer;
				transition: color 0.3s;

				&:hover {
					color: @primary-color;
				}
			}
		}
	}
</style>
