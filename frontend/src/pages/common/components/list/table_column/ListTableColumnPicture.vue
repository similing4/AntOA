<template>
	<div><img :src="value" :style="{width:render.width, height:render.height}" /></div>
</template>
<script>
export default {
	props: {
		render: {
			type: Object,
			default () {
				return {
					"type": "ListTableColumnPicture",
					"col": "",
					"tip": "",
					"width": "100px",
					"height": "100px"
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