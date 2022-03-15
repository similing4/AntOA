<template>
	<div>
		{{ item.enum[value + ''] }}
	</div>
</template>
<script>
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