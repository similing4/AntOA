<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<wang-editor :value="value" @input="onChange"></wang-editor>
		<slot />
	</a-form-item>
</template>
<script>
import WangEditor from "@/components/tool/WangEditor.vue";
export default {
	props: {
		column: {
			type: Object,
			default () {
				return {
					"col": "id",
					"tip": "",
					"default": "",
					"type": "EditColumnRichText"
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
			type: String
		}
	},
	data() {
		return {};
	},
	components: {
		WangEditor
	},
	methods: {
		onChange(e) {
			this.$emit("input", e);
		}
	}
}
</script>