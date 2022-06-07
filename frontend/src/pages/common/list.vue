<template>
	<div>
		<a-card v-if="isLoadOk">
			<div style="margin-bottom: 16px">
				<a-row>
					<a-col :md="12" :sm="24" v-for="(filterItem,indexT) in gridListObject.listFilterCollection.filter((t)=>t.type != 'ListFilterHidden')" :key="indexT">
						<ListFilterEnum v-if="filterItem.type == 'ListFilterEnum'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col]" />
						<ListFilterStartTime v-if="filterItem.type == 'ListFilterStartTime'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col+ '_starttime']" />
						<ListFilterEndTime v-if="filterItem.type == 'ListFilterEndTime'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col+  '_endtime']" />
						<ListFilterText v-if="filterItem.type == 'ListFilterText'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col]" />
						<ListFilterMultiSelect v-if="filterItem.type == 'ListFilterMultiSelect'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col]" />
						<ListFilterCascader v-if="filterItem.type == 'ListFilterCascader'" :item="filterItem" v-model="tableModel.searchObj[filterItem.col]" />
						<ListFilterPlugin v-if="filterItem.type.startsWith('PluginListFilter')" :item="filterItem" v-model="tableModel.searchObj[filterItem.col]" />
					</a-col>
					<span style="float: right; margin-top: 3px;" v-if="gridListObject.listFilterCollection.filter((t)=>t.type != 'ListFilterHidden').length > 0">
						<a-button type="primary" @click="doSearch">查询</a-button>
						<a-button style="margin-left: 8px" @click="resetSearch">重置</a-button>
					</span>
				</a-row>
			</div>
			<div>
				<a-space class="antoa-list-operator">
					<HeaderButtonsWrapper :grid-list-object="gridListObject" :grid-api-object="gridApiObject" :selected-rows="tableModel.selectedRows" :table-model="tableModel" @openurl="openurl" @loadpage="loadPage" />
				</a-space>
				<div style="margin-bottom: 16px">
					<a-alert type="info" :show-icon="true">
						<div class="message" slot="message">
							共计&nbsp;<a style="font-weight: 600">{{tableModel.pagination.total}}</a>&nbsp;行
						</div>
					</a-alert>
				</div>
				<div style="margin-bottom: 16px" v-if="statistic != ''">
					<a-alert type="info" :show-icon="true">
						<div class="message" slot="message">
							<div style="white-space: pre-wrap;">{{statistic}}</div>
						</div>
					</a-alert>
				</div>
				<standard-table :columns="tableModel.columns" :data-source="tableModel.dataSource" :selected-rows.sync="tableModel.selectedRows" :pagination="tableModel.pagination" :row-key="gridListObject.primaryKey" @change="onDataChange">
					<template :slot="templateItem.col" slot-scope="{text, record}" v-for="templateItem in gridListObject.listTableColumnCollection">
						<ListTableColumnEnum :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnEnum'" />
						<ListTableColumnDivideNumber :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnDivideNumber'" />
						<ListTableColumnRichText :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnRichText'" />
						<ListTableColumnRichDisplay :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnRichDisplay'" />
						<ListTableColumnText :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnText'" />
						<ListTableColumnPicture :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type == 'ListTableColumnPicture'" />
						<ListTableColumnPlugin :render="templateItem" :value="record[templateItem.col]" v-if="templateItem.type.startsWith('PluginListTableColumn')" />
					</template>
					<div slot="action" slot-scope="{text, record}">
						<RowButtonsWrapper :grid-list-object="gridListObject" :grid-api-object="gridApiObject" :record="record" @openurl="openurl" @loadpage="loadPage" />
					</div>
				</standard-table>
			</div>
			<confirm-dialog ref="confirmDialog"></confirm-dialog>
		</a-card>
	</div>
</template>
<script>
import ListFilterStartTime from "./components/list/filter/ListFilterStartTime.vue";
import ListFilterEndTime from "./components/list/filter/ListFilterEndTime.vue";
import ListFilterEnum from "./components/list/filter/ListFilterEnum.vue";
import ListFilterText from "./components/list/filter/ListFilterText.vue";
import ListFilterMultiSelect from "./components/list/filter/ListFilterMultiSelect.vue";
import ListFilterCascader from "./components/list/filter/ListFilterCascader.vue";
import ListTableColumnEnum from "./components/list/table_column/ListTableColumnEnum.vue";
import ListTableColumnDivideNumber from "./components/list/table_column/ListTableColumnDivideNumber.vue";
import ListTableColumnRichText from "./components/list/table_column/ListTableColumnRichText.vue";
import ListTableColumnRichDisplay from "./components/list/table_column/ListTableColumnRichDisplay.vue";
import ListTableColumnPicture from "./components/list/table_column/ListTableColumnPicture.vue";
import ListTableColumnText from "./components/list/table_column/ListTableColumnText.vue";
import HeaderButtonsWrapper from "./components/list/HeaderButtonsWrapper.vue";
import RowButtonsWrapper from "./components/list/RowButtonsWrapper.vue";
import ListFilterPlugin from "./components/ListFilterPluginGeneral.vue";
import ListTableColumnPlugin from "./components/ListTableColumnPluginGeneral.vue";
import moment from "moment";
import StandardTable from "@/components/table/StandardTable.vue";
import confirmDialog from "@/components/tool/ConfirmDialog.vue";

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
			gridListObject: {
				"primaryKey": "id",
				"listFilterCollection": [], //{"type": "ListFilterText","col": "name","tip": "比赛名称"}
				"listTableColumnCollection": [], //{"type": "ListTableColumnText","col": "id","tip": "ID"}
				"listHeaderButtonCollection": [],
				"listRowButtonCollection": [], //{"type": "ListRowButtonNavigate","buttonText": "报名记录","buttonType": "primary","baseUrl": "\/race\/register\/list","finalUrl": null}
				"hasCreate": false,
				"hasEdit": false,
				"hasDelete": false
			},

			tableModel:{
				columns: [],
				searchObj: {},
				dataSource: [],
				selectedRows: [],
				pagination: {
					current: 1,
					total: 0,
					pageSize: 15
				},
			},
			statistic: ""
		};
	},
	components: {
		ListFilterCascader,
		ListFilterMultiSelect,
		ListFilterEnum,
		ListFilterStartTime,
		ListFilterEndTime,
		ListFilterText,
		ListTableColumnText,
		ListTableColumnEnum,
		ListTableColumnDivideNumber,
		ListTableColumnRichText,
		ListTableColumnRichDisplay,
		ListTableColumnPicture,
		HeaderButtonsWrapper,
		RowButtonsWrapper,
		StandardTable,
		confirmDialog,
		ListFilterPlugin,
		ListTableColumnPlugin
	},
	async mounted() {
		try {
			this.gridPath = this.$route.path.substring(0, this.$route.path.length - "/list".length);
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
			Object.assign(this.gridListObject, gridConfigRes.grid.list);
			this.generateTableObject();
			this.generateSearchObject();
			await this.loadPage();
			this.isLoadOk = true;
		} catch (e) {
			this.$message.error("配置加载错误：" + e, 5);
		}
	},
	methods: {
		generateSearchObject(){
			let searchObj = {};
			this.gridListObject.listFilterCollection.map((col) => {
				if (col.type === "ListFilterStartTime")
					return searchObj[col.col + "_starttime"] = (this.$route.query[col.col + "_starttime"] ? this.$route.query[col.col + "_starttime"] : '');
				if (col.type === "ListFilterEndTime")
					return searchObj[col.col + "_endtime"] = (this.$route.query[col.col + "_endtime"] ? this.$route.query[col.col + "_endtime"] : '');
				return searchObj[col.col] = (this.$route.query[col.col] ? this.$route.query[col.col] : '');
			});
			this.tableModel.searchObj = searchObj;
		},
		generateTableObject(){
			this.tableModel.columns = this.gridListObject.listTableColumnCollection.map((col) => {
				return {
					"title": col.tip,
					"scopedSlots": {
						"customRender": col.col
					}
				};
			}).concat([{
				"title": "操作",
				"scopedSlots": {
					"customRender": "action"
				}
			}]);
		},
		onShow() {
			this.loadPage();
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
		doSearch() {
			return this.loadPage();
		},
		resetSearch() {
			for (const i in this.tableModel.searchObj)
				this.tableModel.searchObj[i] = (this.$route.query[i] ? this.$route.query[i] : '');
			this.doSearch();
		},
		onDataChange(pagination) {
			this.tableModel.pagination = pagination;
			this.loadPage();
		},
		async loadPage() {
			let param = {};
			Object.assign(param, this.tableModel.searchObj, {
				page: this.tableModel.pagination.current
			});
			for (let i in param) {
				if (param[i] instanceof moment)
					param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
				if (param[i] === null || param[i] === undefined)
					param[i] = "";
			}
			let res = await this.$api(this.gridApiObject.list).method("POST").param(param).call();
			if(res.status == 0)
				throw res.msg;
			this.tableModel.pagination.total = res.total;
			this.tableModel.pagination.pageSize = res.per_page;
			this.tableModel.dataSource = res.data;
			this.statistic = res.statistic;
		}
	}
};
</script>
<style scoped lang="less">
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
