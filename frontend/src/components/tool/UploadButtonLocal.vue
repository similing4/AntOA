<template>
	<a-upload name="file" :multiple="multiple" :customRequest="onUploadFile" :accept="accept" :showUploadList="false" @change="onChange">
		<a-button>
			<a-icon type="upload" /> {{uploadWord}}{{allCount - successCount == 0 ? '' : '中（' + (allCount - successCount) + '个）'}}
		</a-button>
	</a-upload>
</template>
<script>
	export default {
		data(){
			return {
				successCount: 0,
				allCount: 0
			};
		},
		props:{
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
			},
			type:{
				type: String,
				default: ""
			},
			col:{
				type: String,
				default: ""
			},
			path:{
				type: String,
				default: ""
			}
		},
		methods:{
			onChange(e){
				this.successCount = e.fileList.filter((i)=>{
					return i.status != 'uploading';
				}).length;
				this.allCount = e.fileList.length;
				if(this.successCount == this.allCount){
					var ret = e.fileList.filter((i)=>{
						return i.status == 'done';
					});
					this.$emit("uploadfinished",ret);
					for(var i in e.fileList)
						e.fileList[i].status = 'removed';
				}
			},
			async onUploadFile(data) {
				try{
					var res = await this.$api(this.path).param({
						type: this.type,
						col: this.col
					}).upload(data.file);
					console.log(res);
					data.onSuccess(res.data);
				}catch(e){console.log(e)
					data.onError(e);
				}
				/*
				for(var i in files){
					var res = await this.$oss.upload(files[i])
					insert(res.url)
				}
				this.$message.success('上传成功')*/
			},
		}
	}
</script>
<style scoped></style>
