<template>
	<a-card>
		<a-form v-if="isLoadOk">
			<template v-for="(column,index) in gridEditObject.editColumnCollection">
				<EditColumnCascader :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnCascader'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnCascader>
				<EditColumnText :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnText'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnText>
				<EditColumnChildrenChoose :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnChildrenChoose'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnChildrenChoose>
				<EditColumnDisplay :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnDisplay'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnDisplay>
				<EditColumnDivideNumber :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnDivideNumber'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnDivideNumber>
				<EditColumnEnum :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnEnum'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnEnum>
				<EditColumnEnumCheckBox :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnEnumCheckBox'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnEnumCheckBox>
				<EditColumnEnumRadio :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnEnumRadio'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnEnumRadio>
				<EditColumnEnumTreeCheckBox :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnEnumTreeCheckBox'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnEnumTreeCheckBox>
				<EditColumnFile :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnFile'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnFile>
				<EditColumnFiles :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnFiles'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnFiles>
				<EditColumnPassword :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnPassword'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnPassword>
				<EditColumnPicture :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnPicture'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnPicture>
				<EditColumnPictures :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnPictures'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnPictures>
				<EditColumnRichText :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnRichText'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnRichText>
				<EditColumnTextarea :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnTextarea'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnTextarea>
				<EditColumnTimestamp :column="column" :grid-api-object="gridApiObject" v-model="form[column.col]" v-if="column.type == 'EditColumnTimestamp'" v-show="displayColumns.includes(column.col)" @input="$forceUpdate();">
					<RowButton v-if="getApiButtonByColumn(column.col)" :form="form" :button="getApiButtonByColumn(column.col)" @formchange="onFormChange" />
				</EditColumnTimestamp>
			</template>
			<a-form-item style="display: flex;justify-content: center;">
				<a-button type="primary" @click="submit">修改</a-button>
				<a-button type="primary" style="margin-left: 8px;" @click="reset">重置</a-button>
			</a-form-item>
		</a-form>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
	</a-card>
</template>
<script>
import moment from "moment";
import RowButton from "./components/edit/RowButton.vue";
import EditColumnCascader from "./components/edit/column_column/EditColumnCascader.vue";
import EditColumnChildrenChoose from "./components/edit/column_column/EditColumnChildrenChoose.vue";
import EditColumnText from "./components/edit/column_column/EditColumnText.vue";
import EditColumnDisplay from "./components/edit/column_column/EditColumnDisplay.vue";
import EditColumnDivideNumber from "./components/edit/column_column/EditColumnDivideNumber.vue";
import EditColumnEnum from "./components/edit/column_column/EditColumnEnum.vue";
import EditColumnEnumCheckBox from "./components/edit/column_column/EditColumnEnumCheckBox.vue";
import EditColumnEnumRadio from "./components/edit/column_column/EditColumnEnumRadio.vue";
import EditColumnEnumTreeCheckBox from "./components/edit/column_column/EditColumnEnumTreeCheckBox.vue";
import EditColumnFile from "./components/edit/column_column/EditColumnFile.vue";
import EditColumnFiles from "./components/edit/column_column/EditColumnFiles.vue";
import EditColumnPassword from "./components/edit/column_column/EditColumnPassword.vue";
import EditColumnPicture from "./components/edit/column_column/EditColumnPicture.vue";
import EditColumnPictures from "./components/edit/column_column/EditColumnPictures.vue";
import EditColumnRichText from "./components/edit/column_column/EditColumnRichText.vue";
import EditColumnTextarea from "./components/edit/column_column/EditColumnTextarea.vue";
import EditColumnTimestamp from "./components/edit/column_column/EditColumnTimestamp.vue";
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
			gridEditObject: {
				"primaryKey": "id",
				"editColumnCollection": [], //{"col": "id","tip": "","default": "","type": "EditColumnHidden"}
				"editRowButtonBaseCollection": [], //["bindCol":"","apiUrl":"","buttonText":"","buttonType":""]
				"createOrEditColumnChangeHookCollection": null
			},
			displayColumns: [],
			form: {},
		};
	},
	components: {
		EditColumnCascader,
		EditColumnChildrenChoose,
		EditColumnDisplay,
		EditColumnDivideNumber,
		EditColumnText,
		EditColumnEnum,
		EditColumnEnumCheckBox,
		EditColumnEnumRadio,
		EditColumnEnumTreeCheckBox,
		EditColumnFile,
		EditColumnFiles,
		EditColumnPassword,
		EditColumnPicture,
		EditColumnPictures,
		EditColumnRichText,
		EditColumnTextarea,
		EditColumnTimestamp,
		ConfirmDialog,
		RowButton
	},
	async mounted() {
		try {
			this.gridPath = this.$route.path.substring(0, this.$route.path.length - "/list".length);
			this.gridConfigUrl = "/api" + this.gridPath + "/grid_config";
			const gridConfigRes = await this.$api(this.gridConfigUrl).param(this.$route.query).method("POST").call();
			if (!gridConfigRes.status)
				throw gridConfigRes.msg;
			Object.assign(this.gridApiObject, gridConfigRes.api);
			Object.assign(this.gridEditObject, gridConfigRes.grid.edit);
			this.gridEditObject.editColumnCollection.map((editColumnItem) => {
				this.form[editColumnItem.col] = "";
				this.displayColumns.push(editColumnItem.col);
			});
			await this.reset();
			this.setWatchHook();
			this.isLoadOk = true;
		} catch (e) {
			this.$message.error("配置加载错误：" + e, 5);
		}
	},
	methods: {
		getApiButtonByColumn(column) {
			let ret = this.gridEditObject.editRowButtonBaseCollection.filter((t) => t.bindCol == column.col);
			if (ret.length == 0)
				return null;
			return ret[0];
		},
		setWatchHook() {
			this.gridEditObject.createOrEditColumnChangeHookCollection.map((col) => {
				this.$watch("form." + col.col, () => {
					this.onHookCall(col.col);
				});
			});
		},
		async onHookCall(hookCol) {
			try {
				let res = await this.$api(this.gridApiObject.api_column_change).method("POST").param({
					type: "edit",
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
				let res = await this.$api(this.gridApiObject.detail).param(this.$route.query).method("POST").call();
				if (!res.status)
					throw res.msg;
				for (let i in this.gridEditObject.editColumnCollection)
					this.form[this.gridEditObject.editColumnCollection[i].col] = res.data[this.gridEditObject.editColumnCollection[i].col];
			} catch (e) {
				this.$message.error(e + "", 5);
			}
		},
		async submit() {
			const param = {};
			this.gridEditObject.editColumnCollection.map((col) => {
				if (col.type === 'EditColumnDisplay')
					return;
				param[col.col] = this.form[col.col];
			});
			try {
				let res = await this.$api(this.gridApiObject.save).method("POST").param(param).call();
				if (res.status) {
					this.$message.success(res.data, 5);
					this.reset();
					this.$closePage(this.$route.path);
					this.$router.go(-1);
					try {
						let beforePage = localStorage.beforePage
						if (!beforePage) {
							throw "";
						} else {
							beforePage = JSON.parse(beforePage);
							let res = beforePage.filter((t) => {
								return t.after == this.$route.fullPath
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