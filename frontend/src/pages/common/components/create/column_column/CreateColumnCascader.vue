<template>
    <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
        <a-cascader :placeholder="'请选择' + column.tip" :value="parse(value)" @change="onChange" :options="column.enum">
        </a-cascader>
        <slot />
    </a-form-item>
</template>
<script>
export default {
    props: {
        column: {
            type: Object,
            default () {
                return {
                    "col": "id",
                    "tip": "",
                    "default": "",
                    "type": "CreateColumnCascader",
                    "enum": [] //{label:"",value:"",disabled:false,children:[]}
                };
            }
        },
        gridApiObject: {
            type: Object,
            default () {
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
            type: String
        }
    },
    data() {
        return {};
    },
    methods: {
        parse(value) {
            try {
                return JSON.parse(value);
            } catch (e) {
                return [];
            }
        },
        onChange(res) {
            this.$emit("input", JSON.stringify(res));
        }
    }
}
</script>
