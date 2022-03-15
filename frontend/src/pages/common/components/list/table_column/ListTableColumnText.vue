<template>
	<div>
		{{value}}
	</div>
</template>
<script>
export default {
	props: {
		render: {
			type: Object,
			default () {
				return {
					"type": "ListTableColumnText",
					"col": "",
					"tip": ""
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