<template>
	<div class="new-page">
		<home-component></home-component>
	</div>
</template>
<script>
import Vue from "vue";
export default {
	data() {
		return {};
	},
	components: {
		"HomeComponent": async function(recv) {
			try {
				if (!localStorage.homeVueApi)
					throw "";
				var api = localStorage.homeVueApi;
				var res = await Vue.prototype.$api(api).method("GET").call();
				if (!res.status)
					throw res.msg;
				return recv(eval("(()=>{return " + res.data + "})();"));
			} catch (e) {
				console.log(e);
				return recv({
					data() {
						return {
							title: "后台管理系统首页",
						};
					},
					template: `<div>{{title}}</div>`
				});
			}
		}
	}
}
</script>
<style scoped lang="less">
.new-page {
	background-color: @base-bg-color;
	border-radius: 4px;
	min-height: 100vh;
}
</style>