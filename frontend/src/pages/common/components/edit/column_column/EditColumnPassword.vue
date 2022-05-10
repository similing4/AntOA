<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<a-input-password :placeholder="'请填写' + column.tip + '，不修改密码则请留空'" :value="value" @change="onChange"></a-input-password>
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
					"type": "EditColumnPassword"
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
		onChange(e) {
			this.$emit("input", e.target.value);
		}
	}
}
</script>