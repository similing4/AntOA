<template>
	<a-modal class="confirm" v-model="isTipShow" :title="title" @ok="onConfirmTip">
		<center>{{confirmText}}</center>
		<a-form-item :label="prompt_tip" :wrapperCol="{span: 10}" :labelCol="{span: 7}" v-if="prompt_tip">
			<a-input v-model="prompt_text" :placeholder="prompt_tip_placeholder"></a-input>
		</a-form-item>
	</a-modal>
</template>

<script>
	export default {
		name: 'ConfirmDialog',
		data() {
			return {
				isTipShow: false,
				title: "提示",
				confirmText: "",
				onConfirmTip: () => {},
				prompt_text: "",
				prompt_tip: false
			};
		},
		methods: {
			confirm(tip, title, prompt_tip, prompt_tip_placeholder) {
				this.prompt_text = "";
				this.prompt_tip = prompt_tip ? prompt_tip : "";
				this.prompt_tip_placeholder = prompt_tip_placeholder ? prompt_tip_placeholder : "";
				return new Promise((recv) => {
					this.isTipShow = true;
					this.title = (title ? title : "提示");
					this.confirmText = tip;
					this.onConfirmTip = () => {
						this.isTipShow = false;
						recv(this.prompt_text);
					};
				});
			}
		}
	}
</script>

<style>
</style>
