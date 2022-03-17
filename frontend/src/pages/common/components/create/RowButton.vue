<template>
	<div>
		<a-button :type="button.buttonType" @click="onRowButtonClick">{{button.buttonText}}</a-button>
		<a-modal v-model="richHtmlModal.isShow" @ok="richHtmlModal.isShow = false">
			<div v-html="richHtmlModal.html"></div>
		</a-modal>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
	</div>
</template>
<script>
import confirmDialog from "@/components/tool/ConfirmDialog.vue";
export default {
	props: {
		button: {
			type: Object,
			default () {
				return {
					"bindCol": "",
					"apiUrl": "",
					"buttonText": "",
					"buttonType": ""
				}
			}
		},
		form: {
			type: Object,
			default () {
				return {};
			}
		}
	},
	data() {
		return {
			richHtmlModal: {
				isShow: false,
				html: ""
			}
		};
	},
	components: {
		confirmDialog
	},
	methods: {
		async callApi() {
			let res = await this.$api(this.button.apiUrl).method("POST").param({
				query: this.$route.query,
				form: this.form
			}).call();
			if (!res.status)
				throw res.msg;
			return res;
		},
		openurl(url) {
			if (url.startsWith("http"))
				return window.open(url);
			let beforePage = localStorage.beforePage;
			if (!beforePage)
				beforePage = "[]";
			beforePage = JSON.parse(beforePage);
			beforePage = beforePage.filter((item) => {
				return item.after != url;
			});
			beforePage.push({
				before: this.$route.fullPath,
				after: url
			});
			localStorage.beforePage = JSON.stringify(beforePage);
			this.$router.push(url);
		},
		async onRowButtonClick() {
			try {
				let param = {
					query: this.$route.query,
					row: record
				};
				if (rowButtonItem.type === "EditRowButtonApi") {
					let res = await this.callApi();
					this.$emit("formchange", res);
					if (res.msg)
						this.$message.success(res.msg);
				} else if (rowButtonItem.type === "EditRowButtonApiWithConfirm") {
					this.$refs.confirmDialog.confirm("确认要这样做么？").then(async () => {
						let res = await this.callApi();
						this.$emit("formchange", res);
						if (res.msg)
							this.$message.success(res.msg);
					});
				} else if (rowButtonItem.type === "EditRowButtonNavigate") {
					this.$emit('openurl', this.button.apiUrl);
				} else if (rowButtonItem.type === "EditRowButtonRichText") {
					const html = await this.$api(this.button.apiUrl).method("POST").param(param).call();
					if (!html.status)
						return this.$message.error(html.msg);
					this.richHtmlModal.html = html.data;
					this.richHtmlModal.isShow = true;
				} else if (rowButtonItem.type === "EditRowButtonBlob") {
					try {
						const blob = await this.$api(finalUrl).method("POST").param(param).setBlob(true).call();
						var downloadElement = document.createElement("a");
						var href = window.URL.createObjectURL(blob);
						downloadElement.href = href;
						downloadElement.download = rowButtonItem.downloadFilename;
						document.body.appendChild(downloadElement);
						downloadElement.click();
						document.body.removeChild(downloadElement);
						window.URL.revokeObjectURL(href);
					} catch (e) {
						this.$message.error("文件导出时发生了错误：" + e, 5);
					}
				}
			} catch (e) {
				this.$message.error(e + "", 5);
			}
		}
	}
}
</script>
<style scoped></style>