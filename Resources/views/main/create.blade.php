@extends('antoa::inc.base_inc')
@section('head')
    <script src="{{ asset('/antoa/assets/components/standard-table.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/confirm-dialog.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/wang-editor.resource.min.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/wang-editor.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/upload-button.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/column-children-choose.js') }}"></script>
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
                <a-form>
                    <template v-for="(column,index) in columns">
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                            v-if="column.type == 'COLUMN_DISPLAY'" v-show="displayColumns.includes(column.col)">
                            <div v-if="!form[column.col]" v-html="column.extra"></div>
                            <div v-if="form[column.col]" v-html="form[column.col]"></div>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TEXT'" v-show="displayColumns.includes(column.col)">
                            <a-input :placeholder="'请填写' + column.tip" v-model="form[column.col]"></a-input>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_NUMBER_DIVIDE'" v-show="displayColumns.includes(column.col)">
                            <a-input-number :placeholder="'请填写' + column.tip" v-model="form[column.col]"></a-input-number> @{{column.extra.unit}}
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TEXTAREA'" v-show="displayColumns.includes(column.col)">
                            <a-textarea :placeholder="'请填写' + column.tip" v-model="form[column.col]" rows="20"
                                        allow-clear></a-textarea>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PASSWORD'" v-show="displayColumns.includes(column.col)">
                            <a-input-password :placeholder="'请填写' + column.tip"
                                              v-model="form[column.col]"></a-input-password>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_RADIO'" v-show="displayColumns.includes(column.col)">
                            <a-radio-group v-model="form[column.col]" @change="$forceUpdate()">
                                <a-radio :value="index" v-for="(column_i,index) in column.extra" :key="index">
                                    @{{column_i}}
                                </a-radio>
                            </a-radio-group>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_SELECT'" v-show="displayColumns.includes(column.col)">
                            <a-select v-model="form[column.col]">
                                <a-select-option :value="index" v-for="(column_i,index) in column.extra" :key="index">
                                    @{{column_i}}
                                </a-select-option>
                            </a-select>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHECKBOX'" v-show="displayColumns.includes(column.col)">
                            <a-checkbox-group v-model="form[column.col]" @change="$forceUpdate()">
                                <a-checkbox v-for="(column_i,index) in column.extra" :key="index" :value="index">
                                    @{{column_i}}
                                </a-checkbox>
                            </a-checkbox-group>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TIMESTAMP'" v-show="displayColumns.includes(column.col)">
                            <a-date-picker show-time format="YYYY-MM-DD HH:mm:ss" :placeholder="'请选择' + column.tip"
                                           v-model="form[column.col]"
                                           @change="$forceUpdate()"></a-date-picker>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_RICHTEXT'" v-show="displayColumns.includes(column.col)">
                            <wang-editor :id="form[column.col]" v-model="form[column.col]"></wang-editor>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PICTURE'" v-show="displayColumns.includes(column.col)">
                            <img :src="form[column.col]" v-if="form[column.col] != ''" style="width: 200px"/>
                            <a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
                            </a-button>
                            <upload-button @uploadfinished="form[column.col] = $event[0].response" accept="image/*"
                                           :multiple="false"></upload-button>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_FILE'" v-show="displayColumns.includes(column.col)">
                            <a-button type="primary" @click="openurl(form[column.col])" v-if="form[column.col]!=''">下载
                            </a-button>
                            <a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
                            </a-button>
                            <upload-button @uploadfinished="form[column.col] = $event[0].response" accept="*/*"
                                           :multiple="false"></upload-button>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PICTURES'" v-show="displayColumns.includes(column.col)">
                            <div v-for="(fileItem,index) in form[column.col]" :key="index">
                                <img :src="fileItem" style="width: 200px"/>
                                <a-button type="danger"
                                          @click="form[column.col] = form[column.col].filter((t)=>{return t != fileItem;})">
                                    删除
                                </a-button>
                            </div>
                            <upload-button
                                    @uploadfinished="form[column.col] = form[column.col].concat($event.map((t)=>{return t.response;}))"
                                    accept="image/*"
                                    :multiple="true"></upload-button>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_FILES'" v-show="displayColumns.includes(column.col)">
                            <div v-for="(fileItem,index) in form[column.col]" :key="index">
                                <a-button type="primary" @click="openurl(fileItem)">下载</a-button>
                                <a-button type="danger"
                                          @click="form[column.col] = form[column.col].filter((t)=>{return t != fileItem;})">
                                    删除
                                </a-button>
                            </div>
                            <upload-button
                                    @uploadfinished="form[column.col] = form[column.col].concat($event.map((t)=>{return t.response;}))"
                                    accept="*/*"
                                    :multiple="true"></upload-button>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHOOSE'" v-show="displayColumns.includes(column.col)">
                            <a-cascader :placeholder="'请选择' + column.tip" v-model="form[column.col]" :options="column.extra"></a-cascader>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHILDREN_CHOOSE'" v-show="displayColumns.includes(column.col)">
                            <column-children-choose v-model="form[column.col]" :tip.sync="formTip[column.col]"
                                :column="column" :api="api" pagetype="edit"></column-children-choose>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TREE_CHECKBOX'" v-show="displayColumns.includes(column.col)">
                            <a-tree-select v-model="form[column.col]" :tree-data="column.extra" style="width: 100%" tree-checkable :search-placeholder="'请选择' + column.tip"></a-tree-select>
                            <a-button v-if="getApiButtonByColumn(column.col)" :type="getApiButtonByColumn(column.col).type" @click="callApi(getApiButtonByColumn(column.col).url)">@{{getApiButtonByColumn(column.col).title}}</a-button>
                        </a-form-item>
                    </template>
                    <a-form-item style="display: flex;justify-content: center;">
                        <a-button type="primary" @click="submit">创建</a-button>
                        <a-button type="primary" style="margin-left: 8px;" @click="reset">重置</a-button>
                    </a-form-item>
                </a-form>
                <confirm-dialog ref="confirmDialog"></confirm-dialog>
            </a-card>
        </a-locale-provider>
    </div>
@endsection
@section('script')
    <script>
        const tableObj = {!! $grid->getCreateForm()->json() !!};
        const form = {};
        const formTip = {};
        const api = {!! json_encode($api) !!};
        const getQueryString = function (name) {
            const reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
            const r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return "";
        };
        tableObj.columns.map((col) => {
            if (col.type === 'COLUMN_CHECKBOX' || col.type === 'COLUMN_PICTURES' || col.type === 'COLUMN_FILES' || col.type === 'COLUMN_CHOOSE' || col.type === 'COLUMN_TREE_CHECKBOX')
                form[col.col] = (getQueryString(col.col) !== '' ? JSON.parse(getQueryString(col.col)) : []);
            else if (col.type === 'COLUMN_TIMESTAMP')
                form[col.col] = (getQueryString(col.col) !== '' ? getQueryString(col.col) : moment());
            else
                form[col.col] = getQueryString(col.col);
        });
        window.APP_VUE = new Vue({
            el: "#APP",
            data() {
                return {
                    lang: antd.locales.zh_CN,
                    columns: tableObj.columns,
                    defaultValues: tableObj.default_values,
                    apiButtons: tableObj.columns_api_button,
                    form: form,
                    formTip: formTip,
                    api: api,
                    displayColumns: []
                };
            },
            created(){
                this.setDefaultValues();
                this.columns.map((col) => {
                    this.displayColumns.push(col.col);
                });
                this.setWatchHook(tableObj);
            },
            methods: {
                setWatchHook(tableObj){
                    if(!tableObj.change_hook)
                        return;
                    this.onHookCall();
                    tableObj.change_hook.map((col)=>{
                        this.$watch("form." + col,()=>{
                            this.onHookCall();
                        });
                    });
                },
                async onHookCall(){
                    const param = {};
                    this.columns.map((col) => {
                        if (col.type === 'COLUMN_DISPLAY')
                            return;
                        if(col.type === 'COLUMN_NUMBER_DIVIDE')
                            return param[col.col] = parseFloat(this.form[col.col]) * col.divide;
                        param[col.col] = this.form[col.col];
                    });
                    for (let i in param) {
                        if (param[i] instanceof moment)
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
                        if (param[i] instanceof Array)
                            param[i] = JSON.stringify(param[i]);
                    }
                    try {
                        let res = await this.$api(this.api.api_column_change).method("POST").param({
                            type: "create",
                            form: param
                        }).call();
                        if (res.status){
                            res = res.data;
                            for (let i in this.columns) {
                                if (res.data[this.columns[i].col] !== undefined) {
                                    if (this.columns[i].type === 'COLUMN_CHECKBOX' || this.columns[i].type ===
                                        'COLUMN_PICTURES' || this.columns[i].type === 'COLUMN_FILES' || this.columns[i].type === 'COLUMN_CHOOSE' || this.columns[i].type === 'COLUMN_TREE_CHECKBOX')
                                        this.form[this.columns[i].col] = JSON.parse(res.data[this.columns[i].col]);
                                    else if (this.columns[i].type === 'COLUMN_TIMESTAMP')
                                        this.form[this.columns[i].col] = moment(res.data[this.columns[i].col],
                                            "YYYY-MM-DD HH:mm:ss");
                                    else
                                        this.form[this.columns[i].col] = res.data[this.columns[i].col] + "";
                                }
                                if (this.columns[i].type === 'COLUMN_CHILDREN_CHOOSE')
                                    this.formTip[this.columns[i].col] = '';
                            }
                            if(!res.display && res.display !== [])
                                return;
                            this.displayColumns = res.display;
                        }
                    } catch (e) {
                        this.$message.error(e + "", 5);
                    }
                },
                async callApi(url){
                    const param = {};
                    tableObj.columns.map((col) => {
                        if (col.type === 'COLUMN_DISPLAY')
                            return;
                        if (col.type === 'COLUMN_NUMBER_DIVIDE')
                            return param[col.col] = parseFloat(this.form[col.col]) * col.divide;
                        param[col.col] = this.form[col.col];
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
                            this.form[i] = res.data[i];
                        if(res.msg)
                            this.$message.success(res.msg);
                    } catch (e) {
                        this.$message.error(e + "", 5);
                    }
                },
                getApiButtonByColumn(col){
                    let ret = this.apiButtons.filter((item)=>{
                        return item.column === col;
                    });
                    if(ret.length === 0)
                        return null;
                    return ret[0];
                },
                openurl(url) {
                    window.open(url);
                },
                reset() {
                    for (let i in this.columns) {
                        if (this.columns[i].type === "COLUMN_HIDDEN")
                            ;
                        else if (this.columns[i].type === 'COLUMN_CHECKBOX' || this.columns[i].type === 'COLUMN_PICTURES' || this.columns[i].type === 'COLUMN_FILES' || this.columns[i].type === 'COLUMN_CHOOSE' || this.columns[i].type === 'COLUMN_TREE_CHECKBOX')
                            form[this.columns[i].col] = (getQueryString(this.columns[i].col) !== '' ? JSON.parse(getQueryString(this.columns[i].col)) : []);
                        else if (this.columns[i].type === 'COLUMN_TIMESTAMP')
                            form[this.columns[i].col] = (getQueryString(this.columns[i].col) !== '' ? getQueryString(this.columns[i].col) : moment());
                        else
                            form[this.columns[i].col] = getQueryString(this.columns[i].col);
                    }
                    this.setDefaultValues();
                },
                setDefaultValues(){
                    for(var i in this.defaultValues)
                        this.form[i] = this.defaultValues[i];
                },
                async submit() {
                    const param = {};
                    tableObj.columns.map((col) => {
                        if (col.type === 'COLUMN_DISPLAY')
                            return;
                        if(col.type === 'COLUMN_NUMBER_DIVIDE')
                            return param[col.col] = parseFloat(this.form[col.col]) * col.divide;
                        param[col.col] = this.form[col.col];
                    });
                    for (let i in param) {
                        if (param[i] instanceof moment)
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
                        if (param[i] instanceof Array)
                            param[i] = JSON.stringify(param[i]);
                    }
                    try {
                        let res = await this.$api(this.api.create).method("POST").param(param).call();
                        if (res.status) {
                            this.$message.success(res.data, 5);
                            this.reset();
                            window.close();
                        } else
                            throw res.msg;
                    } catch (e) {
                        this.$message.error(e + "", 5);
                    }
                }
            }
        });
    </script>
@endsection
