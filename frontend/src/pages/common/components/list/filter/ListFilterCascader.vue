<template>
	<a-row class="antoa-list-filter-item">
		<a-col :span="8">
			<div class="antoa-list-filter-label">
				{{item.tip}}
			</div>
		</a-col>
		<a-col :span="16">
			<a-cascader :placeholder="'请选择' + item.tip" :value="parse(value)" @change="onChange" :options="item.enum"></a-cascader>
		</a-col>
	</a-row>
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
	},
	methods: {
		parse(value) {
			try {
				return JSON.parse(value);
			} catch (e) {
				return [];
			}
		},
		onChange(res) {
			this.$emit("input", JSON.stringify(res));
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
