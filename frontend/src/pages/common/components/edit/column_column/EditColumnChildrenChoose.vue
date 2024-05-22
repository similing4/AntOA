<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<span v-if="value">{{currentFormTip}}</span>
		<a-button type="primary" @click="isSelectModalShow = true;$forceUpdate()">选择</a-button>
		<a-button type="danger" @click="remove" v-if="value">删除</a-button>
		<a-modal v-model="isSelectModalShow" :title="'请选择' + column.tip">
			<div style="margin-bottom: 16px">
				<a-row>
					<a-col :md="12" :sm="24" v-for="(filterItem,indexT) in column.gridListEasy.listFilterCollection"
						   :key="indexT">
						<ListFilterEnum v-if="filterItem.type == 'ListFilterEnum'" :item="filterItem"
										v-model="tableModel.searchObj[filterItem.col]"/>
						<ListFilterStartTime v-if="filterItem.type == 'ListFilterStartTime'" :item="filterItem"
											 v-model="tableModel.searchObj[filterItem.col+ '_starttime']"/>
						<ListFilterEndTime v-if="filterItem.type == 'ListFilterEndTime'" :item="filterItem"
										   v-model="tableModel.searchObj[filterItem.col+  '_endtime']"/>
						<ListFilterText v-if="filterItem.type == 'ListFilterText'" :item="filterItem"
										v-model="tableModel.searchObj[filterItem.col]"/>
					</a-col>
					<span style="float: right; margin-top: 3px;"
						  v-if="column.gridListEasy.listFilterCollection.filter((t)=>t.type != 'ListFilterHidden').length > 0">
                        <a-button type="primary" @click="doSearch">查询</a-button>
                        <a-button style="margin-left: 8px" @click="resetSearch">重置</a-button>
                    </span>
				</a-row>
			</div>
			<a-space class="antoa-list-operator">
				<EasyHeaderButtonsWrapper :grid-list-object="column.gridListEasy" @openurl="openurl"
										  @loadpage="loadPage"/>
			</a-space>
			<standard-table :columns="tableModel.columns" v-if="tableModel.columns.length > 0"
							:data-source="tableModel.dataSource" :selected-rows.sync="tableModel.selectedRows"
							:pagination="tableModel.pagination" :row-key="column.gridListVModelCol"
							@change="onDataChange">
				<template :slot="templateItem.col" slot-scope="{text, record}"
						  v-for="templateItem in column.gridListEasy.listTableColumnCollection">
					<ListTableColumnEnum :render="templateItem" :value="record[templateItem.col]"
										 v-if="templateItem.type == 'ListTableColumnEnum'"/>
					<ListTableColumnDivideNumber :render="templateItem" :value="record[templateItem.col]"
												 v-if="templateItem.type == 'ListTableColumnDivideNumber'"/>
					<ListTableColumnRichText :render="templateItem" :value="record[templateItem.col]"
											 v-if="templateItem.type == 'ListTableColumnRichText'"/>
					<ListTableColumnRichDisplay :render="templateItem" :value="record[templateItem.col]"
												v-if="templateItem.type == 'ListTableColumnRichDisplay'"/>
					<ListTableColumnText :render="templateItem" :value="record[templateItem.col]"
										 v-if="templateItem.type == 'ListTableColumnText'"/>
					<ListTableColumnPicture :render="templateItem" :value="record[templateItem.col]"
											v-if="templateItem.type == 'ListTableColumnPicture'"/>
				</template>
				<div slot="action" slot-scope="{text, record}">
					<EasyRowButtonsWrapper :grid-list-object="column.gridListEasy" :record="record" @openurl="openurl"
										   @loadpage="loadPage" @select="select(record)"/>
				</div>
			</standard-table>
		</a-modal>
		<slot/>
	</a-form-item>
</template>
<script>
	import ListFilterStartTime from "../../list/filter/ListFilterStartTime.vue";
	import ListFilterEndTime from "../../list/filter/ListFilterEndTime.vue";
	import ListFilterEnum from "../../list/filter/ListFilterEnum.vue";
	import ListFilterText from "../../list/filter/ListFilterText.vue";
	import ListTableColumnEnum from "../../list/table_column/ListTableColumnEnum.vue";
	import ListTableColumnDivideNumber from "../../list/table_column/ListTableColumnDivideNumber.vue";
	import ListTableColumnRichText from "../../list/table_column/ListTableColumnRichText.vue";
	import ListTableColumnRichDisplay from "../../list/table_column/ListTableColumnRichDisplay.vue";
	import ListTableColumnPicture from "../../list/table_column/ListTableColumnPicture.vue";
	import ListTableColumnText from "../../list/table_column/ListTableColumnText.vue";
	import StandardTable from "@/components/table/StandardTable.vue";
	import EasyHeaderButtonsWrapper from "../EasyHeaderButtonsWrapper.vue";
	import EasyRowButtonsWrapper from "../EasyRowButtonsWrapper.vue";

	export default {
		props: {
			column: {
				type: Object,
				default() {
					return {
						"col": "",
						"tip": "",
						"default": "",
						"type": "EditColumnChildrenChoose",
						"gridListEasy": {
							"listFilterCollection": [], //{"col": "id","tip": "","default": "","type": "ListFilterHidden"}
							"listTableColumnCollection": [], //{"col": "game_uid","tip": "雀魂UID","type": "ListTableColumnText"}
							"listHeaderButtonCollection": [],
							"listRowButtonCollection": [] //{"type": "ListRowButtonNavigate","buttonText": "报名记录","buttonType": "primary","baseUrl": "\/race\/register\/list","finalUrl": null}
						},
						"gridListVModelCol": "id",
						"gridListDisplayCol": "game_nickname"
					};
				}
			},
			gridApiObject: {
				type: Object,
				default() {
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
						save: "",
						api_upload: ""
					};
				},
			},
			value: {
				type: [String, Number]
			}
		},
		data() {
			return {
				isSelectModalShow: false,
				currentFormTip: "",
				tableModel: {
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
			};
		},
		components: {
			StandardTable,
			EasyHeaderButtonsWrapper,
			EasyRowButtonsWrapper,
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
		},
		async mounted() {
			try {
				this.generateTableObject();
				this.generateSearchObject();
				await this.loadPage();
				this.isLoadOk = true;
			} catch (e) {
				this.$message.error("配置加载错误：" + e, 5);
			}
		},
		methods: {
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
			generateSearchObject() {
				let searchObj = {};
				this.column.gridListEasy.listFilterCollection.map((col) => {
					if (col.type === "ListFilterStartTime")
						return searchObj[col.col + "_starttime"] = (this.$route.query[col.col + "_starttime"] ? this.$route.query[col.col + "_starttime"] : '');
					if (col.type === "ListFilterEndTime")
						return searchObj[col.col + "_endtime"] = (this.$route.query[col.col + "_endtime"] ? this.$route.query[col.col + "_endtime"] : '');
					return searchObj[col.col] = (this.$route.query[col.col] ? this.$route.query[col.col] : '');
				});
				this.tableModel.searchObj = searchObj;
			},
			generateTableObject() {
				this.tableModel.columns = this.column.gridListEasy.listTableColumnCollection.map((col) => {
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
			doSearch() {
				return this.loadPage();
			},
			resetSearch() {
				for (const i in this.tableModel.searchObj)
					this.tableModel.searchObj[i] = (this.$route.query[i] ? this.$route.query[i] : '');
				this.doSearch();
			},
			async loadPage() {
				let param = {};
				Object.assign(param, this.tableModel.searchObj, {
					page: this.tableModel.pagination.current
				});
				let res = await this.$api(this.gridApiObject.detail_column_list + "?type=edit&col=" + this.column.col + "&val=" + this.value).method("POST").param(param).call();
				if (res.status == 0)
					throw res.msg;
				this.tableModel.pagination.total = res.total;
				this.tableModel.pagination.pageSize = res.per_page;
				this.tableModel.dataSource = res.data;
				this.currentFormTip = res.vModelValTip;
			},
			onDataChange(pagination) {
				this.tableModel.pagination = pagination;
				this.loadPage();
			},
			select(record) {
				this.$emit("input", record[this.column.gridListVModelCol]);
				this.isSelectModalShow = false;
				this.$forceUpdate()
				this.currentFormTip = record[this.column.gridListDisplayCol];
			},
			remove() {
				this.$emit("input", "");
				this.currentFormTip = "";
			}
		}
	}
</script>
