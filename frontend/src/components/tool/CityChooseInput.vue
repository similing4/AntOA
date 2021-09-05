<template>
	<div>
		<a-cascader :options="options" @change="onSelected" :value="value" :placeholder="placeholder" />
	</div>
</template>
<script>
	import options from './city.js';
	export default {
		data() {
			return {
				options: options.options
			}
		},
		props: {
			value: {
				type: Array,
				required: true
			},
			placeholder: {
				type: String,
				default: "请选择城市"
			}
		},
		methods:{
			onSelected(t){
				this.$emit("input",t);
			}
		}
	}
</script>

<style>
</style>


  