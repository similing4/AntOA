<template>
    <a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
        <div v-html="value"></div>
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
                    "type": "CreateColumnDisplay"
                };
            }
        },
        value: {
            type: String
        }
    },
    data() {
        return {};
    },
    methods: {
        onChange(e) {
            this.$emit("input", e.target.value);
        }
    }
}
</script>
