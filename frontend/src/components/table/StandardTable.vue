<template>
	<div class="standard-table">
		<div class="alert">
			<a-alert type="info" :show-icon="true" v-if="selectedRows">
				<div class="message" slot="message">
					已选择&nbsp;<a>{{selectedRows.length}}</a>&nbsp;项
					<a class="clear" @click="onClear">清空</a>
					<a class="clear" @click="isColumnFilterModalShow=true" style="margin-right: 10px">菜单列</a>
					<template v-for="(item, index) in needTotalList">
						<div v-if="item.needTotal" :key="index">
							{{item.title}}总计&nbsp;
							<a>{{item.customRender ? item.customRender(item.total) : item.total}}</a>
						</div>
					</template>
				</div>
			</a-alert>
		</div>
		<a-table :bordered="bordered" :loading="loading" :columns="columnsDisplay" :dataSource="dataSource"
				 :rowKey="rowKey"
				 :pagination="pagination" :expandedRowKeys="expandedRowKeys" :expandedRowRender="expandedRowRender"
				 @change="onChange" :scroll="{x: true}"
				 :rowSelection="selectedRows ? {selectedRowKeys: selectedRowKeys, onChange: updateSelect} : undefined">
			<template slot-scope="text, record, index" :slot="slot"
					  v-for="slot in Object.keys($scopedSlots).filter(key => key !== 'expandedRowRender') ">
				<slot :name="slot" v-bind="{text, record, index}"></slot>
			</template>
			<template :slot="slot" v-for="slot in Object.keys($slots)">
				<slot :name="slot"></slot>
			</template>
			<template slot-scope="record, index, indent, expanded"
					  :slot="$scopedSlots.expandedRowRender ? 'expandedRowRender' : ''">
				<slot v-bind="{record, index, indent, expanded}"
					  :name="$scopedSlots.expandedRowRender ? 'expandedRowRender' : ''"></slot>
			</template>
		</a-table>
		<a-modal v-model="isColumnFilterModalShow" :footer="null">
			<div>
				<a-checkbox :checked="item.scopedSlots ? !hideColumnKeys.includes(item.scopedSlots.customRender) : true"
							style="width: 100%" :disabled="!item.scopedSlots" v-for="item in columns"
							@change="onItemSelectedChange(item)">
					{{ item.title }}
				</a-checkbox>
			</div>
		</a-modal>
	</div>
</template>

<script>
	export default {
		name: 'StandardTable',
		props: {
			bordered: Boolean,
			loading: [Boolean, Object],
			columns: Array,
			dataSource: Array,
			rowKey: {
				type: [String, Function],
				default: 'key'
			},
			pagination: {
				type: [Object, Boolean],
				default: true
			},
			selectedRows: Array,
			expandedRowKeys: Array,
			expandedRowRender: Function
		},
		data() {
			return {
				needTotalList: [],
				hideColumnKeys: [],
				isColumnFilterModalShow: false
			}
		},
		methods: {
			updateSelect(selectedRowKeys, selectedRows) {
				this.$emit('update:selectedRows', selectedRows)
				this.$emit('selectedRowChange', selectedRowKeys, selectedRows)
			},
			initTotalList(columns) {
				const totalList = columns.filter(item => item.needTotal)
					.map(item => {
						return {
							...item,
							total: 0
						}
					})
				return totalList
			},
			onClear() {
				this.updateSelect([], [])
				this.$emit('clear')
			},
			onChange(pagination, filters, sorter, {
				currentDataSource
			}) {
				this.$emit('change', pagination, filters, sorter, {
					currentDataSource
				})
			},
			onItemSelectedChange(item) {
				if (!item.scopedSlots)
					return;
				if (this.hideColumnKeys.includes(item.scopedSlots.customRender))
					this.hideColumnKeys = this.hideColumnKeys.filter(t => t !== item.scopedSlots.customRender);
				else
					this.hideColumnKeys.push(item.scopedSlots.customRender);
				this.$forceUpdate();
			}
		},
		created() {
			this.needTotalList = this.initTotalList(this.columns)
		},
		watch: {
			selectedRows(selectedRows) {
				this.needTotalList = this.needTotalList.map(item => {
					return {
						...item,
						total: selectedRows.reduce((sum, val) => {
							let v
							try {
								v = val[item.dataIndex] ? val[item.dataIndex] : eval(
									`val.${item.dataIndex}`);
							} catch (_) {
								v = val[item.dataIndex];
							}
							v = !isNaN(parseFloat(v)) ? parseFloat(v) : 0;
							return sum + v
						}, 0)
					}
				})
			}
		},
		computed: {
			selectedRowKeys() {
				return this.selectedRows.map(record => {
					return (typeof this.rowKey === 'function') ? this.rowKey(record) : record[this.rowKey]
				})
			},
			columnsDisplay() {
				return this.columns.filter((t) => {
					if (!t.scopedSlots)
						return true;
					return !this.hideColumnKeys.includes(t.scopedSlots.customRender);
					//t.title
				});
			}
		}
	}
</script>

<style scoped lang="less">
	.standard-table {
		.alert {
			margin-bottom: 16px;

			.message {
				a {
					font-weight: 600;
				}
			}

			.clear {
				float: right;
			}
		}
	}

	/deep/ .ant-table-column-title {
		white-space: nowrap;
	}
</style>
