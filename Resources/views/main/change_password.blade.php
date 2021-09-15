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
                    <a-form-item label="新密码" :label-col="{span: 7}" :wrapper-col="{span: 10}">
                        <a-input-password placeholder="请输入新密码" v-model="password"></a-input-password>
                    </a-form-item>
                    <a-form-item label="再次输入新密码" :label-col="{span: 7}" :wrapper-col="{span: 10}">
                        <a-input-password placeholder="请再次输入新密码" v-model="password2"></a-input-password>
                    </a-form-item>
                    <a-form-item style="display: flex;justify-content: center;">
                        <a-button type="primary" @click="submit">修改</a-button>
                    </a-form-item>
                </a-form>
            </a-card>
        </a-locale-provider>
    </div>
@endsection
@section('script')
    <script>
        window.APP_VUE = new Vue({
            el: "#APP",
            data() {
                return {
                    lang: antd.locales.zh_CN,
                    password: "",
                    password2: ""
                };
            },
            methods: {
                async submit() {
                    try {
                        let password = this.password;
                        if(password !== this.password2)
                            throw "两次密码输入不一致";
                        let res = await this.$api("/api/antoa/antoa/user/change_password").method("POST").param({
                            password: password
                        }).call();
                        if (res.status) {
                            this.$message.success(res.msg, 5);
                            this.password = "";
                            this.password2 = "";
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
