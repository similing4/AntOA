@extends('antoa::inc.base_inc')
@section('head')
    <script src="{{ asset('/antoa/assets/components/standard-table.js') }}"></script>
    <script src="{{ asset('/antoa/assets/components/confirm-dialog.js') }}"></script>
@endsection
@section('content')
    <div id="APP">
        @{{ helloWorld }}
    </div>
@endsection
@section('script')
    <script>
        window.APP_VUE = new Vue({
            el: "#APP",
            data() {
                return {
                    helloWorld: "欢迎使用AntOA后台管理系统"
                };
            }
        });
    </script>
@endsection
