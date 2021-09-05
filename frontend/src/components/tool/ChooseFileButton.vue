<template>
	<a-upload name="file" :multiple="false" :customRequest="onUploadFile" :accept="accept" :showUploadList="false" @change="onChange">
		<a-button type="primary">
			<a-icon type="upload" /> {{uploadWord}}
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
				default: "导入"
			},
			accept: {
				type: String,
				default: "*"
			},
			filelimit: {
				type: Number,
				default: -1
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
					if(this.filelimit != -1)
						if(data.file.size > this.filelimit)
							throw "文件大小超过限制";
					var reader = new FileReader();  
					reader.onload = function() {
						data.onSuccess(this.result);
					};
					reader.readAsArrayBuffer(data.file); 
				}catch(e){
					data.onError(e);
				}
				/*
				for(var i in files){
					var res = await ossUpload(files[i])
					insert(res.url)
				}
				this.$message.success('上传成功')*/
			},
		}
	}
</script>
<style scoped></style>