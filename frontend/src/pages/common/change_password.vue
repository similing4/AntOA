<template>
	<a-card>
		<a-form>
			<a-form-item label="新密码" :label-col="{span: 7}" :wrapper-col="{span: 10}">
				<a-input-password placeholder="请输入新密码" v-model="password"></a-input-password>
			</a-form-item>
			<a-form-item label="再次输入新密码" :label-col="{span: 7}" :wrapper-col="{span: 10}">
				<a-input-password placeholder="请再次输入新密码" v-model="password2"></a-input-password>
			</a-form-item>
			<a-form-item style="display: flex;justify-content: center;">
				<a-button type="primary" @click="submit">修改</a-button>
			</a-form-item>
		</a-form>
	</a-card>
</template>

<script>
	export default {
		data() {
			return {
				password: "",
				password2: ""
			};
		},
		methods: {
			async submit() {
				try {
					let password = this.password;
					if (password !== this.password2)
						throw "两次密码输入不一致";
					let res = await this.$api("/api/antoa/user/change_password").method("POST").param({
						password: password
					}).call();
					if (res.status) {
						this.$message.success(res.data, 5);
						this.password = "";
						this.password2 = "";
					} else
						throw res.msg;
				} catch (e) {
					this.$message.error(e + "", 5);
				}
			}
		}
	};
</script>

<style>
</style>
