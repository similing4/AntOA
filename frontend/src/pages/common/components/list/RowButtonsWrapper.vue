<template>
	<div>
		<a-button @click="onEditClick(record[gridListObject.primaryKey])" type="primary" v-if="gridListObject.hasEdit" style="margin: 5px;">编辑</a-button>
		<a-button @click="onDeleteClick(record[gridListObject.primaryKey])" type="danger" v-if="gridListObject.hasDelete" style="margin: 5px;">删除</a-button>
		<a-button @click="onRowButtonClick(rowButton, record, index)" :type="rowButton.buttonType" v-for="(rowButton,index) in gridListObject.listRowButtonCollection" :key="index + '_a'" v-if="record['BUTTON_CONDITION_DATA'][index]" style="margin: 5px;">
			{{ rowButton.buttonText }}
		</a-button>
		<a-modal v-model="richHtmlModal.isShow" @ok="richHtmlModal.isShow = false">
			<div v-html="richHtmlModal.html"></div>
		</a-modal>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
		<div v-for="(rowButton,index) in gridListObject.listRowButtonCollection" :key="index + '_b'">
			<a-modal :visible="!!isShowCreateModal[index]" @ok="doSubmit(index, rowButton, record)" @cancel="isShowCreateModal[index] = false;$forceUpdate()" v-if="record['BUTTON_CONDITION_DATA'][index] && rowButton.type === 'ListRowButtonWithForm'">
				<easy-create-form-modal :grid-create-object="rowButton.gridCreateForm" :grid-api-object="gridApiObject" :index="index" :ref="'modal_' + index" :row-data="record"></easy-create-form-modal>
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
		record: {
			type: Object,
			default () {
				return {
					BUTTON_CONDITION_DATA: [],
					BUTTON_FINAL_URL_DATA: []
				}
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
	components:{
		confirmDialog,
		EasyCreateFormModal
	},
	methods: {
		loadPage() {
			this.$emit("loadpage");
		},
		async onRowButtonClick(rowButtonItem, record, index) {
			let finalUrl = this.record.BUTTON_FINAL_URL_DATA[index];
			let param = {
				query: param,
				row: record
			};
			if (rowButtonItem.type === "ListRowButtonApi") {
				let res = await this.$api(finalUrl).method("POST").param(param).call();
				if (!res.status)
					this.$message.error(res.msg);
				else
					this.$message.success(res.data);
				this.loadPage();
			} else if (rowButtonItem.type === "ListRowButtonApiWithConfirm") {
				this.$refs.confirmDialog.confirm("确认要这样做么？").then(async () => {
					let res = await this.$api(finalUrl).method("POST").param(param).call();
					if (!res.status)
						this.$message.error(res.msg);
					else
						this.$message.success(res.data);
					this.loadPage();
				});
			} else if (rowButtonItem.type === "ListRowButtonNavigate") {
				this.$emit('openurl', finalUrl);
			} else if (rowButtonItem.type === "ListRowButtonRichText") {
				const html = await this.$api(finalUrl).method("POST").param(param).call();
				if (!html.status)
					return this.$message.error(html.msg);
				this.richHtmlModal.html = html.data;
				this.richHtmlModal.isShow = true;
			} else if (rowButtonItem.type === "ListRowButtonBlob") {
				try {
					let blob = await this.$api(finalUrl).method("POST").param(param).setBlob(true).call(true);
					let filename = /filename=(.*)/.exec(blob.headers["content-disposition"]);
					if(!filename)
						filename = rowButtonItem.downloadFilename;
					else
						filename = filename[1];
					blob = blob.data;
					let downloadElement = document.createElement("a");
					let href = window.URL.createObjectURL(blob);
					downloadElement.href = href;
					downloadElement.download = filename;
					document.body.appendChild(downloadElement);
					downloadElement.click();
					document.body.removeChild(downloadElement);
					window.URL.revokeObjectURL(href);
				} catch (e) {
					this.$message.error("文件导出时发生了错误：" + e, 5);
				}
			} else if (rowButtonItem.type === "ListRowButtonWithForm") {
				this.isShowCreateModal[index] = true;
				this.$forceUpdate();
				this.$nextTick(()=>{
					this.$refs['modal_' + index][0].reset()
				})
			}
		},
		onEditClick(id) {
			if(!id)
				return this.$message.error("请在响应数据中配置id字段，否则无法使用GridEditForm功能", 5);
			this.$emit('openurl', this.gridApiObject.edit_page + "?id=" + id);
		},
		onDeleteClick(id) {
			if(!id)
				return this.$message.error("请在响应数据中配置id字段，否则无法使用delete功能", 5);
			this.$refs.confirmDialog.confirm("确认要删除这条记录么？").then(async () => {
				let e = await this.$api(this.gridApiObject.delete).method("GET").param({
					id: id
				}).call();
				if (e.status) {
					this.$message.success(e.data, 5);
					this.loadPage();
				} else {
					this.$message.error(e.msg, 5);
				}
			});
		},
		doSubmit(index, rowButton, record){
			let finalUrl = this.record.BUTTON_FINAL_URL_DATA[index];
			this.$refs['modal_' + index][0].submit(async (param)=>{
				let res = await this.$api(finalUrl).method("POST").param({
					query: param,
					row: record
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
};
</script>