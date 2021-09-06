<template>
	<a-card>
		<a-form v-if="api != null">
			<template v-for="(column,index) in columns">
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_TEXT'">
					<a-input :placeholder="'请填写' + column.tip" v-model="form[column.col]"></a-input>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_TEXTAREA'">
					<a-textarea :placeholder="'请填写' + column.tip" v-model="form[column.col]" rows="20" allow-clear>
					</a-textarea>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_PASSWORD'">
					<a-input-password :placeholder="'请填写' + column.tip" v-model="form[column.col]"></a-input-password>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_RADIO'">
					<a-radio-group v-model="form[column.col]" @change="$forceUpdate()">
						<a-radio :value="index" v-for="(column_i,index) in column.extra" :key="index">
							{{column_i}}
						</a-radio>
					</a-radio-group>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_SELECT'">
					<a-select v-model="form[column.col]">
						<a-select-option :value="index" v-for="(column_i,index) in column.extra" :key="index">
							{{column_i}}
						</a-select-option>
					</a-select>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_CHECKBOX'">
					<a-checkbox-group v-model="form[column.col]" @change="$forceUpdate()">
						<a-checkbox v-for="(column_i,index) in column.extra" :key="index" :value="index">
							{{column_i}}
						</a-checkbox>
					</a-checkbox-group>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_TIMESTAMP'">
					<a-date-picker show-time format="YYYY-MM-DD HH:mm:ss" :placeholder="'请选择' + column.tip"
						v-model="form[column.col]" @change="$forceUpdate()"></a-date-picker>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_RICHTEXT'">
					<wang-editor :id="form[column.col]" v-model="form[column.col]"></wang-editor>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_PICTURE'">
					<img :src="form[column.col]" v-if="form[column.col] != ''" style="width: 200px" />
					<a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
					</a-button>
					<upload-button @uploadfinished="form[column.col] = $event[0].response" accept="image/*"
						:multiple="false"></upload-button>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_FILE'">
					<a-button type="primary" @click="openurl(form[column.col])" v-if="form[column.col]!=''">下载
					</a-button>
					<a-button type="danger" @click="form[column.col] = ''" v-if="form[column.col]!=''">删除
					</a-button>
					<upload-button @uploadfinished="form[column.col] = $event[0].response" accept="*/*"
						:multiple="false"></upload-button>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_PICTURES'">
					<div v-for="(fileItem,index) in form[column.col]" :key="index">
						<img :src="fileItem" style="width: 200px" />
						<a-button type="danger"
							@click="form[column.col] = form[column.col].filter((t)=>{return t != fileItem;})">
							删除
						</a-button>
					</div>
					<upload-button
						@uploadfinished="form[column.col] = form[column.col].concat($event.map((t)=>{return t.response;}))"
						accept="image/*" :multiple="true"></upload-button>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_FILES'">
					<div v-for="(fileItem,index) in form[column.col]" :key="index">
						<a-button type="primary" @click="openurl(fileItem)">下载</a-button>
						<a-button type="danger"
							@click="form[column.col] = form[column.col].filter((t)=>{return t != fileItem;})">
							删除
						</a-button>
					</div>
					<upload-button
						@uploadfinished="form[column.col] = form[column.col].concat($event.map((t)=>{return t.response;}))"
						accept="*/*" :multiple="true"></upload-button>
				</a-form-item>
				<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}"
					v-if="column.type == 'COLUMN_CHILDREN_CHOOSE'">
					<column-children-choose v-model="form[column.col]" :tip.sync="formTip[column.col]" :column="column"
						:api="api" pagetype="create"></column-children-choose>
				</a-form-item>
			</template>
			<a-form-item style="display: flex;justify-content: center;">
				<a-button type="primary" @click="submit">创建</a-button>
				<a-button type="primary" style="margin-left: 8px;" @click="reset">重置</a-button>
			</a-form-item>
		</a-form>
		<confirm-dialog ref="confirmDialog"></confirm-dialog>
	</a-card>
</template>

<script>
	import moment from "moment";
	import StandardTable from "@/components/table/StandardTable.vue";
	import ConfirmDialog from "@/components/tool/ConfirmDialog.vue";
	import WangEditor from "@/components/tool/WangEditor.vue"
	import UploadButton from "@/components/tool/UploadButton.vue"
	import ColumnChildrenChoose from "./components/ColumnChildrenChoose.vue"
	export default {
		data() {
			return {
				columns: null,
				form: null,
				formTip: null,
				api: null
			};
		},
		components:{
			ConfirmDialog,
			StandardTable,
			WangEditor,
			UploadButton,
			ColumnChildrenChoose
		},
		async mounted() {
			try {
				var path = this.$route.path.substring(0, this.$route.path.length - "/create".length);
				const configUrl = "/api" + path + "/grid_config";
				const configRes = await this.$api(configUrl).method("GET").call();
				if (!configRes.status)
					throw configRes.msg;
				const tableObj = configRes.grid.create;
				const api = configRes.api;
				const form = {};
				const formTip = {};
				const getQueryString = (name) => {
					return this.$route.query[name] ? this.$route.query[name] : '';
				};
				tableObj.columns.map((col) => {
					if (col.type === 'COLUMN_CHECKBOX' || col.type === 'COLUMN_PICTURES' || col.type ===
						'COLUMN_FILES')
						form[col.col] = (getQueryString(col.col) !== '' ? JSON.parse(getQueryString(col.col)) :
							[]);
					else if (col.type === 'COLUMN_TIMESTAMP')
						form[col.col] = (getQueryString(col.col) !== '' ? getQueryString(col.col) : moment());
					else
						form[col.col] = getQueryString(col.col);
					if(col.type === 'COLUMN_CHILDREN_CHOOSE')
						formTip[col.col] = "";
				});
				this.columns = tableObj.columns
				this.form = form;
				this.formTip = formTip;
				this.api = api;
			} catch (e) {
				this.$message.error("配置加载错误：" + e, 5);
			}
		},
		methods: {
			openurl(url) {
				if (url.startsWith("http"))
					return window.open(url);
				var path = url;
				var query = "";
				if (url.includes("?")) {
					path = url.split("?")[0];
					query = url.split("?")[1];
				}
				if (path.endsWith("/create")){
					url = "/create?path=" + path.substring(0, path.length - "/create".length);
					if(query != '')
						url += "&" + query;
				}else if (path.endsWith("/edit")){
					url = "/edit?path=" + path.substring(0, path.length - "/edit".length);
					if(query != '')
						url += "&" + query;
				}else if (path.endsWith("/list")){
					url = "/list?path=" + path.substring(0, path.length - "/list".length);
					if(query != '')
						url += "&" + query;
				}
				this.$router.push(url);
			},
			reset() {
				for (let i in this.columns) {
					if (this.columns[i].type === "COLUMN_HIDDEN")
						;
					else if (this.columns[i].type === 'COLUMN_CHECKBOX' || this.columns[i].type === 'COLUMN_PICTURES' ||
						this.columns[i].type === 'COLUMN_FILES')
						this.form[this.columns[i].col] = [];
					else if (this.columns[i].type === 'COLUMN_TIMESTAMP')
						this.form[this.columns[i].col] = moment();
					else
						this.form[i] = "";
					if(this.columns[i].type === 'COLUMN_CHILDREN_CHOOSE')
						this.formTip[this.columns[i].col] = "";
				}
			},
			async submit() {
				const param = {};
				this.columns.map((col) => {
					if (col.type === 'COLUMN_DISPLAY')
						return;
					param[col.col] = this.form[col.col];
				});
				for (let i in param) {
					if (param[i] instanceof moment)
						param[i] = param[i].format('YYYY-MM-DD HH:mm:ss');
					if (param[i] instanceof Array)
						param[i] = JSON.stringify(param[i]);
				}
				try {
					let res = await this.$api(this.api.create).method("POST").param(param).call();
					if (res.status) {
						this.$message.success(res.msg, 5);
						this.reset();
						this.$closePage(this.$route.path);
					} else
						throw res.msg;
				} catch (e) {
					this.$message.error(e + "", 5);
				}
			}
		}
	};
</script>

<style scoped>
	.antoa-list-filter-item {
		padding-bottom: 20px;
	}

	.antoa-list-operator {
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
