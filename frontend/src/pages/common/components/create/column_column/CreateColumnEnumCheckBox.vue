<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<a-checkbox-group :value="parse(value)" @change="onChange" :options="enums">
		</a-checkbox-group>
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
					"type": "CreateColumnEnumCheckBox",
					"enum": [] //{title:"",value:"",disabled:false}
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
