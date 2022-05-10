<template>
    <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
        <a-tree-select :value="parse(value)" @change="onChange" :tree-data="column.enum" style="width: 100%" tree-checkable :search-placeholder="'请选择' + column.tip"></a-tree-select>
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
                    "type": "EditColumnEnumTreeCheckBox",
                    "enum": [] // {title:"",value:"",disabled:false}
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
            type: [String, Number]
        }
    },
    data() {
        return {};
    },
    computed: {
        enums() {
            return this.column.enum.map((t) => {
                let ret = {};
                Object.assign(ret, t);
                ret.label = ret.title;
                delete ret.title;
                return ret;
            });
        }
    },
    methods: {
        parse(value) {
            try {
                return JSON.parse(value);
            } catch (e) {
                return [];
            }
        },
        onChange(e) {
            this.$emit("input", JSON.stringify(e));
        }
    }
}
</script>