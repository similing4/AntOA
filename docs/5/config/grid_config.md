> 请求地址

```
/api{$配置路由}/grid_config
```

> 调用方式：

```
POST JSON
```

> 接口描述：

获取当前配置路由的配置信息

> 请求参数:

* HEADER参数：

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| Authorization | Token请求头 | string | Y | 通过登录授权接口获取 | 

* GET参数:

无

* POST参数（JSON）:

无（需传空对象{}）

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"grid": {
		"list": {
			"primaryKey": "id",
			"listFilterCollection": [{
				"col": "name",
				"tip": "比赛名称",
				"default": "",
				"type": "ListFilterText"
			}],
			"listTableColumnCollection": [{
				"col": "id",
				"tip": "ID",
				"type": "ListTableColumnText"
			}, {
				"col": "name",
				"tip": "比赛名称",
				"type": "ListTableColumnText"
			}, {
				"col": "reg_count",
				"tip": "报名人数",
				"type": "ListTableColumnText"
			}, {
				"col": "status",
				"tip": "状态",
				"type": "ListTableColumnEnum",
				"enum": [{
					"disabled": false,
					"value": 0,
					"title": "不可报名"
				}, {
					"disabled": false,
					"value": 1,
					"title": "可报名"
				}]
			}, {
				"col": "detail_url",
				"tip": "报名链接",
				"type": "ListTableColumnRichDisplay"
			}],
			"listHeaderButtonCollection": [],
			"listRowButtonCollection": [{
				"buttonText": "报名记录",
				"buttonType": "primary",
				"baseUrl": "\/race\/register\/list",
				"finalUrl": null,
				"type": "ListRowButtonNavigate"
			}, {
				"buttonText": "比赛分组",
				"buttonType": "primary",
				"baseUrl": "\/race\/group\/list",
				"finalUrl": null,
				"type": "ListRowButtonNavigate"
			}],
			"hasCreate": true,
			"hasEdit": true,
			"hasDelete": true
		},
		"create": {
			"primaryKey": "id",
			"createColumnCollection": [{
				"col": "name",
				"tip": "比赛名称",
				"default": "",
				"type": "CreateColumnText"
			}, {
				"col": "start_time",
				"tip": "比赛开始时间",
				"default": "",
				"type": "CreateColumnTimestamp"
			}, {
				"col": "register_end_time",
				"tip": "报名截止时间",
				"default": "",
				"type": "CreateColumnTimestamp"
			}, {
				"col": "money_explain",
				"tip": "奖金介绍",
				"default": "",
				"type": "CreateColumnRichText"
			}, {
				"col": "race_explain",
				"tip": "比赛简介",
				"default": "",
				"type": "CreateColumnRichText"
			}, {
				"col": "race_qq_number",
				"tip": "比赛QQ群",
				"default": "",
				"type": "CreateColumnText"
			}, {
				"col": "race_qq_url",
				"tip": "比赛QQ群加群链接",
				"default": "",
				"type": "CreateColumnText"
			}, {
				"col": "status",
				"tip": "状态",
				"default": "",
				"type": "CreateColumnEnumRadio",
				"enum": [{
					"disabled": false,
					"value": 0,
					"title": "不可报名"
				}, {
					"disabled": false,
					"value": 1,
					"title": "可报名"
				}]
			}],
			"createRowButtonBaseCollection": [],
			"createOrEditColumnChangeHookCollection": []
		},
		"edit": {
			"primaryKey": "id",
			"editColumnCollection": [{
				"col": "id",
				"tip": "",
				"default": "",
				"type": "EditColumnHidden"
			}, {
				"col": "name",
				"tip": "比赛名称",
				"default": "",
				"type": "EditColumnText"
			}, {
				"col": "start_time",
				"tip": "比赛开始时间",
				"default": "",
				"type": "EditColumnTimestamp"
			}, {
				"col": "register_end_time",
				"tip": "报名截止时间",
				"default": "",
				"type": "EditColumnTimestamp"
			}, {
				"col": "money_explain",
				"tip": "奖金介绍",
				"default": "",
				"type": "EditColumnRichText"
			}, {
				"col": "race_explain",
				"tip": "比赛简介",
				"default": "",
				"type": "EditColumnRichText"
			}, {
				"col": "race_qq_number",
				"tip": "比赛QQ群",
				"default": "",
				"type": "EditColumnText"
			}, {
				"col": "race_qq_url",
				"tip": "比赛QQ群加群链接",
				"default": "",
				"type": "EditColumnText"
			}, {
				"col": "status",
				"tip": "状态",
				"default": "",
				"type": "EditColumnEnumRadio",
				"enum": [{
					"disabled": false,
					"value": 0,
					"title": "不可报名"
				}, {
					"disabled": false,
					"value": 1,
					"title": "可报名"
				}]
			}, {
				"col": "winner1",
				"tip": "第一名（比赛结束后设置）",
				"default": "",
				"type": "EditColumnChildrenChoose",
				"gridListEasy": {
					"listFilterCollection": [{
						"col": "id",
						"tip": "",
						"default": "",
						"type": "ListFilterHidden"
					}],
					"listTableColumnCollection": [{
						"col": "game_uid",
						"tip": "雀魂UID",
						"type": "ListTableColumnText"
					}, {
						"col": "game_nickname",
						"tip": "雀魂昵称",
						"type": "ListTableColumnText"
					}],
					"listHeaderButtonCollection": [],
					"listRowButtonCollection": []
				},
				"gridListVModelCol": "game_uid",
				"gridListDisplayCol": "game_nickname"
			}, {
				"col": "winner2",
				"tip": "第二名（比赛结束后设置）",
				"default": "",
				"type": "EditColumnChildrenChoose",
				"gridListEasy": {
					"listFilterCollection": [{
						"col": "id",
						"tip": "",
						"default": "",
						"type": "ListFilterHidden"
					}],
					"listTableColumnCollection": [{
						"col": "game_uid",
						"tip": "雀魂UID",
						"type": "ListTableColumnText"
					}, {
						"col": "game_nickname",
						"tip": "雀魂昵称",
						"type": "ListTableColumnText"
					}],
					"listHeaderButtonCollection": [],
					"listRowButtonCollection": []
				},
				"gridListVModelCol": "game_uid",
				"gridListDisplayCol": "game_nickname"
			}],
			"editRowButtonBaseCollection": [],
			"createOrEditColumnChangeHookCollection": []
		}
	},
	"api": {
		"path": "api\/race\/home\/grid_config",
		"list": "\/api\/race\/home\/list",
		"create": "\/api\/race\/home\/create",
		"detail": "\/api\/race\/home\/detail",
		"save": "\/api\/race\/home\/save",
		"delete": "\/api\/race\/home\/delete",
		"detail_column_list": "\/api\/race\/home\/detail_column_list",
		"api_column_change": "\/api\/race\/home\/column_change",
		"api_upload": "\/api\/race\/home\/upload",
		"list_page": "\/race\/home\/list",
		"create_page": "\/race\/home\/create",
		"edit_page": "\/race\/home\/edit"
	}
}
```

* 失败时

```json
{
	"status": 0,
	"msg": "登录失效"
}
```

> 请求返回结果参数说明:

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| status | 响应码 | integer | 一定存在 | 值为1时请求成功，否则请求失败 | 
| grid | 配置路由的配置信息 | Grid | status为1时 | 详见Grid描述 |
| api | 所有可用的API与页面 | object | status为1时 | 详见范例即可，除page结尾为页面外其余均为接口 |
| msg | 请求失败原因 | string | status为0时 | - |

* Grid

该对象用于标识当前配置路由的配置信息：

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| list | 列表页信息 | GridList | 一定存在 | 如果后台没有配置列表页信息则该字段值为null | 
| create | 创建页信息 | GridCreateForm | 一定存在 | 如果后台没有配置创建页信息则该字段值为null |
| edit | 编辑页信息 | GridEditForm | 一定存在 | 如果后台没有配置编辑页信息则该字段值为null |

* GridList

该对象用于描述列表页的页面结构

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| primaryKey | 列表主键 | string | 一定存在 | 列表数据的主键，编辑功能开启时编辑按钮点击后跳转将携带该参数 | 
| listFilterCollection | 筛选项 | array\<ListFilter\> | 一定存在 | 页面上方的筛选项 |
| listHeaderButtonCollection | 顶部按钮项 | array\<HeaderButton\> | 一定存在 | 顶部按钮数组 |
| listRowButtonCollection | 每行按钮项 | array\<RowButton\> | 一定存在 | 每行数据后方除编辑、删除按钮外的按钮数组 |
| hasCreate | 是否有创建 | boolean | 一定存在 | - |
| hasDelete | 是否有删除 | boolean | 一定存在 | - |
| hasEdit | 是否有编辑 | boolean | 一定存在 | - |

* GridCreateForm

该对象用于描述创建页的页面结构

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| primaryKey | 列表主键 | string | 一定存在 | 列表数据的主键 | 
| createColumnCollection | 创建页表单项 | array\<CreateColumnBase\> | 一定存在 | - |
| createRowButtonBaseCollection | 表单项后的按钮项 | array\<CreateRowButtonBase\> | 一定存在 | 详见CreateRowButtonBase说明 |
| createOrEditColumnChangeHookCollection | 表单项变化监听事件 | array\<CreateOrEditColumnChangeHook\> | 一定存在 | 监听变化后调用接口 |

* GridEditForm

该对象用于描述编辑页的页面结构

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| primaryKey | 列表主键 | string | 一定存在 | 列表数据的主键，即传入编辑页的主键 | 
| editColumnCollection | 编辑页表单项 | array\<EditColumnBase\> | 一定存在 | - |
| editRowButtonBaseCollection | 表单项后的按钮项 | array\<EditRowButtonBase\> | 一定存在 | 详见EditRowButtonBase说明 |
| createOrEditColumnChangeHookCollection | 表单项变化监听事件 | array\<CreateOrEditColumnChangeHook\> | 一定存在 | 监听变化后调用接口 |

* ListFilter

该对象用于描述列表页上方的筛选项。后端使用filter系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台列表页》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 筛选项的类型，一般为服务端ListFilterBase对应子类类名，用于前端页面的不同展现形式 |
| col | 筛选的列 | string | 一定存在 | 筛选时传入键 |
| tip | 筛选的列提示 | string | 一定存在 | 展示在列表页该筛选项上用于标识该筛选项是什么 |
| default | 默认值 | string | 一定存在 | - |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* HeaderButton

该对象用于描述列表页上方的按钮项。后端使用headerButton系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台列表页》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 顶部按钮类型，一般为服务端ListHeaderButtonBase对应子类类名，用于前端页面的不同展现形式 |
| buttonText | 按钮文本内容 | string | 一定存在 | - |
| buttonType | 按钮类型 | string | 一定存在 | 详见AntDesign按钮的type属性 |
| baseUrl | 基础Url | string | 一定存在 | 该字段无用，请使用finalUrl |
| finalUrl | 最终请求地址 | string | 一定存在 | type不同作用不同，详见ListHeaderButtonBase对应子类描述 |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* RowButton

该对象用于描述列表页每行数据后除编辑、删除按钮外的按钮项。后端使用rowButton系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台列表页》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 顶部按钮类型，一般为服务端ListHeaderButtonBase对应子类类名，用于前端页面的不同展现形式 |
| buttonText | 按钮文本内容 | string | 一定存在 | - |
| buttonType | 按钮类型 | string | 一定存在 | 详见AntDesign按钮的type属性 |
| baseUrl | 基础Url | string | 一定存在 | 该字段无用，请使用每行响应数据的BUTTON_FINAL_URL_DATA字段 |
| finalUrl | 最终请求地址 | string | 一定存在 | 该字段无用，请使用每行响应数据的BUTTON_FINAL_URL_DATA字段 |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* CreateColumnBase

该对象用于描述创建页的表单项。后端使用column系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台创建页》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 表单项的类型，一般为服务端CreateColumnBase对应子类类名，用于前端页面的不同展现形式 |
| col | 字段 | string | 一定存在 | - |
| tip | 字段提示 | string | 一定存在 | 展示在创建页该表单项左侧用于标识该项是什么 |
| default | 默认值 | string | 一定存在 | - |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* CreateRowButtonBase

该对象用于描述创建页的表单项右侧按钮。后端使用GridCreateForm对象的按钮系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台创建页/GridCreateForm对象的按钮系列方法》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 按钮类型，一般为服务端CreateRowButtonBase对应子类类名，用于前端页面的不同展现形式 |
| bindCol | 绑定的字段 | string | 一定存在 | 用于指定该按钮绑定在哪个表单项后 |
| apiUrl | 最终请求地址 | string | 一定存在 | type不同作用不同，详见CreateRowButtonBase对应子类描述 |
| buttonText | 按钮文本内容 | string | 一定存在 | - |
| buttonType | 按钮类型 | string | 一定存在 | 详见AntDesign按钮的type属性 |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* EditColumnBase

该对象用于描述编辑页的表单项。后端使用column系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台编辑页》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 表单项的类型，一般为服务端EditColumnBase对应子类类名，用于前端页面的不同展现形式 |
| col | 字段 | string | 一定存在 | - |
| tip | 字段提示 | string | 一定存在 | 展示在编辑页该表单项左侧用于标识该项是什么 |
| default | 默认值 | string | 一定存在 | - |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* EditRowButtonBase

该对象用于描述编辑页的表单项右侧按钮。后端使用GridEditForm对象的按钮系列方法时对应接口会产生该对象。配置方法详见《Grid的使用/后台编辑页/GridEditForm对象的按钮系列方法》

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| type | 类型 | string | 一定存在 | 按钮类型，一般为服务端EditRowButtonBase对应子类类名，用于前端页面的不同展现形式 |
| bindCol | 绑定的字段 | string | 一定存在 | 用于指定该按钮绑定在哪个表单项后 |
| apiUrl | 最终请求地址 | string | 一定存在 | type不同作用不同，详见EditRowButtonBase对应子类描述 |
| buttonText | 按钮文本内容 | string | 一定存在 | - |
| buttonType | 按钮类型 | string | 一定存在 | 详见AntDesign按钮的type属性 |
| ... | 其它 | - | 根据后端实体类配置变化 | - |

* CreateOrEditColumnChangeHook

该对象用于描述表单项填充值变化监听事件。当用户填写表单时前端需以发起接口请求并处理响应值的方式触发该事件。创建或编辑页面初始化时所有事件应默认触发一次。

| 字段名称 | 字段说明 | 类型 | 存在条件 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| col | 绑定的字段 | string | 一定存在 | 用于指定哪个表单项产生变化后触发事件 |


> CURL请求示例

* 假设配置路由为/admin/user，则curl请求示例如下：

```
curl --location --request POST 'https://similing.gitee.io/api/admin/user/grid_config' \
--header 'Authorization: 11_f42332f725ee9101a8b0ce39e55acdd1' \
--header 'Content-Type: application/json' \
--data '{}'
```