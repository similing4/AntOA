const ossUpload = Vue.prototype.$oss.upload;
Vue.component("UploadButton", {
    data() {
        return {
            successCount: 0,
            allCount: 0
        };
    },
    props: {
        uploadWord: {
            type: String,
            default: "上传"
        },
        multiple: {
            type: Boolean,
            default: true
        },
        accept: {
            type: String,
            default: "*"
        }
    },
    methods: {
        onChange(e) {
            this.successCount = e.fileList.filter((i) => {
                return i.status !== 'uploading';
            }).length;
            this.allCount = e.fileList.length;
            if (this.successCount === this.allCount) {
                let ret = e.fileList.filter((i) => {
                    return i.status === 'done';
                });
                this.$emit("uploadfinished", ret);
                for (let i in e.fileList)
                    e.fileList[i].status = 'removed';
            }
        },
        async onUploadFile(data) {
            try {
                let res = await ossUpload(data.file);
                data.onSuccess(res.url);
            } catch (e) {
                data.onError(e);
            }
        },
    },
    template: `<a-upload name="file" :multiple="multiple" :customRequest="onUploadFile" :accept="accept" :showUploadList="false" @change="onChange">
		<a-button>
			<a-icon type="upload" /> {{uploadWord}}{{allCount - successCount == 0 ? '' : '中（' + (allCount - successCount) + '个）'}} 
		</a-button>
	</a-upload>`
});
