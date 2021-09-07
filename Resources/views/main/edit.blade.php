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
                            v-if="column.type == 'COLUMN_DISPLAY'">
                            <div v-html="column.extra"></div>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TEXT'">
                            <a-input :placeholder="'请填写' + column.tip" v-model="form[column.col]"></a-input>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TEXTAREA'">
                            <a-textarea :placeholder="'请填写' + column.tip" v-model="form[column.col]" rows="20"
                                        allow-clear></a-textarea>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PASSWORD'">
                            <a-input-password :placeholder="'请填写' + column.tip + '，不修改密码则请留空'"
                                              v-model="form[column.col]"></a-input-password>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_RADIO'">
                            <a-radio-group v-model="form[column.col]" @change="$forceUpdate()">
                                <a-radio :value="index + ''" v-for="(column_i,index) in column.extra" :key="index + ''">
                                    @{{column_i}}
                                </a-radio>
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_SELECT'">
                            <a-select v-model="form[column.col]">
                                <a-select-option :value="index + ''" v-for="(column_i,index) in column.extra" :key="index + ''">
                                    @{{column_i}}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHECKBOX'">
                            <a-checkbox-group v-model="form[column.col]" @change="$forceUpdate()">
                                <a-checkbox v-for="(column_i,index) in column.extra" :key="index" :value="index">
                                    @{{column_i}}
                                </a-checkbox>
                            </a-checkbox-group>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_TIMESTAMP'">
                            <a-date-picker show-time format="YYYY-MM-DD HH:mm:ss" :placeholder="'请选择' + column.tip"
                                           v-model="form[column.col]"
                                           @change="$forceUpdate()"></a-date-picker>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_RICHTEXT'">
                            <wang-editor :id="form[column.col]" v-model="form[column.col]"></wang-editor>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PICTURE'">
                            <img :src="form[column.col]" v-if="form[column.col] != ''" style="width: 200px"/>
                            <a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
                            </a-button>
                            <upload-button @uploadfinished="form[column.col] = $event[0].response" accept="image/*"
                                           :multiple="false"></upload-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_FILE'">
                            <a-button type="primary" @click="openurl(form[column.col])" v-if="form[column.col]!=''">下载
                            </a-button>
                            <a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
                            </a-button>
                            <upload-button @uploadfinished="form[column.col] = $event[0].response" accept="*/*"
                                           :multiple="false"></upload-button>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_PICTURES'">
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
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_FILES'">
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
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHOOSE'">
                            <a-cascader :placeholder="'请选择' + column.tip" v-model="form[column.col]" :options="column.extra"></a-cascader>
                        </a-form-item>
                        <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
                                     v-if="column.type == 'COLUMN_CHILDREN_CHOOSE'">
                            <column-children-choose v-model="form[column.col]" :tip.sync="formTip[column.col]" v-if="formTip[column.col] !== undefined" :column="column" :api="api" pagetype="edit"></column-children-choose>
                        </a-form-item>
                    </template>
                    <a-form-item style="display: flex;justify-content: center;">
                        <a-button type="primary" @click="submit">修改</a-button>
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
        const tableObj = {!! $grid->getEditForm()->json() !!};
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
            if (col.type === 'COLUMN_CHECKBOX' || col.type === 'COLUMN_PICTURES' || col.type === 'COLUMN_FILES' || col.type === 'COLUMN_CHOOSE')
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
                    id: getQueryString("id"),
                    lang: antd.locales.zh_CN,
                    columns: tableObj.columns,
                    form: form,
                    formTip: formTip,
                    api: api
                };
            },
            mounted() {
                if (!this.id)
                    return this.$message.error("参数不正确", 5);
                this.reset();
            },
            methods: {
                openurl(url) {
                    window.open(url);
                },
                async reset() {
                    try {
                        let res = await this.$api(this.api.detail).method("GET").param({
                            id: this.id
                        }).call();
                        if (res.status) {
                            for (let i in this.columns) {
                                if (res.data[this.columns[i].col] !== undefined) {
                                    if (this.columns[i].type === 'COLUMN_CHECKBOX' || this.columns[i].type === 'COLUMN_PICTURES' || this.columns[i].type === 'COLUMN_FILES' || this.columns[i].type === 'COLUMN_CHOOSE')
                                        this.form[this.columns[i].col] = JSON.parse(res.data[this.columns[i].col]);
                                    else if (this.columns[i].type === 'COLUMN_TIMESTAMP')
                                        this.form[this.columns[i].col] = moment(res.data[this.columns[i].col], "YYYY-MM-DD HH:mm:ss");
                                    else
                                        this.form[this.columns[i].col] = res.data[this.columns[i].col] + "";
                                } else {
                                    if (this.columns[i].type === 'COLUMN_CHECKBOX' || this.columns[i].type === 'COLUMN_PICTURES' || this.columns[i].type === 'COLUMN_FILES')
                                        this.form[this.columns[i].col] = (getQueryString(this.columns[i].col) !== '' ? JSON.parse(getQueryString(this.columns[i].col)) : []);
                                    else if (this.columns[i].type === 'COLUMN_TIMESTAMP')
                                        this.form[this.columns[i].col] = (getQueryString(this.columns[i].col) !== '' ? getQueryString(this.columns[i].col) : moment());
                                    else
                                        this.form[this.columns[i].col] = getQueryString(this.columns[i].col);
                                }
                                if(this.columns[i].type === 'COLUMN_CHILDREN_CHOOSE'){
                                    if(res.tip[this.columns[i].col])
                                        this.formTip[this.columns[i].col] = res.tip[this.columns[i].col][this.columns[i].extra.displayColumn];
                                    else
                                        this.formTip[this.columns[i].col] = '';
                                }
                            }
                        } else
                            throw res.msg;
                    } catch (e) {console.log(e);
                        this.$message.error(e + "", 5);
                    }
                },
                async submit() {
                    const param = {};
                    tableObj.columns.map((col) => {
                        if (col.type === 'COLUMN_DISPLAY')
                            return;
                        param[col.col] = this.form[col.col];
                    });
                    for (let i in param) {
                        if (param[i] instanceof moment)
                            param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
                        if (param[i] instanceof Array)
                            param[i] = JSON.stringify(param[i]);
                    }
                    try {
                        let res = await this.$api(this.api.save).method("POST").param(param).call();
                        if (res.status) {
                            this.$message.success(res.msg, 5);
                            this.reset();
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
