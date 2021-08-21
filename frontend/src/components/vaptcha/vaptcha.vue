<template>
	<div ref="vaptcha" style="width:100%;height:36px">
		<div class="vaptcha-init-main">
			<div class="vaptcha-init-loading">
				<a href="https://www.vaptcha.com/" target="_blank"><img src="https://cdn.vaptcha.com/vaptcha-loading.gif" /></a>
				<span class="vaptcha-text">VAPTCHA启动中...</span>
			</div>
		</div>
	</div>
</template>

<script>
	const extend = function(to, _from) {
		for (const key in _from) {
			to[key] = _from[key]
		}
		return to
	}

	export default {
		props: {
			type: {
				type: String,
				default: 'click'
			},
			vid: {
				type: String,
				default: ''
			},
			value: {
				type: String,
				default: ""
			}
		},
		data() {
			return {
				vaptchaObj: null
			};
		},
		mounted() {
			var config = extend({
				container: this.$refs.vaptcha,
				offline_server: ""
			}, this.$props)
			this.loadV2Script().then(() => {
				window.vaptcha(config).then(vaptchaObj => {
					this.vaptchaObj = vaptchaObj;
					vaptchaObj.render();
					vaptchaObj.listen("pass", () => {
						this.$emit('input', vaptchaObj.getToken());
					});
					vaptchaObj.listen("close", () => {});
				})
			})
		},
		methods: {
			loadV2Script() {
				if (typeof window.vaptcha === 'function') { //如果已经加载就直接放回
					return Promise.resolve();
				} else {
					return new Promise(resolve => {
						var script = document.createElement('script');
						script.src = 'https://v.vaptcha.com/v3.js';
						script.async = true;
						script.onload = script.onreadystatechange = function() {
							if (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete') {
								resolve();
								script.onload = script.onreadystatechange = null;
							}
						}
						document.getElementsByTagName("head")[0].appendChild(script);
					})
				}
			}
		}
	}
</script>

<style scoped="scoped">
	.vaptcha-init-main {
		display: table;
		width: 100%;
		height: 100%;
		background-color: #EEEEEE;
	}

	.vaptcha-init-loading {
		display: table-cell;
		vertical-align: middle;
		text-align: center
	}

	.vaptcha-init-loading>a {
		display: inline-block;
		width: 18px;
		height: 18px;
	}

	.vaptcha-init-loading>a img {
		vertical-align: middle
	}

	.vaptcha-init-loading .vaptcha-text {
		font-family: sans-serif;
		font-size: 12px;
		color: #CCCCCC;
		vertical-align: middle
	}
</style>
