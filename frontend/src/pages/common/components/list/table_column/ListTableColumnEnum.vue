<template>
	<div>
		{{renderVal}}
	</div>
</template>
<script>
export default {
	props: {
		render: {
			type: Object,
			default () {
				return {
					"type": "ListTableColumnEnum",
					"col": "",
					"tip": "",
					"enum": [{
						"title": "",
						"value": "",
						"disabled": false
					}]
				};
			}
		},
		value: {
			type: [String, Number],
			default: 0
		}
	},
	computed: {
		renderVal() {
			for (let i = 0; i < this.render.enum; i++) {
				if (this.render.enum[i].value + "" == this.value + "")
					return this.render.enum[i].title;
			}
			return "";
		}
	},
	data() {
		return {};
	}
}
</script>