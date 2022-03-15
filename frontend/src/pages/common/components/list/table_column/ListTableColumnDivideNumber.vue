<template>
	<div>{{parseFloat(value) / render.divide}}
		{{render.unit}}
	</div>
</template>
<script>
export default {
	props: {
		render: {
			type: Object,
			default () {
				return {
					"type": "ListTableColumnDivideNumber",
					"col": "",
					"tip": "",
					"divide": 1,
					"unit": ""
				};
			}
		},
		value: {
			type: [String, Number],
			default: 0
		}
	},
	data() {
		return {};
	}
}
</script>