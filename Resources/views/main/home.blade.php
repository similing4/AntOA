@extends('antoa::inc.base_inc')
@section('head')
    <script src="{{ asset('/antoa/assets/components/standard-table.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/confirm-dialog.js') }}"></script>
@endsection
@section('content')
    <div id="APP">
        <home-component></home-component>
    </div>
@endsection
@section('script')
    <script>
        window.APP_VUE = new Vue({
            el: "#APP",
            data() {
                return {};
            },
            methods: {
                openurl(url) {
                    window.open(url);
                }
            },
            components: {
                "HomeComponent": async function (recv) {
                    try {
                        if (!localStorage.homeVueApi)
                            throw "";
                        var api = localStorage.homeVueApi;
                        var res = await Vue.prototype.$api(api).method("GET").call();
                        if (!res.status)
                            throw res.msg;
                        return recv(eval("(()=>{return " + res.data + "})();"));
                    } catch (e) {
                        console.log(e);
                        return recv({
                            data() {
                                return {
                                    title: "后台管理系统首页",
                                };
                            },
                            template: `<div>@{{title}}</div>`
                        });
                    }
                }
            }
        });
    </script>
@endsection
