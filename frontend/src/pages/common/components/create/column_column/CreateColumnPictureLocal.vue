<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<img :src="g(value)" v-if="value != ''" style="width: 200px" />
		<a-button type="danger" @click="onChange('')" v-if="value != ''">删除</a-button>
		<upload-button @uploadfinished="onChange($event[0].response)" accept="*/*" :multiple="false" :type="type" :col="column.col" :path="gridApiObject.api_upload"></upload-button>
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
					"type": "CreateColumnPicture",
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
		},
        type: {
            type: String,
            default: "create"
        },
        index: {
            type: [Number, String],
            default: 0
        }
	},
	data() {
		return {};
	},
	components:{
		UploadButton
	},
	methods: {
		onChange(e) {
			this.$emit("input", e);
		},
		g(url){
			return process.env.VUE_APP_API_BASE_URL + url
		}
	}
}
</script>
