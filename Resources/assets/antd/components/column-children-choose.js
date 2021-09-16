Vue.component("ColumnChildrenChoose", {
    props: {
        value: {
            type: String,
            required: true
        },
        tip: {
            type: String,
            required: true
        },
        column: {
            type: Object,
            required: true
        },
        api: {
            type: Object,
            required: true
        },
        pagetype: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            isShow: false,
            columns: [],
            templates: this.column.extra.columns.filter((col) => {
                return col.type !== "TEXT";
            }),
            dataSource: [],
            selectedRows: [],
            pagination: {
                current: 1,
                total: 0,
                pageSize: 15,
            },
            searchObj: {},
        };
    },
    created() {
        this.column.extra.filter_columns.map((col) => {
            if (col.type === "FILTER_STARTTIME")
                return this.searchObj[col.col + "_starttime"] = this.getQueryString(col.col + "_starttime");
            if (col.type === "FILTER_ENDTIME")
                return this.searchObj[col.col + "_endtime"] = this.getQueryString(col.col + "_endtime");
            return this.searchObj[col.col] = this.getQueryString(col.col);
        });
        this.columns = this.column.extra.columns.map((col) => {
            if (col.type === "TEXT")
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
        this.loadPage();
    },
    methods: {
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
            let res = await Vue.prototype.$api(this.api.detail_column_list + "?type=" + this.pagetype + "&col=" + this.column.col).method("POST").param(param).call();
            this.pagination.total = res.total;
            this.pagination.pageSize = res.per_page;
            this.dataSource = res.data;
        },
        getQueryString(name) {
            const reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
            const r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return "";
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
                window.open(headerButtonItem.url);
        }
        ,
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
                    window.open(rowButtonItem.url + "&id=" + id);
                else
                    window.open(rowButtonItem.url + "?id=" + id);
        },
        select(record) {
            this.$emit("input", record[this.columns[0].dataIndex] + "");
            this.$emit("update:tip", record[this.column.extra.displayColumn]);
            this.isShow = false;
            this.$forceUpdate()
        }
    },
    template: `<div><span>{{tip}}</span>
    <a-button type="primary" @click="isShow = true;$forceUpdate()">选择
    </a-button>
    <a-modal v-model="isShow" :title="'请选择' + column.tip">
        <div>
            <a-row>
                <a-col :md="12" :sm="24" v-for="(filterItem,indexT) in column.extra.filter_columns" :key="indexT">
                    <a-row v-if="filterItem.type == 'FILTER_ENUM'" class="antoa-list-filter-item">
                        <a-col :span="8">
                            <div class="antoa-list-filter-label">
                                {{filterItem.tip}}
                            </div>
                        </a-col>
                        <a-col :span="16">
                            <a-select v-model="searchObj[filterItem.col]" :placeholder="'请选择'+filterItem.tip"
                                @change="$forceUpdate()">
                                <a-select-option value="">不筛选</a-select-option>
                                <a-select-option :value="index2" v-for="(col,index2) in filterItem.extra"
                                    :key="index2">
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
                    <a-button type="primary" @click="loadPage">查询</a-button>
                </span>
            </a-row>
        </div>
        <a-space class="antoa-list-operator">
            <a-button @click="onHeaderButtonClick(headerButton)" :type="headerButton.type"
                v-for="(headerButton,index) in column.extra.header_buttons" :key="index">
                @{{ headerButton.title }}
            </a-button>
        </a-space>
        <standard-table :columns="columns" :data-source="dataSource" v-if="columns.length > 0"
            :pagination="pagination" :selected-rows.sync="selectedRows"
            :row-key="columns[0].dataIndex"
            @change="pagination = $event;loadPage();">
            <template :slot="templateItem.col" slot-scope="{text, record}"
                v-for="templateItem in templates">
                <div v-if="templateItem.type == 'ENUM'">
                    <div>@{{(templateItem.extra)[record[templateItem.col]+'']}}</div>
                </div>
                <div v-if="templateItem.type == 'RICH_TEXT'">
                    <div v-html="record[templateItem.col]"></div>
                </div>
                <div v-if="templateItem.type == 'PICTURE'">
                    <div><img :src="record[templateItem.col]" :style="templateItem.extra" />
                    </div>
                </div>
            </template>
            <div slot="action" slot-scope="{text, record}">
                <a-button
                    @click="onRowButtonClick(rowButton,record,record[columns[0].dataIndex])"
                    :type="rowButton.type" v-for="(rowButton,index) in column.extra.row_buttons" :key="index"
                    style="margin: 5px;">
                    @{{ rowButton.title }}
                </a-button>
                <a-button
                    @click="select(record)"
                    type="primary" class="linebtn">选择
                </a-button>
            </div>
        </standard-table>
    </a-modal></div>`
});
