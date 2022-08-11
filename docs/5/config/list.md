> 请求地址

```
/api{$配置路由}/list
```

> 调用方式：

```
POST JSON
```

> 接口描述：

获取当前配置路由对应的列表页数据，可以对列表页数据进行筛选、分页

> 请求参数:

* HEADER参数：

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| Authorization | Token请求头 | string | Y | 通过登录授权接口获取 | 

* GET参数:

无

* POST参数（JSON）:

| 字段名称 | 字段说明 | 类型 | 必填 | 备注 |
| ---- | ---- | ---- | ---- | ---- |
| page | 页数 | integer | Y | 当前页数 |
| ... | 筛选参数 | - | Y | 根据配置的filter系列方法变化而变化，具体应为《获取配置路由的配置信息》接口的grid属性的list属性的listFilterCollection响应字段每一项的col属性 |

> 请求返回结果:

* 成功时

```json
{
	"status": 1,
	"current_page": 1,
	"data": [{
		"id": 50,
		"name": "风韵杯",
		"reg_count": 10,
		"status": 1,
		"BUTTON_CONDITION_DATA": [true, true],
		"BUTTON_FINAL_URL_DATA": ["/race/register/list?race_id=50", "/race/group/list?race_id=50"],
		"detail_url": "https://similing.gitee.io/race?id=50"
	}],
	"first_page_url": "https://similing.gitee.io/api/race/home/list?page=1",
	"from": 1,
	"last_page": 4,
	"last_page_url": "https://similing.gitee.io/api/race/home/list?page=4",
	"next_page_url": "https://similing.gitee.io/api/race/home/list?page=2",
	"path": "https://similing.gitee.io/api/race/home/list",
	"per_page": 15,
	"prev_page_url": null,
	"to": 15,
	"total": 50,
	"statistic": ""
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
| data | 每行数据 | object | status为1时 | 每行数据并附带BUTTON_CONDITION_DATA、BUTTON_FINAL_URL_DATA字段。详见表后说明 |
| statistic | 配置的统计文本 | string | status为1时 | 由AntOAController子类实现的statistic方法决定 |
| current_page | 当前页码 | integer | status为1时 | laravel框架自带pagination返回值，从1开始 |
| path | 数据接口地址 | string | status为1时 | laravel框架自带pagination返回值 |
| per_page | 每一页数据行数 | integer | status为1时 | laravel框架自带pagination返回值（框架中固定为15） |
| total | 查询结果总数据行数 | integer | status为1时 | laravel框架自带pagination返回值 |
| last_page | 最后一页数据是第几页 | integer | status为1时 | laravel框架自带pagination返回值 |
| from | 当前页数据从第几行开始 | integer | status为1时 | laravel框架自带pagination返回值 |
| to | 当前页数据到第几行结束 | integer | status为1时 | laravel框架自带pagination返回值 |
| first_page_url | 第一页数据接口地址 | string | status为1时 | laravel框架自带pagination返回值 |
| last_page_url | 最后一页数据接口地址 | string | status为1时 | laravel框架自带pagination返回值 |
| prev_page_url | 上一页数据接口地址（没有上一页时为null） | string | status为1时 | laravel框架自带pagination返回值 |
| next_page_url | 下一页数据接口地址（没有下一页时为null） | string | status为1时 | laravel框架自带pagination返回值 |
| msg | 请求失败原因 | string | status为0时 | - |

* BUTTON_CONDITION_DATA、BUTTON_FINAL_URL_DATA字段

后端使用rowButton系列方法时由于每行数据的按钮显示条件、访问的链接不一致，故而产生列表接口每行数据的这两个字段。其数组内容与《获取配置路由的配置信息》接口响应值的list属性的listRowButtonCollection属性数组下标对应。

BUTTON_CONDITION_DATA：按钮是否显示。比如listRowButtonCollection值为

```
[{
	"buttonText": "报名记录",
	"buttonType": "primary",
	"baseUrl": "/race/register/list",
	"finalUrl": null,
	"type": "ListRowButtonNavigate"
}, {
	"buttonText": "比赛分组",
	"buttonType": "primary",
	"baseUrl": "/race/group/list",
	"finalUrl": null,
	"type": "ListRowButtonNavigate"
}]
```

而BUTTON_CONDITION_DATA值为

```
[
	true,
	false
]
```

那么这一行报名记录按钮就会显示，比赛分组按钮就不会显示。

BUTTON_FINAL_URL_DATA：按钮的请求地址。比如listRowButtonCollection值与上方一致，而BUTTON_FINAL_URL_DATA值为

```
	[
		"/race/register/list?race_id=50",
		"/race/group/list?race_id=50"
	]
```

那么这一行的报名记录按钮就会跳转到 /race/register/list?race_id=50 页，而比赛分组按钮则跳转到 /race/group/list?race_id=50 页。

> CURL请求示例

* 假设配置路由为/admin/user，筛选项只有name，则curl请求示例如下：

```
curl --location --request POST 'https://similing.gitee.io/api/admin/user/list' \
--header 'Authorization: 11_f42332f725ee9101a8b0ce39e55acdd1' \
--header 'Content-Type: application/json' \
--data '{"page":1,"name":""}'
```