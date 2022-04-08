<template>
    <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
        <a-input-number :placeholder="'请填写' + column.tip" :value="parseValue()" @change="onChange"></a-input-number> {{column.unit}}
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
                    "type": "EditColumnDivideNumber",
                    "divide": 1,
                    "unit": ""
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
                    save: ""
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
    methods: {
        parseValue(){
			if(isNaN(this.value))
				return 0;
			if(this.value === "")
				return 0;
			return parseFloat(this.value) / parseFloat(this.column.divide)
        },
        onChange(e) {
            this.$emit("input", parseFloat(e) * parseFloat(this.column.divide));
        },
    }
}
</script>
