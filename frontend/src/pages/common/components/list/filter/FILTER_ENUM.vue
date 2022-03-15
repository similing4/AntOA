<template>
	<a-row class="antoa-list-filter-item">
		<a-col :span="8">
			<div class="antoa-list-filter-label">
				{{item.tip}}
			</div>
		</a-col>
		<a-col :span="16">
			<a-select :value="value" @change="$emit('input', $event);$forceUpdate()" :placeholder="'请选择' + item.tip" :options="options" style="width:100%"></a-select>
		</a-col>
	</a-row>
</template>
<script>
import moment from "moment";
export default {
	props: {
		item: {
			type: Object,
			default () {
				return {
					tip: "",
					enum: [{
						disabled: false,
						value: 1,
						title: "男"
					},{
						disabled: false,
						value: 2,
						title: "女"
					}]
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
	computed: {
		options() {
			return [{ title: '不筛选', value: '' }].concat(this.item.enum);
		}
	},
	methods: {
		moment(val, format) {
			if (val == "")
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