<template>
	<div>
		<a-button style="margin-right:15px" @click="onCreateClick" type="primary" v-if="gridListObject.hasCreate">创建</a-button>
		<a-button style="margin-right:15px" @click="onHeaderButtonClick(headerButton, index)" :type="headerButton.buttonType" v-for="(headerButton,index) in gridListObject.listHeaderButtonCollection" :key="index">
			{{headerButton.buttonText }}
		</a-button>
		<a-modal v-model="richHtmlModal.isShow" @ok="richHtmlModal.isShow = false">
			<div v-html="richHtmlModal.html"></div>
		</a-modal>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
		<div v-for="(headerButton,index) in gridListObject.listHeaderButtonCollection" :key="index + '_b'">
			<a-modal :visible="!!isShowCreateModal[index]" @ok="doSubmit(index, headerButton)" @cancel="isShowCreateModal[index] = false;$forceUpdate()" v-if="headerButton.type === 'ListHeaderButtonWithForm'">
				<easy-create-form-modal :grid-create-object="headerButton.gridCreateForm" :grid-api-object="gridApiObject" :index="index" :ref="'modal_' + index" type="easy_header"></easy-create-form-modal>
			</a-modal>
		</div>
	</div>
</template>
<script>
import confirmDialog from "@/components/tool/ConfirmDialog.vue";
import EasyCreateFormModal from "./EasyCreateFormModal.vue";
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
		},
		gridApiObject: {
			type: Object,
			default () {
				return {
					api_column_change: "",
					create: "",
					create_page: "",
					delete: "",
					detail: "",
					detail_column_list: "",
					edit_page: "",
					list: "",
					list_page: "",
					path: "",
					save: ""
				}
			}
		},
        selectedRows: {
		    type: Array,
            default(){
		        return [];
            }
        },
		tableModel: {
			type: Object,
			default(){
				return {};
			}
		}
	},
	data() {
		return {
			richHtmlModal: {
				isShow: false,
				html: ""
			},
			isShowCreateModal: []
		};
	},
	components: {
		confirmDialog,
		EasyCreateFormModal
	},
	methods: {
		loadPage() {
			this.$emit("loadpage");
		},
		async onHeaderButtonClick(headerButtonItem, index) {
			let param = this.$route.query;
			param.antoa_row_selected = this.selectedRows;
			param.search_obj = {};
			Object.assign(param.search_obj, this.tableModel.searchObj, {
				page: this.tableModel.pagination.current
			});
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
			} else if (headerButtonItem.type === "ListHeaderButtonWithForm") {
				this.isShowCreateModal[index] = true;
				this.$forceUpdate();
				this.$nextTick(()=>{
					this.$refs['modal_' + index][0].reset()
				})
			}
		},
		onCreateClick() {
			let param = this.$route.query;
			let params = [];
			for (let i in param)
				params.push(i + "=" + param[i]);
			this.$emit('openurl', this.gridApiObject.create_page + "?" + params.join("&"));
		},
		doSubmit(index, headerButton){
			let search_obj = {};
			Object.assign(search_obj, this.tableModel.searchObj, {
				page: this.tableModel.pagination.current
			});
			this.$refs['modal_' + index][0].submit(async (param)=>{
				let res = await this.$api(headerButton.baseUrl).method("POST").param({
					query: param,
					antoa_row_selected: this.selectedRows,
					search_obj
				}).call();
				if (!res.status)
					this.$message.error(res.msg);
				else{
					this.$message.success(res.data);
					this.isShowCreateModal[index] = false;
				}
				this.loadPage();
				this.$forceUpdate();
			});
		}
	}
}
</script>
