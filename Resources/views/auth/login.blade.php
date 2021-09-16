<!DOCTYPE html>
<html lang="zh-cn" class="beauty-scroll">
<head>
    <title>后台管理系统登录</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <link rel="stylesheet" href="{{ asset('/antoa/assets/antd.min.css') }}"/>
    <script src="{{ asset('/antoa/assets/vue.min.js') }}"></script>
    <script src="{{ asset('/antoa/assets/moment.min.js') }}"></script>
    <script src="{{ asset('/antoa/assets/antd.min.js') }}"></script>
    <script src="{{ asset('/antoa/assets/axios.min.js') }}"></script>
    <script src="{{ asset('/antoa/assets/api_caller.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/page-footer.js') }}"></script>
</head>
<body>
<div id="app">
    <div class="content">
        <div class="top">
            <div class="header">
                <span class="title">后台管理系统</span>
            </div>
            <div class="desc">后台管理系统登录</div>
        </div>
        <div class="login">
            <a-form @submit="onSubmit" :form="form">
                <a-form-item>
                    <a-input autocomplete="autocomplete" name="username" size="large"
                             :placeholder="'请输入' + username_placeholder"
                             v-decorator="['name', {rules: [{ required: true, message: '请输入账户名', whitespace: true}]}]"
                             v-model="username">
                        <a-icon slot="prefix" type="user"/>
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-input size="large" name="password" :placeholder="'请输入' + password_placeholder"
                             autocomplete="autocomplete" type="password"
                             v-decorator="['password', {rules: [{ required: true, message: '请输入密码', whitespace: true}]}]"
                             v-model="password">
                        <a-icon slot="prefix" type="lock"/>
                    </a-input>
                </a-form-item>
                <div>
                    <a-checkbox v-model="isAutoLogin">记住密码</a-checkbox>
                    <a style="float: right" @click="clearLoginStorage">清除记录</a>
                </div>
                <a-form-item>
                    <a-button :loading="logging" class="submitbtn" style="width: 100%;margin-top: 24px" size="large"
                              html-type="submit" type="primary">登录
                    </a-button>
                </a-form-item>
            </a-form>
        </div>
    </div>
    <page-footer></page-footer>
</div>
<script>
    window.vue = new Vue({
        el: '#app',
        data() {
            return {
                logging: false,
                error: '',
                form: this.$form.createForm(this),
                isAutoLogin: false,
                actionUrl: "/api/antoa/auth/login",
                username_placeholder: "请输入用户名",
                password_placeholder: "请输入密码",
                username: "",
                password: ""
            }
        },
        async created(){
            try {
                if (!localStorage.token)
                    throw "登录失败";
                const res = await this.$api("/api/antoa/auth/auth")
                    .param({
                        token: localStorage.token
                    })
                    .method("POST")
                    .call();
                if (!res.status)
                    throw "登录失效";
                location.href = "/antoa/home/home";
            } catch (e) {
                ;
            }
        },
        mounted() {
            try {
                let usernameAndPassword = localStorage.usernameAndPassword;
                if (!usernameAndPassword)
                    throw "没有发现本地存储的用户名密码";
                usernameAndPassword = JSON.parse(usernameAndPassword);
                this.username = usernameAndPassword.username;
                this.password = usernameAndPassword.password;
            } catch(e) {
                console.log(e);
            }
        },
        methods: {
            async onSubmit(e) {
                e.preventDefault();
                try {
                    this.form.validateFields((err) => {
                        if (!err) {
                            this.logging = true;
                            return true;
                        }
                    })
                    if (this.isAutoLogin)
                        localStorage.usernameAndPassword = JSON.stringify({
                            username: this.username,
                            password: this.password
                        });
                    const res = await this.$api(this.actionUrl)
                        .param({
                            username: this.username,
                            password: this.password
                        })
                        .method("POST")
                        .call();
                    if (!res.status)
                        throw res.msg;
                    localStorage.token = res.data;
                    this.logging = false;
                    location.href = "/antoa/home/home";
                }catch (e) {
                    this.$message.error(e + "");
                    this.logging = false;
                }
            },
            onClose() {
                this.error = false
            },
            clearLoginStorage() {
                delete localStorage.usernameAndPassword;
            }
        }
    });
</script>
<style>
    body {
        margin: 0;
        color: rgba(0, 0, 0, .65);
        font-size: 14px;
        font-family: -apple-system, BlinkMacSystemFont, Segoe UI, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Helvetica Neue, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
        font-variant: tabular-nums;
        line-height: 1.5;
        background-color: #fff;
    }

    #app {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: auto;
        background-color: #f0f2f5;
        background-image: url('https://gw.alipayobjects.com/zos/rmsportal/TVYTbAXWheQpRcWDaDMu.svg');
        background-repeat: no-repeat;
        background-position-x: center;
        background-position-y: 110px;
        background-size: 100%;
    }

    .content {
        padding: 32px 0;
        flex: 1;
    }

    @media (min-width: 768px) {
        .content {
            padding: 112px 0 24px;
        }
    }

    .top {
        text-align: center;
    }

    .top .header {
        height: 44px;
        line-height: 44px;
    }

    .top .header a {
        text-decoration: none;
    }

    .top .header .logo {
        height: 44px;
        vertical-align: top;
        margin-right: 16px;
    }

    .top .header .title {
        font-size: 33px;
        color: rgba(0, 0, 0, 0.85);
        font-family: 'Myriad Pro', 'Helvetica Neue', Arial, Helvetica, sans-serif;
        font-weight: 600;
        position: relative;
        top: 2px;
    }

    .top .desc {
        font-size: 14px;
        color: rgba(0, 0, 0, 0.45);
        margin-top: 12px;
        margin-bottom: 40px;
    }

    .login {
        width: 368px;
        margin: 0 auto;
    }

    @media screen and (max-width: 576px) {
        .login {
            width: 95%;
        }
    }

    @media screen and (max-width: 320px) {
        .login .captcha-button {
            font-size: 14px;
        }
    }

    .login .icon {
        font-size: 24px;
        color: rgba(0, 0, 0, 0.45);
        margin-left: 16px;
        vertical-align: middle;
        cursor: pointer;
        transition: color 0.3s;
    }

    .login .icon:hover {
        color: blue;
    }

    .submitbtn {
        color: #fff;
        background-color: #13c2c2;
        border-color: #13c2c2;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, .12);
        -webkit-box-shadow: 0 2px 0 rgba(0, 0, 0, .045);
        box-shadow: 0 2px 0 rgba(0, 0, 0, .045);
    }
</style>
</body>
</html>
