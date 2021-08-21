<template>
	<div>
		<div ref="editor"></div>
		<div style="display:none;">
			<input ref="inputUpload" type="file" @change="onFileChoose" accept="audio/*" />
		</div>
	</div>
</template>

<script>
	import E from 'wangeditor';
	export default {
		data() {
			return {
				editor: null,
				isInputChange: false
			}
		},
		props: {
			value: {
				type: String,
				required: true
			}
		},
		watch: {
			value() {
				if(this.isInputChange)
					this.isInputChange = false;
				else
					this.editor.txt.html(this.value);
			}
		},
		mounted() {
			this.editor = new E(this.$refs.editor);
			this.editor.config.onchange = (newHtml) => {
				if(newHtml != "")
					this.isInputChange = true;
				this.$emit("input", newHtml);
			};
			this.editor.config.customUploadImg = async (files, insert) => {
				for (var i in files) {
					var res = await this.$oss.upload(files[i])
					insert(res.url)
				}
				this.$message.success('上传成功')
			};
			this.editor.config.customUploadVideo = async (files, insert) => {
				for (var i in files) {
					var res = await this.$oss.upload(files[i])
					insert(res.url)
				}
				this.$message.success('上传成功')
			};
			var that = this;
			const { BtnMenu } = E;
			class MP3Menu extends BtnMenu {
				constructor(editor) {
					const $elem = E.$('<div class="w-e-menu" data-title="音频"><span>MP3</span></div>');
					super($elem, editor);
				}
				clickHandler() {
					that.$refs.inputUpload.click();
				}
				tryChangeActive() {
					this.active();
				}
			}
			E.registerMenu("mp3Key", MP3Menu)
			this.editor.create();
			this.editor.txt.html(this.value);
		},
		beforeDestroy() {
			this.editor.destroy()
			this.editor = null
		},
		methods:{
			async onFileChoose(){
				var res = await ossUpload(this.$refs.inputUpload.files[0])
				this.editor.cmd.do('insertHTML', '<audio src="' + res.url + '" controls />');
				this.$refs.inputUpload.value = "";
			}
		}
	}
</script>

<style>
	.w-e-menu {
		z-index: 2 !important;
	}

	.w-e-text-container {
		z-index: 1 !important;
	}

	.w-e-toolbar {
		z-index: 3 !important;
	}
</style>
