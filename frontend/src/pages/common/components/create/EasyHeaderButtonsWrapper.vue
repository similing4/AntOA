<template>
	<div>
		<a-button style="margin-right:15px" @click="onHeaderButtonClick(headerButton)" :type="headerButton.buttonType" v-for="(headerButton,index) in gridListObject.listHeaderButtonCollection" :key="index">
			{{headerButton.buttonText }}
		</a-button>
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
		gridListObject: {
			type: Object,
			default () {
				return {
					"primaryKey": "id",
					"listFilterCollection": [], //{"type": "ListFilterText","col": "name","tip": "比赛名称"}
					"listTableColumnCollection": [], //{"type": "ListTableColumnText","col": "id","tip": "ID"}
					"listHeaderButtonCollection": [],
					"listRowButtonCollection": [], //{"type": "ListRowButtonNavigate","buttonText": "报名记录","buttonType": "primary","baseUrl": "\/race\/register\/list","finalUrl": null}
					"hasCreate": false,
					"hasEdit": false,
					"hasDelete": false
				}
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
		loadPage() {
			this.$emit("loadpage");
		},
		async onHeaderButtonClick(headerButtonItem) {
			let param = this.$route.query;
			if (headerButtonItem.type === "ListHeaderButtonApi") {
				let res = await this.$api(headerButtonItem.finalUrl).method("POST").param(param).call();
				if (!res.status)
					this.$message.error(res.msg);
				else
					this.$message.success(res.data);
				this.loadPage();
			} else if (headerButtonItem.type === "ListHeaderButtonApiWithConfirm") {
				this.$refs.confirmDialog.confirm("确认要这样做么？").then(async () => {
					let res = await this.$api(headerButtonItem.finalUrl).method("POST").param(param).call();
					if (!res.status)
						this.$message.error(res.msg);
					else
						this.$message.success(res.data);
					this.loadPage();
				});
			} else if (headerButtonItem.type === "ListHeaderButtonNavigate") {
				this.$emit('openurl', headerButtonItem.finalUrl);
			} else if (headerButtonItem.type === "ListHeaderButtonRichText") {
				const html = await this.$api(headerButtonItem.finalUrl).method("POST").param(param).call();
				if (!html.status)
					return this.$message.error(html.msg);
				this.richHtmlModal.html = html.data;
				this.richHtmlModal.isShow = true;
			} else if (headerButtonItem.type === "ListHeaderButtonBlob") {
				try {
					const blob = await this.$api(headerButtonItem.finalUrl).method("POST").param(param).setBlob(true)
						.call();
					var downloadElement = document.createElement("a");
					var href = window.URL.createObjectURL(blob);
					downloadElement.href = href;
					downloadElement.download = headerButtonItem.downloadFilename;
					document.body.appendChild(downloadElement);
					downloadElement.click();
					document.body.removeChild(downloadElement);
					window.URL.revokeObjectURL(href);
				} catch (e) {
					this.$message.error("文件导出时发生了错误：" + e, 5);
				}
			}
		}
	}
}
</script>