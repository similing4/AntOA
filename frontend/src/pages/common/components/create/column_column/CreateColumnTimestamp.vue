<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<a-date-picker show-time format="YYYY-MM-DD HH:mm:ss" :placeholder="'请选择' + column.tip" :value="parse(value)" @change="onChange"></a-date-picker>
		<slot />
	</a-form-item>
</template>
<script>
import moment from "moment";
export default {
	props: {
		column: {
			type: Object,
			default () {
				return {
					"col": "id",
					"tip": "",
					"default": "",
					"type": "CreateColumnTimestamp"
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
		parse(val) {
			if (val)
				return moment(val, "YYYY-MM-DD HH:mm:ss");
			return null;
		},
		onChange(e) {
			this.$emit("input", e.format("YYYY-MM-DD HH:mm:ss"));
		}
	}
}
</script>
