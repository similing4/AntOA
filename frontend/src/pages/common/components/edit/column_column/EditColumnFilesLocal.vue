<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<div v-for="(fileItem,index) in parse(value)" :key="index">
			<a-button type="primary" @click="openurl(fileItem)">下载</a-button>
			<a-button type="danger" @click="onChange(parse(value).filter((t)=>{return t != fileItem;}))">删除</a-button>
		</div>
		<upload-button @uploadfinished="onChange(parse(value).concat($event.map((t)=>{return t.response;})))" accept="*/*" :multiple="true"></upload-button>
		<slot />
	</a-form-item>
</template>
<script>
import UploadButton from "@/components/tool/UploadButtonLocal.vue"
export default {
	props: {
		column: {
			type: Object,
			default () {
				return {
					"col": "id",
					"tip": "",
					"default": "",
					"type": "EditColumnFiles",
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
	components: {
		UploadButton
	},
	methods: {
		parse(value) {
			try {
				return JSON.parse(value);
			} catch (e) {
				return [];
			}
		},
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
			this.$emit("input", JSON.stringify(e));
		}
	}
}
</script>