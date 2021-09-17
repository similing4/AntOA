@extends('antoa::inc.base_inc')
@section('head')
    <script src="{{ asset('/antoa/assets/components/standard-table.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/confirm-dialog.js') }}"></script>
    <style>
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
@endsection
@section('content')
    <div id="APP">
        <a-locale-provider :locale="lang">
            <a-card>
                <div style="margin-bottom: 16px">
                    <a-row>
                        <a-col :md="12" :sm="24" v-for="(filterItem,indexT) in tableObj.filter_columns" :key="indexT">
                            <a-row v-if="filterItem.type == 'FILTER_ENUM'" class="antoa-list-filter-item">
                                <a-col :span="8">
                                    <div class="antoa-list-filter-label">
                                        @{{filterItem.tip}}
                                    </div>
                                </a-col>
                                <a-col :span="16">
                                    <a-select v-model="searchObj[filterItem.col]" style="width:100%"
                                              :placeholder="'请选择'+filterItem.tip" @change="$forceUpdate()">
                                        <a-select-option value="">不筛选</a-select-option>
                                        <a-select-option :value="index2" v-for="(col,index2) in filterItem.extra"
                                                         :key="index2">
                                            @{{ col }}
                                        </a-select-option>
                                    </a-select>
                                </a-col>
                            </a-row>
                            <a-row v-if="filterItem.type == 'FILTER_STARTTIME' || filterItem.type == 'FILTER_ENDTIME'"
                                   class="antoa-list-filter-item">
                                <a-col :span="8" class="antoa-list-filter-label">
                                    <div class="antoa-list-filter-label">
                                        @{{filterItem.tip}}
                                    </div>
                                </a-col>
                                <a-col :span="16">
                                    <a-date-picker
                                        v-model="searchObj[filterItem.col + (filterItem.type == 'FILTER_STARTTIME' ? '_starttime' : '_endtime')]"
                                        format="YYYY-MM-DD HH:mm:ss"
                                        :placeholder="'请选择'+filterItem.tip" style="width: 100%;"
                                        @panelChange="$forceUpdate()"></a-date-picker>
                                </a-col>
                            </a-row>
                            <a-row v-if="filterItem.type == 'FILTER_TEXT'" class="antoa-list-filter-item">
                                <a-col :span="8" class="antoa-list-filter-label">
                                    <div class="antoa-list-filter-label">
                                        @{{filterItem.tip}}
                                    </div>
                                </a-col>
                                <a-col :span="16">
                                    <a-input v-model="searchObj[filterItem.col]"
                                             :placeholder="'请填写' + filterItem.tip"></a-input>
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
                                  v-for="(headerButton,index) in tableObj.header_buttons" :key="index">@{{
                            headerButton.title }}
                        </a-button>
                    </a-space>
                    <div style="margin-bottom: 16px">
                        <a-alert type="info" :show-icon="true">
                            <div class="message" slot="message">
                                共计&nbsp;<a style="font-weight: 600">@{{pagination.total}}</a>&nbsp;行
                            </div>
                        </a-alert>
                    </div>
                    <div style="margin-bottom: 16px" v-if="statistic != ''">
                        <a-alert type="info" :show-icon="true">
                            <div class="message" slot="message">
                                <div style="white-space: pre-wrap;">@{{statistic}}</div>
                            </div>
                        </a-alert>
                    </div>
                    <standard-table :columns="columns" :data-source="dataSource" :selected-rows.sync="selectedRows"
                                    :pagination="pagination" :row-key="columns[0].dataIndex" @change="onDataChange">
                        <template :slot="templateItem.col" slot-scope="{text, record}"
                                  v-for="templateItem in templates">
                            <div v-if="templateItem.type == 'ENUM'">
                                <div>@{{(templateItem.extra)[record[templateItem.col]+'']}}</div>
                            </div>
                            <div v-if="templateItem.type == 'RICH_TEXT' || templateItem.type == 'RICH_DISPLAY'">
                                <div v-html="record[templateItem.col]"></div>
                            </div>
                            <div v-if="templateItem.type == 'PICTURE'">
                                <div><img :src="record[templateItem.col]" :style="templateItem.extra"/></div>
                            </div>
                        </template>
                        <div slot="action" slot-scope="{text, record}">
                            <a-button @click="onEditClick(record[columns[0].dataIndex])" type="primary"
                                      v-if="tableObj.hasEdit" style="margin: 5px;">
                                编辑
                            </a-button>
                            <a-button @click="onDeleteClick(record[columns[0].dataIndex])" type="danger"
                                      v-if="tableObj.hasDelete"
                                      style="margin: 5px;">删除
                            </a-button>
                            <a-button @click="onRowButtonClick(rowButton,record,record[columns[0].dataIndex])"
                                      :type="rowButton.type" v-if="record['BUTTON_CONDITION_DATA'][index]"
                                      v-for="(rowButton,index) in tableObj.row_buttons" :key="index"
                                      style="margin: 5px;">
                                @{{ rowButton.title }}
                            </a-button>
                        </div>
                    </standard-table>
                </div>
                <confirm-dialog ref="confirmDialog"></confirm-dialog>
                <a-modal v-model="createFormModal.isShow" @ok="createFormModal.onSubmit">
                    <a-form>
                        <template v-for="(column,index) in createFormModal.columns">
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_DISPLAY'">
                                <div v-if="!createFormModal.form[column.col]" v-html="column.extra"></div>
                                <div v-if="createFormModal.form[column.col]" v-html="createFormModal.form[column.col]"></div>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_TEXT'">
                                <a-input :placeholder="'请填写' + column.tip"
                                         v-model="createFormModal.form[column.col]"></a-input>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_TEXTAREA'">
                                <a-textarea :placeholder="'请填写' + column.tip"
                                            v-model="createFormModal.form[column.col]" rows="20"
                                            allow-clear></a-textarea>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_PASSWORD'">
                                <a-input-password :placeholder="'请填写' + column.tip"
                                                  v-model="createFormModal.form[column.col]"></a-input-password>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_RADIO'">
                                <a-radio-group v-model="createFormModal.form[column.col]" @change="$forceUpdate()">
                                    <a-radio :value="index" v-for="(column_i,index) in column.extra" :key="index">
                                        @{{column_i}}
                                    </a-radio>
                                </a-radio-group>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_SELECT'">
                                <a-select v-model="createFormModal.form[column.col]">
                                    <a-select-option :value="index" v-for="(column_i,index) in column.extra"
                                                     :key="index">
                                        @{{column_i}}
                                    </a-select-option>
                                </a-select>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_CHECKBOX'">
                                <a-checkbox-group v-model="createFormModal.form[column.col]"
                                                  @change="$forceUpdate()">
                                    <a-checkbox v-for="(column_i,index) in column.extra" :key="index"
                                                :value="index">
                                        @{{column_i}}
                                    </a-checkbox>
                                </a-checkbox-group>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_TIMESTAMP'">
                                <a-date-picker show-time format="YYYY-MM-DD HH:mm:ss"
                                               :placeholder="'请选择' + column.tip"
                                               v-model="createFormModal.form[column.col]"
                                               @change="$forceUpdate()"></a-date-picker>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_RICHTEXT'">
                                <wang-editor :id="createFormModal.form[column.col]"
                                             v-model="createFormModal.form[column.col]"></wang-editor>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_PICTURE'">
                                <img :src="createFormModal.form[column.col]"
                                     v-if="createFormModal.form[column.col] != ''" style="width: 200px"/>
                                <a-button type="danger" @click="createFormModal.form[column.col] = ''"
                                          v-if="createFormModal.form[column.col]!=''">删除
                                </a-button>
                                <upload-button
                                    @uploadfinished="createFormModal.form[column.col] = $event[0].response"
                                    accept="image/*"
                                    :multiple="false"></upload-button>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_FILE'">
                                <a-button type="primary" @click="openurl(createFormModal.form[column.col])"
                                          v-if="createFormModal.form[column.col]!=''">下载
                                </a-button>
                                <a-button type="danger" @click="createFormModal.form[column.col] = ''"
                                          v-if="createFormModal.form[column.col]!=''">删除
                                </a-button>
                                <upload-button
                                    @uploadfinished="createFormModal.form[column.col] = $event[0].response"
                                    accept="*/*"
                                    :multiple="false"></upload-button>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_PICTURES'">
                                <div v-for="(fileItem,index) in createFormModal.form[column.col]" :key="index">
                                    <img :src="fileItem" style="width: 200px"/>
                                    <a-button type="danger"
                                              @click="createFormModal.form[column.col] = createFormModal.form[column.col].filter((t)=>{return t != fileItem;})">
                                        删除
                                    </a-button>
                                </div>
                                <upload-button
                                    @uploadfinished="createFormModal.form[column.col] = createFormModal.form[column.col].concat($event.map((t)=>{return t.response;}))"
                                    accept="image/*"
                                    :multiple="true"></upload-button>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_FILES'">
                                <div v-for="(fileItem,index) in createFormModal.form[column.col]" :key="index">
                                    <a-button type="primary" @click="openurl(fileItem)">下载</a-button>
                                    <a-button type="danger"
                                              @click="createFormModal.form[column.col] = createFormModal.form[column.col].filter((t)=>{return t != fileItem;})">
                                        删除
                                    </a-button>
                                </div>
                                <upload-button
                                    @uploadfinished="createFormModal.form[column.col] = createFormModal.form[column.col].concat($event.map((t)=>{return t.response;}))"
                                    accept="*/*"
                                    :multiple="true"></upload-button>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_CHOOSE'">
                                <a-cascader :placeholder="'请选择' + column.tip"
                                            v-model="createFormModal.form[column.col]"
                                            :options="column.extra"></a-cascader>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                            <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                         v-if="column.type == 'COLUMN_CHILDREN_CHOOSE'">
                                <column-children-choose v-model="createFormModal.form[column.col]"
                                                        :tip.sync="createFormModal.formTip[column.col]"
                                                        :column="column" :api="api"
                                                        pagetype="edit"></column-children-choose>
                                <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                            </a-form-item>
                        </template>
                    </a-form>
                </a-modal>
                <a-modal v-model="richHtmlModal.isShow" @ok="richHtmlModal.isShow = false">
                    <div v-html="richHtmlModal.html"></div>
                </a-modal>
            </a-card>
        </a-locale-provider>
    </div>
@endsection
@section('script')
    <script>
        function getQueryString(name) {
            const reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
            const r = decodeURI(window.location.search).substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return "";
        }

        const tableObj = {!! $grid->getGridList()->json() !!};
        const api = {!! json_encode($api) !!};
        const columns = tableObj.columns.map((col) => {
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
        const templates = tableObj.columns.filter((col) => {
            return col.type !== "TEXT";
        });
        const searchObj = {};
        tableObj.filter_columns.map((col) => {
            if (col.type === "FILTER_STARTTIME")
                return searchObj[col.col + "_starttime"] = getQueryString(col.col + "_starttime");
            if (col.type === "FILTER_ENDTIME")
                return searchObj[col.col + "_endtime"] = getQueryString(col.col + "_endtime");
            return searchObj[col.col] = getQueryString(col.col);
        });
        window.APP_VUE = new Vue({
            el: "#APP",
            data() {
                return {
                    tableObj: tableObj,
                    columns: columns,
                    searchObj: searchObj,
                    api: api,
                    templates: templates,
                    lang: antd.locales.zh_CN,
                    statistic: "",
                    dataSource: [],
                    selectedRows: [],
                    pagination: {
                        current: 1,
                        total: 0,
                        pageSize: 15
                    },
                    createFormModal: {
                        columns: [],
                        form: {},
                        formTip: {},
                        apiButtons: [],
                        isShow: false,
                        onSubmit() {
                        },
                        submitText: ""
                    },
                    richHtmlModal: {
                        isShow: false,
                        html: ""
                    }
                };
            },
            mounted() {
                this.loadPage();
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible')
                        this.loadPage();
                });
                if (getQueryString("click_header_button"))
                    for (var i in tableObj.header_buttons)
                        if (tableObj.header_buttons[i].title == getQueryString("click_header_button"))
                            this.onHeaderButtonClick(tableObj.header_buttons[i]);
            },
            methods: {
                getApiButtonByColumn(col){
                    let ret = this.createFormModal.apiButtons.filter((item)=>{
                        return item.column === col;
                    });
                    if(ret.length === 0)
                        return null;
                    return ret[0];
                },
                async callApi(url){
                    const param = {};
                    this.createFormModal.columns.map((col) => {
                        if (col.type === 'COLUMN_DISPLAY')
                            return;
                        param[col.col] = this.createFormModal.form[col.col];
                    });
                    for (let i in param) {
                        if (param[i] instanceof moment)
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
                        if (param[i] instanceof Array)
                            param[i] = JSON.stringify(param[i]);
                    }
                    try {
                        let res = await this.$api(url).method("POST").param(param).call();
                        if (!res.status)
                            throw res.msg;
                        for(let i in res.data)
                            this.createFormModal.form[i] = res.data[i];
                        if(res.msg)
                            this.$message.success(res.msg);
                    } catch (e) {
                        this.$message.error(e + "", 5);
                    }
                },
                openurl(url) {
                    window.open(url);
                },
                onShow() {
                    this.loadPage();
                },
                doSearch() {
                    return this.loadPage();
                },
                resetSearch() {
                    for (const i in this.searchObj)
                        this.searchObj[i] = getQueryString(i);
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
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
                        if (param[i] === null || param[i] === undefined)
                            param[i] = "";
                    }
                    let res = await this.$api(this.api.list).method("POST").param(param).call();
                    this.pagination.total = res.total;
                    this.pagination.pageSize = res.per_page;
                    this.dataSource = res.data;
                    this.statistic = res.statistic;
                },
                onAddClick() {
                    var params = [];
                    for (var i in this.searchObj)
                        params.push(i + "=" + this.searchObj[i]);
                    window.open(this.api.create_page + "?" + params.join("&"));
                },
                onEditClick(id) {
                    var params = [];
                    for (var i in this.searchObj) {
                        if (i == "id")
                            continue;
                        params.push(i + "=" + this.searchObj[i]);
                    }
                    window.open(this.api.edit_page + "?id=" + id + "&" + params.join("&"));
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
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
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
                    } else if (headerButtonItem.btn_do_type === "navigate") {
                        const urlEncode = function (param, key, encode) {
                            if (param == null)
                                return '';
                            let paramStr = '';
                            let t = typeof (param);
                            if (t === 'string' || t === 'number' || t === 'boolean') {
                                paramStr += '&' + key + '=' + ((encode == null || encode) ? encodeURIComponent(param) : param);
                            } else {
                                for (let i in param) {
                                    let k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
                                    paramStr += urlEncode(param[i], k, encode);
                                }
                            }
                            return paramStr;
                        };
                        let params = {};
                        for (let key in headerButtonItem.dest_col) {
                            if (record[key] !== undefined)
                                params[headerButtonItem.dest_col[key]] = record[key];
                            else
                                params[headerButtonItem.dest_col[key]] = getQueryString(key);
                        }
                        if (headerButtonItem.url.includes("?"))
                            window.open(headerButtonItem.url + "&" + urlEncode(params));
                        else
                            window.open(headerButtonItem.url + "?" + urlEncode(params));
                    } else if (headerButtonItem.btn_do_type === "api_form") {
                        this.showCreateFormModal(headerButtonItem, null);
                    } else if (headerButtonItem.btn_do_type === "rich_text") {
                        const html = await this.$api(headerButtonItem.html + location.search).method("GET").call();
                        if (!html.status)
                            return this.$message.error(html.msg);
                        this.richHtmlModal.html = html.data;
                        this.richHtmlModal.isShow = true;
                    } else if (headerButtonItem.btn_do_type.startsWith("blob:")) {
                        try {
                            const blob = await this.$api(headerButtonItem.url).method("POST").param(param).setBlob(true).call();
                            var downloadElement = document.createElement("a");
                            var href = window.URL.createObjectURL(blob);
                            downloadElement.href = href;
                            downloadElement.download = headerButtonItem.btn_do_type.substring("blob:".length);
                            document.body.appendChild(downloadElement);
                            downloadElement.click();
                            document.body.removeChild(downloadElement);
                            window.URL.revokeObjectURL(href);
                        } catch (e) {
                            this.$message.error("文件导出时发生了错误：" + e, 5);
                        }
                    }
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
                    } else if (rowButtonItem.btn_do_type === "navigate") {
                        if (typeof (rowButtonItem.dest_col) === "string") {
                            if (rowButtonItem.url.includes("?"))
                                window.open(rowButtonItem.url + "&" + rowButtonItem.dest_col + "=" + id);
                            else
                                window.open(rowButtonItem.url + "?" + rowButtonItem.dest_col + "=" + id);
                        } else {
                            const urlEncode = function (param, key, encode) {
                                if (param == null)
                                    return '';
                                let paramStr = '';
                                let t = typeof (param);
                                if (t === 'string' || t === 'number' || t === 'boolean') {
                                    paramStr += '&' + key + '=' + ((encode == null || encode) ? encodeURIComponent(param) : param);
                                } else {
                                    for (let i in param) {
                                        let k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
                                        paramStr += urlEncode(param[i], k, encode);
                                    }
                                }
                                return paramStr;
                            };
                            let params = {};
                            for (let key in rowButtonItem.dest_col) {
                                if (record[key] !== undefined)
                                    params[rowButtonItem.dest_col[key]] = record[key];
                                else
                                    params[rowButtonItem.dest_col[key]] = getQueryString(key);
                            }
                            if (rowButtonItem.url.includes("?"))
                                window.open(rowButtonItem.url + "&" + urlEncode(params));
                            else
                                window.open(rowButtonItem.url + "?" + urlEncode(params));
                        }
                    } else if (rowButtonItem.btn_do_type === "api_form") {
                        this.showCreateFormModal(rowButtonItem, record);
                    } else if (rowButtonItem.btn_do_type === "rich_text") {
                        const html = await this.$api(rowButtonItem.html).method("GET").param({
                            id: id
                        }).call();
                        if (!html.status)
                            return this.$message.error(html.msg);
                        this.richHtmlModal.html = html.data;
                        this.richHtmlModal.isShow = true;
                    } else if (rowButtonItem.btn_do_type.startsWith("blob:")) {
                        try {
                            const blob = await this.$api(rowButtonItem.url).method("GET").param({
                                id: id
                            }).setBlob(true).call();
                            var downloadElement = document.createElement("a");
                            var href = window.URL.createObjectURL(blob);
                            downloadElement.href = href;
                            downloadElement.download = rowButtonItem.btn_do_type.substring("blob:".length);
                            document.body.appendChild(downloadElement);
                            downloadElement.click();
                            document.body.removeChild(downloadElement);
                            window.URL.revokeObjectURL(href);
                        } catch (e) {
                            this.$message.error("文件导出时发生了错误：" + e, 5);
                        }
                    }
                },
                async showCreateFormModal(rowButtonItem, record) {
                    const that = this;
                    let form = {};
                    rowButtonItem.extra.columns.map((col) => {
                        if (col.type === 'COLUMN_CHECKBOX' || col.type === 'COLUMN_PICTURES' || col.type === 'COLUMN_FILES' || col.type === 'COLUMN_CHOOSE')
                            form[col.col] = [];
                        else if (col.type === 'COLUMN_TIMESTAMP')
                            form[col.col] = moment();
                        else
                            form[col.col] = "";
                    });
                    if (typeof (rowButtonItem.extra.default_values) === "string") {
                        let res = await this.$api(rowButtonItem.extra.default_values).method("POST").param({
                            row: record
                        }).call();
                        if (res.status === 0)
                            return that.$message.error(res.msg, 5);
                        Object.assign(form, res.data);
                    } else {
                        if(rowButtonItem.extra.default_values != [])
                            Object.assign(form, rowButtonItem.extra.default_values);
                    }
                    this.createFormModal = {
                        columns: rowButtonItem.extra.columns,
                        form: form,
                        formTip: {},
                        apiButtons: rowButtonItem.extra.columns_api_button,
                        isShow: true,
                        onSubmit: async () => {
                            let res = await this.$api(rowButtonItem.url).method("POST").param({
                                row: record,
                                form: that.createFormModal.form
                            }).call();
                            if (res.status === 0)
                                return that.$message.error(res.data, 5);
                            if (res.type === "rich_text") {
                                this.richHtmlModal.html = res.html;
                                this.richHtmlModal.isShow = true;
                            } else {
                                that.$message.success(res.msg);
                                that.createFormModal.isShow = false;
                                that.loadPage();
                            }
                        },
                        submitText: "提交"
                    };
                }
            }
        });
    </script>
@endsection
