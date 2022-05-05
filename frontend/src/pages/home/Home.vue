<template>
	<div class="new-page">
		<div class="mainWrapper">
		    <div class="mainWrapperFirstLine">
		        <div class="line"></div>
		        <div class="linetext">Based On Vue-Antd-Admin</div>
		        <div class="line"></div>
		    </div>
		    <div class="mainWrapperSecondLine">
		        <div>AntOA</div>
		        <div>后台管理系统</div>
		    </div>
		    <div class="mainWrapperFirstLine">
		        <div class="line"></div>
		        <div class="linetext">Developed By Shengxinyu</div>
		        <div class="line"></div>
		    </div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {};
	}
}
</script>
<style scoped lang="less">
.new-page {
	background-color: @base-bg-color;
	border-radius: 4px;
	min-height: 60vh;
	
	.mainWrapper{
	    width: 100%;
	    height: 60vh;
	    display: flex;
	    flex-direction: column;
	    justify-content: center;
	    align-items: center;
	    .mainWrapperFirstLine{
	        display: flex;
	        width: 60vw;
	        align-items: center;
	        .line{
	            flex: 1;
	            height: 1px;
	            background: #333;
	        }
	        .linetext{
	            flex: 1;
	            font-size: 18px;
	            color: #333;
	            text-align: center;
	        }
        }
        .mainWrapperSecondLine{
            display: flex;
            justify-content: space-around;
            font-size: 50px;
        }
    }
}
</style>