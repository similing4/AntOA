<template>
	<div>
		<a-card v-if="tableObj != null">
			<div>
				<a-row>
					<a-col :md="12" :sm="24" v-for="(filterItem,indexT) in tableObj.filter_columns" :key="indexT">
						<a-row v-if="filterItem.type == 'FILTER_ENUM'" class="antoa-list-filter-item">
							<a-col :span="8">
								<div class="antoa-list-filter-label">
									{{filterItem.tip}}
								</div>
							</a-col>
							<a-col :span="16">
								<a-select v-model="searchObj[filterItem.col]" :placeholder="'请选择'+filterItem.tip" style="width:100%"
									@change="$forceUpdate()">
									<a-select-option value="">不筛选</a-select-option>
									<a-select-option :value="index2" v-for="(col,index2) in filterItem.extra" :key="index2">
										{{ col }}
									</a-select-option>
								</a-select>
							</a-col>
						</a-row>
						<a-row v-if="filterItem.type == 'FILTER_STARTTIME' || filterItem.type == 'FILTER_ENDTIME'"
							class="antoa-list-filter-item">
							<a-col :span="8" class="antoa-list-filter-label">
								<div class="antoa-list-filter-label">
									{{filterItem.tip}}
								</div>
							</a-col>
							<a-col :span="16">
								<a-date-picker
									v-model="searchObj[filterItem.col + (filterItem.type == 'FILTER_STARTTIME' ? '_starttime' : '_endtime')]"
									format="YYYY-MM-DD hh:mm:ss" :placeholder="'请选择'+filterItem.tip"
									style="width: 100%;" @panelChange="$forceUpdate()"></a-date-picker>
							</a-col>
						</a-row>
						<a-row v-if="filterItem.type == 'FILTER_TEXT'" class="antoa-list-filter-item">
							<a-col :span="8" class="antoa-list-filter-label">
								<div class="antoa-list-filter-label">
									{{filterItem.tip}}
								</div>
							</a-col>
							<a-col :span="16">
								<a-input v-model="searchObj[filterItem.col]" :placeholder="'请填写' + filterItem.tip">
								</a-input>
							</a-col>
						</a-row>
					</a-col>
					<span style="float: right; margin-top: 3px;">
						<a-button type="primary" @click="doSearch">查询</a-button>
						<a-button style="margin-left: 8px" @click="resetSearch">重置</a-button>
					</span>
				</a-row>
			</div>
			<div>
				<a-space class="antoa-list-operator">
					<a-button @click="onAddClick" type="primary" v-if="tableObj.hasCreate">创建</a-button>
					<a-button @click="onHeaderButtonClick(headerButton)" :type="headerButton.type"
						v-for="(headerButton,index) in tableObj.header_buttons" :key="index">
						{{headerButton.title }}
					</a-button>
				</a-space>
				<div style="margin-bottom: 16px">
					<a-alert type="info" :show-icon="true">
						<div class="message" slot="message">
							共计&nbsp;<a style="font-weight: 600">{{pagination.total}}</a>&nbsp;行 {{statics}}
						</div>
					</a-alert>
				</div>
				<standard-table :columns="columns" :data-source="dataSource" :selected-rows.sync="selectedRows"
					:pagination="pagination" :row-key="columns[0].dataIndex" @change="onDataChange">
					<template :slot="templateItem.col" slot-scope="{text, record}" v-for="templateItem in templates">
						<div v-if="templateItem.type == 'ENUM'">
							<div>{{(templateItem.extra)[record[templateItem.col]+'']}}</div>
						</div>
						<div v-if="templateItem.type == 'RICH_TEXT' || templateItem.type == 'RICH_DISPLAY'">
							<div v-html="record[templateItem.col]"></div>
						</div>
						<div v-if="templateItem.type == 'PICTURE'">
							<div><img :src="record[templateItem.col]" :style="templateItem.extra" /></div>
						</div>
					</template>
					<div slot="action" slot-scope="{text, record}">
						<a-button @click="onEditClick(record[columns[0].dataIndex])" type="primary"
							v-if="tableObj.hasEdit" style="margin: 5px;">
							编辑
						</a-button>
						<a-button @click="onDeleteClick(record[columns[0].dataIndex])" type="danger"
							v-if="tableObj.hasDelete" style="margin: 5px;">删除
						</a-button>
						<a-button @click="onRowButtonClick(rowButton,record,record[columns[0].dataIndex])"
							:type="rowButton.type" v-for="(rowButton,index) in tableObj.row_buttons" :key="index"
							style="margin: 5px;">
							{{ rowButton.title }}
						</a-button>
					</div>
				</standard-table>
			</div>
			<confirm-dialog ref="confirmDialog"></confirm-dialog>
		</a-card>
	</div>
</template>

<script>
	import moment from "moment";
	import StandardTable from "@/components/table/StandardTable.vue";
	import confirmDialog from "@/components/tool/ConfirmDialog.vue";
	export default {
		data() {
			return {
				tableObj: null,
				columns: null,
				searchObj: null,
				api: null,
				templates: null,
				statics: "",
				dataSource: [],
				selectedRows: [],
				pagination: {
					current: 1,
					total: 0,
					pageSize: 15
				}
			};
		},
		components: {
			StandardTable,
			confirmDialog
		},
		async mounted() {
			try {
				var path = this.$route.path.substring(0, this.$route.path.length - "/list".length);
				const configUrl = "/api" + path + "/grid_config";
				const configRes = await this.$api(configUrl).method("GET").call();
				if (!configRes.status)
					throw configRes.msg;
				const tableObj = configRes.grid.list;
				const api = configRes.api;
				this.columns = tableObj.columns.map((col) => {
					if (col.type === "TEXT" || col.type == "DISPLAY")
						return {
							"title": col.tip,
							"dataIndex": col.col
						};
					else
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
				this.templates = tableObj.columns.filter((col) => {
					return col.type !== "TEXT";
				});
				const searchObj = {};
				tableObj.filter_columns.map((col) => {
					if (col.type === "FILTER_STARTTIME")
						return searchObj[col.col + "_starttime"] = (this.$route.query[col.col + "_starttime"] ?
							this.$route.query[col.col + "_starttime"] : '');
					if (col.type === "FILTER_ENDTIME")
						return searchObj[col.col + "_endtime"] = (this.$route.query[col.col + "_endtime"] ?
							this.$route.query[col.col + "_endtime"] : '');
					return searchObj[col.col] = (this.$route.query[col.col] ? this.$route.query[col.col] : '');
				});
				this.searchObj = searchObj;
				this.api = api;
				this.tableObj = tableObj;
				this.loadPage();
			} catch (e) {
				this.$message.error("配置加载错误：" + e, 5);
			}
		},
		methods: {
			onShow() {
				this.loadPage();
			},
			openurl(url) {
				if (url.startsWith("http"))
					return window.open(url);
				this.$router.push(url);
			},
			doSearch() {
				return this.loadPage();
			},
			resetSearch() {
				for (const i in this.searchObj)
					this.searchObj[i] = (this.$route.query[i] ? this.$route.query[i] : '');
				this.doSearch();
			},
			onDataChange(pagination) {
				this.pagination = pagination;
				this.loadPage();
			},
			async loadPage() {
				let param = {};
				Object.assign(param, this.searchObj, {
					page: this.pagination.current
				});
				for (let i in param) {
					if (param[i] instanceof moment)
						param[i] = param[i].format('YYYY-MM-DD hh:mm:ss');
					if (param[i] === null || param[i] === undefined)
						param[i] = "";
				}
				let res = await this.$api(this.api.list).method("POST").param(param).call();
				this.pagination.total = res.total;
				this.pagination.pageSize = res.per_page;
				this.dataSource = res.data;
				this.statics = res.statics;
			},
			onAddClick() {
                var params = [];
                for(var i in this.searchObj)
                    params.push(i + "=" + this.searchObj[i]);
				this.openurl(this.api.create_page + "?" + params.join("&"));
			},
			onEditClick(id) {
                var params = [];
                for(var i in this.searchObj){
                    if(i == "id")
                        continue;
                    params.push(i + "=" + this.searchObj[i]);
                }
				this.openurl(this.api.edit_page + "?id=" + id + "&" + params.join("&"));
			},
			onDeleteClick(id) {
				this.$refs.confirmDialog.confirm("确认要删除这条记录么？").then(async () => {
					let e = await this.$api(this.api.delete).method("GET").param({
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
			async onHeaderButtonClick(headerButtonItem) {
				let param = {};
				Object.assign(param, this.searchObj, {
					page: this.pagination.current
				});
				for (let i in param)
					if (param[i] instanceof moment)
						param[i] = param[i].format('YYYY-MM-DD hh:mm:ss');
				if (headerButtonItem.btn_do_type === "api") {
					let res = await this.$api(headerButtonItem.url).method("POST").param(param).call();
					if (!res.status)
						this.$message.error(res.msg);
					else
						this.$message.success(res.data);
					this.loadPage();
				} else if (headerButtonItem.btn_do_type === "api_confirm") {
					this.$refs.confirmDialog.confirm("确认要这样做么？").then(async () => {
						let res = await this.$api(headerButtonItem.url).method("POST").param(param).call();
						if (!res.status)
							this.$message.error(res.msg);
						else
							this.$message.success(res.data);
						this.loadPage();
					});
				} else if (headerButtonItem.btn_do_type === "navigate")
					this.openurl(headerButtonItem.url);
			},
			async onRowButtonClick(rowButtonItem, record, id) {
				if (rowButtonItem.btn_do_type === "api") {
					let res = await this.$api(rowButtonItem.url).method("GET").param({
						id: id
					}).call();
					if (!res.status)
						this.$message.error(res.msg);
					else
						this.$message.success(res.data);
					this.loadPage();
				} else if (rowButtonItem.btn_do_type === "api_confirm") {
					this.$refs.confirmDialog.confirm("确认要这样做么？").then(async () => {
						let res = await this.$api(rowButtonItem.url).method("GET").param({
							id: id
						}).call();
						if (!res.status)
							this.$message.error(res.msg);
						else
							this.$message.success(res.data);
						this.loadPage();
					});
				} else if (rowButtonItem.btn_do_type === "navigate")
					if (rowButtonItem.url.includes("?"))
						this.openurl(rowButtonItem.url + "&" + rowButtonItem.dest_col + "=" + id);
					else
						this.openurl(rowButtonItem.url + "?" + rowButtonItem.dest_col + "=" + id);
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
