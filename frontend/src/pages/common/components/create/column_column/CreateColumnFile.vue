<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<a-button type="primary" @click="openurl(value)" v-if="value != ''">下载</a-button>
		<a-button type="danger" @click="onChange('')" v-if="value != ''">删除</a-button>
		<upload-button @uploadfinished="onChange($event[0].response)" accept="*/*" :multiple="false"></upload-button>
		<slot />
	</a-form-item>
</template>
<script>
import UploadButton from "@/components/tool/UploadButton.vue"
export default {
	props: {
		column: {
			type: Object,
			default () {
				return {
					"col": "id",
					"tip": "",
					"default": "",
					"type": "CreateColumnFile",
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
	components:{
		UploadButton
	},
	methods: {
		openurl(url) {
			if (url.startsWith("http"))
				return window.open(url);
			let beforePage = localStorage.beforePage;
			if (!beforePage)
				beforePage = "[]";
			beforePage = JSON.parse(beforePage);
			beforePage = beforePage.filter((item) => {
				return item.after != url;
			});
			beforePage.push({
				before: this.$route.fullPath,
				after: url
			});
			localStorage.beforePage = JSON.stringify(beforePage);
			this.$router.push(url);
		},
		onChange(e) {
			this.$emit("input", e);
		}
	}
}
</script>
