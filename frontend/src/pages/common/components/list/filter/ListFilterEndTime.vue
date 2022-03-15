<template>
	<a-row class="antoa-list-filter-item">
		<a-col :span="8" class="antoa-list-filter-label">
			<div class="antoa-list-filter-label">
				{{item.tip}}
			</div>
		</a-col>
		<a-col :span="16">
			<a-date-picker :value="moment(value, 'YYYY-MM-DD HH:mm:ss')" @change="onDateChange" format="YYYY-MM-DD HH:mm:ss" :placeholder="'请选择' + item.tip" show-time style="width: 100%;" @panelChange="$forceUpdate()"></a-date-picker>
		</a-col>
	</a-row>
</template>
<script>
import moment from "moment";
export default {
	props: {
		item: {
			type: Object,
			default(){
				return {
					tip: ""
				}
			}
		},
		value: {
			type: String,
			default: ""
		}
	},
	data() {
		return {};
	},
	methods: {
		moment(val, format) {
			if(val == "")
				return "";
			return moment(val, format);
		},
		onDateChange(e) {
			this.$emit("input", e.format("YYYY-MM-DD HH:mm:ss"));
		}
	}
}
</script>
<style scoped lang="less">
.antoa-list-filter-item {
	padding-bottom: 20px;
}

.antoa-list-filter-label {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
	align-items: center;
	font-weight: 400;
	height: 32px;
	padding-right: 12px;
}
</style>