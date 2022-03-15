<template>
	<a-row class="antoa-list-filter-item">
		<a-col :span="8">
			<div class="antoa-list-filter-label">
				{{item.tip}}
			</div>
		</a-col>
		<a-col :span="16">
			<a-input :value="value" @change="$emit('input', $event.target.value);$forceUpdate()" :placeholder="'请选择' + item.tip" style="width:100%"></a-input>
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