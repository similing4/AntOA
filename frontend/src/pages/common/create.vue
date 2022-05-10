<template>
	<a-card>
		<a-form v-if="isLoadOk">
			<template v-for="(column,index) in gridCreateObject.createColumnCollection">
				<CreateColumnCascader :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnCascader'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnCascader>
				<CreateColumnText :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnText'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnText>
				<CreateColumnChildrenChoose :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnChildrenChoose'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnChildrenChoose>
				<CreateColumnDisplay :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnDisplay'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnDisplay>
				<CreateColumnDivideNumber :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnDivideNumber'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnDivideNumber>
				<CreateColumnEnum :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnEnum'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnEnum>
				<CreateColumnEnumCheckBox :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnEnumCheckBox'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnEnumCheckBox>
				<CreateColumnEnumRadio :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnEnumRadio'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnEnumRadio>
				<CreateColumnEnumTreeCheckBox :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnEnumTreeCheckBox'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnEnumTreeCheckBox>
				<CreateColumnFile :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnFile'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnFile>
				<CreateColumnFiles :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnFiles'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnFiles>
                <CreateColumnFileLocal :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnFileLocal'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnFileLocal>
				<CreateColumnFilesLocal :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnFilesLocal'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnFilesLocal>
				<CreateColumnPassword :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnPassword'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPassword>
				<CreateColumnPicture :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnPicture'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPicture>
				<CreateColumnPictures :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnPictures'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPictures>
				<CreateColumnPictureLocal :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnPictureLocal'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPictureLocal>
				<CreateColumnPicturesLocal :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnPicturesLocal'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPicturesLocal>
				<CreateColumnRichText :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnRichText'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnRichText>
				<CreateColumnTextarea :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnTextarea'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnTextarea>
				<CreateColumnTimestamp :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'CreateColumnTimestamp'" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnTimestamp>
				<CreateColumnPlugin :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type.startsWith('PluginCreateColumn')" v-show="displayColumns.includes(column.col)" @input="onFormItemChange(column.col);$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</CreateColumnPlugin>
			</template>
			<a-form-item style="display: flex;justify-content: center;">
				<a-button type="primary" @click="submit">创建</a-button>
				<a-button type="primary" style="margin-left: 8px;" @click="reset">重置</a-button>
			</a-form-item>
		</a-form>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
	</a-card>
</template>
<script>
import moment from "moment";
import RowButton from "./components/create/RowButton.vue";
import CreateColumnCascader from "./components/create/column_column/CreateColumnCascader.vue";
import CreateColumnChildrenChoose from "./components/create/column_column/CreateColumnChildrenChoose.vue";
import CreateColumnText from "./components/create/column_column/CreateColumnText.vue";
import CreateColumnDisplay from "./components/create/column_column/CreateColumnDisplay.vue";
import CreateColumnDivideNumber from "./components/create/column_column/CreateColumnDivideNumber.vue";
import CreateColumnEnum from "./components/create/column_column/CreateColumnEnum.vue";
import CreateColumnEnumCheckBox from "./components/create/column_column/CreateColumnEnumCheckBox.vue";
import CreateColumnEnumRadio from "./components/create/column_column/CreateColumnEnumRadio.vue";
import CreateColumnEnumTreeCheckBox from "./components/create/column_column/CreateColumnEnumTreeCheckBox.vue";
import CreateColumnFile from "./components/create/column_column/CreateColumnFile.vue";
import CreateColumnFiles from "./components/create/column_column/CreateColumnFiles.vue";
import CreateColumnFileLocal from "./components/create/column_column/CreateColumnFileLocal.vue";
import CreateColumnFilesLocal from "./components/create/column_column/CreateColumnFilesLocal.vue";
import CreateColumnPassword from "./components/create/column_column/CreateColumnPassword.vue";
import CreateColumnPicture from "./components/create/column_column/CreateColumnPicture.vue";
import CreateColumnPictures from "./components/create/column_column/CreateColumnPictures.vue";
import CreateColumnPictureLocal from "./components/create/column_column/CreateColumnPictureLocal.vue";
import CreateColumnPicturesLocal from "./components/create/column_column/CreateColumnPicturesLocal.vue";
import CreateColumnRichText from "./components/create/column_column/CreateColumnRichText.vue";
import CreateColumnTextarea from "./components/create/column_column/CreateColumnTextarea.vue";
import CreateColumnTimestamp from "./components/create/column_column/CreateColumnTimestamp.vue";
import CreateColumnPlugin from "./components/CreateColumnPluginGeneral.vue";
import ConfirmDialog from "@/components/tool/ConfirmDialog.vue";
export default {
	data() {
		return {
			isLoadOk: false,
			gridPath: "",
			gridConfigUrl: "",
			gridApiObject: {
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
			},
			gridCreateObject: {
				"primaryKey": "id",
				"createColumnCollection": [], //{"col": "id","tip": "","default": "","type": "CreateColumnHidden"}
				"createRowButtonBaseCollection": [], //["bindCol":"","apiUrl":"","buttonText":"","buttonType":""]
				"createOrEditColumnChangeHookCollection": null
			},
			displayColumns: [],
			form: {},
		};
	},
	components: {
		CreateColumnCascader,
		CreateColumnChildrenChoose,
		CreateColumnDisplay,
		CreateColumnDivideNumber,
		CreateColumnText,
		CreateColumnEnum,
		CreateColumnEnumCheckBox,
		CreateColumnEnumRadio,
		CreateColumnEnumTreeCheckBox,
		CreateColumnFile,
		CreateColumnFiles,
        CreateColumnFileLocal,
		CreateColumnFilesLocal,
		CreateColumnPassword,
		CreateColumnPicture,
		CreateColumnPictures,
		CreateColumnPictureLocal,
		CreateColumnPicturesLocal,
		CreateColumnRichText,
		CreateColumnTextarea,
		CreateColumnTimestamp,
		CreateColumnPlugin,
		ConfirmDialog,
		RowButton
	},
	async mounted() {
		try {
			this.gridPath = this.$route.path.substring(0, this.$route.path.length - "/create".length);
			this.gridConfigUrl = "/api" + this.gridPath + "/grid_config";
			const gridConfigRes = await this.$api(this.gridConfigUrl).param(this.$route.query).method("POST").call();
			if (!gridConfigRes.status){
				if(gridConfigRes.msg == "登录失效"){
					this.$message.error("登录失效，请重新登录", 5);
					return this.$router.push("/login");
				}
				throw gridConfigRes.msg;
			}
			Object.assign(this.gridApiObject, gridConfigRes.api);
			Object.assign(this.gridCreateObject, gridConfigRes.grid.create);
			this.gridCreateObject.createColumnCollection.map((createColumnItem) => {
				this.form[createColumnItem.col] = "";
				this.displayColumns.push(createColumnItem.col);
			});
			await this.reset();
			for (let i = 0; i < this.gridCreateObject.createOrEditColumnChangeHookCollection.length; i++)
				await this.onHookCall(this.gridCreateObject.createOrEditColumnChangeHookCollection[i].col);
			this.isLoadOk = true;
		} catch (e) {
			this.$message.error("配置加载错误：" + e, 5);
		}
	},
	methods: {
		getApiButtonByColumn(column) {
			let ret = this.gridCreateObject.createRowButtonBaseCollection.filter((t) => t.bindCol == column.col);
			if (ret.length == 0)
				return null;
			return ret[0];
		},
		onFormItemChange(col1){
			this.gridCreateObject.createOrEditColumnChangeHookCollection.filter((col) => {
				return col1 === col.col;
			}).map((t)=>{
				this.onHookCall(t.col);
			});
		},
		async onHookCall(hookCol) {
			try {
				let res = await this.$api(this.gridApiObject.api_column_change).method("POST").param({
					type: "create",
					form: this.form,
					page: this.$route.query,
					col: hookCol
				}).call();
				if (res.status)
					this.onFormChange(res);
				else
					throw res.msg;
			} catch (e) {
				this.$message.error(e + "", 5);
			}
		},
		onFormChange(res) {
			Object.assign(this.form, res.data);
			if (res.displayColumns && res.displayColumns.length > 0)
				this.displayColumns = res.displayColumns;
		},
		async reset() {
			try {
				for (let i in this.gridCreateObject.createColumnCollection)
					this.form[this.gridCreateObject.createColumnCollection[i].col] = "";
			} catch (e) {
				this.$message.error(e + "", 5);
			}
		},
		async submit() {
			const param = {};
			this.gridCreateObject.createColumnCollection.map((col) => {
				if (col.type === 'CreateColumnDisplay')
					return;
				param[col.col] = this.form[col.col];
			});
			try {
				let res = await this.$api(this.gridApiObject.create).method("POST").param(param).call();
				if (res.status) {
					this.$message.success(res.data, 5);
					this.reset();
					try {
						let beforePage = localStorage.beforePage
						if (!beforePage) {
							throw "";
						} else {
							beforePage = JSON.parse(beforePage);
							console.log(beforePage,this.$route.fullPath);
							let res = beforePage.filter((t) => {
								return t.after == this.$route.fullPath || t.after == this.$route.fullPath + "?" || t.after + "?" == this.$route.fullPath
							});
							if (res.length == 0)
								throw "";
							this.$closePage(this.$route.path, res[0].before);
						}
					} catch (e2) {
						this.$closePage(this.$route.path);
						this.$router.go(-1);
					}
				} else
					throw res.msg;
			} catch (e) {
				this.$message.error(e + "", 5);
			}
		}
	}
};
</script>
<style scoped>
.antoa-list-filter-item {
	padding-bottom: 20px;
}

.antoa-list-operator {
	padding-bottom: 20px;
}

.antoa-list-filter-label {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
	align-items: center;
	font-weight: 400;
	height: 32px;
	padding-right: 12px;
}
</style>
