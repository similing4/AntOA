扩展主要由以下几部分的扩展组成
ListFilter 系列插件
ListTableColumn 系列插件
CreateColumn 系列插件
EditColumn 系列插件
下面我们逐一举例开发这几个插件的步骤：
## ListFilter 系列插件
### 命名规范
ListFilter系列插件命名需要以PluginListFilter开头，否则不识别。后端实体类返回json的type字段需要与前端的插件名称、后端插件类名一致。
### 插件前端部分编写
ListFilter系列插件开发需要新建对应的Module（模块）开发，前端部分你需要在对应的模块中创建antoa_components/PluginListFilter文件夹，在里面编写ListFilter系列插件的前端页面。

假设你的模块名称为AntOAPlugins，要创建的ListFilter系列插件为PluginListFilterTest，那么你需要在AntOAPlugins/antoa_components/PluginListFilter文件夹中创建PluginListFilterTest.vue文件并在内部编写代码。该文件是一个Vue的组件。下面以一个输入框搜索的插件编写方法举例：
```
<template>
	<a-row class="antoa-list-filter-item">
		<a-col :span="8">
			<div class="antoa-list-filter-label">
				{{item.tip}}
			</div>
		</a-col>
		<a-col :span="16">
			<a-input :value="value" @change="$emit('input', $event.target.value);$forceUpdate()" :placeholder="'请选择' + item.tip" style="width:100%"></a-input>
			<!--注意这里的$emit一定要调用$forceUpdate()-->
		</a-col>
	</a-row>
</template>
<script>
import moment from "moment";
export default {
	props: {
		item: { //这个item就是后端的jsonSerialize方法返回的数据。
			type: Object,
			default () {
				return {
					tip: ""
				}
			}
		},
		value: { //这里是搜索框内v-model的值，也可以说是对应字段传给后端的值
			type: String,
			default: ""
		}
	},
	data() {
		return {};
	}
}
</script>
<style scoped lang="less">
/*下面的样式推荐不要动。如果有特殊需求请针对页面优化*/
.antoa-list-filter-item {
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
```
编写完成后你需要到AntOA/frontend文件夹中重新使用yarn build进行编译才能使用。如果你是前端想在开发过程中使用你可以直接把整个网站代码down下来然后在AntOA/frontend文件夹中使用yarn serve 进行调试~

### 插件后端部分编写
ListFilter系列插件开发需要新建对应的Module（模块）开发，后端部分你可以在任意位置创建继承自Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase类的子类并重写父类onFilter方法来实现后端功能。

假设你的模块名称为AntOAPlugins，要创建的ListFilter系列插件为PluginListFilterTest，那么你可以在AntOAPlugins/Http/Requests中创建PluginListFilterTest类继承PluginListFilterBase。下面以一个输入框搜索的插件编写方法举例：
```
use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;

class PluginListFilterTest extends ListFilterBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "PluginListFilterTest" //这里必须要与类名同名，否则前端会报错
        ]);
    }
    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid){
    	//这里用于实现后端逻辑
        $param = $urlParamCalculator->getPageParamByKey($this->col);
        if ($param !== null && $param->val != '')
            $gridListDbObject->where($this->col, 'like', "%" . $param->val . "%");
    }
}

```
这里解释一下后端逻辑处理方法：
#### public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid);
前端发起筛选请求时供插件调用的方法。参数以外本实例自带的属性可以参考ListFilterBase类的定义，其中$this->col可以获取当前配置的字段是哪一个字段。
##### 参数
	- $gridListDbObject DBListOperator类型，GridList构造时传入的参数，你可以理解为数据库的Builder
	- $urlParamCalculator UrlParamCalculator类型，你可以通过这个对象获取前端页面传来的任意参数
	- $uid 当前登录的用户ID
##### 返回值
这个方法没有返回值，如果你不需要处理后端逻辑甚至可以不重写本方法。

### 使用
使用GridList的filter方法传入你定义的ListFilterBase子类实例即可。例：
```
$grid->list(new class(DB::table("user")) extends DBListOperator{})
	->columnText("username", "用户名")
	->filter(new PluginListFilterTest("username", "用户名"));
```

## ListTableColumn 系列插件
### 命名规范
ListTableColumn系列插件命名需要以PluginListTableColumn开头，否则不识别。后端实体类返回json的type字段需要与前端的插件名称、后端插件类名一致。

### 插件前端部分编写
ListTableColumn系列插件开发需要新建对应的Module（模块）开发，前端部分你需要在对应的模块中创建antoa_components/PluginListTableColumn文件夹，在里面编写ListTableColumn系列插件的前端页面。

假设你的模块名称为AntOAPlugins，要创建的ListTableColumn系列插件为ListTableColumnTest，那么你需要在AntOAPlugins/antoa_components/PluginListTableColumn文件夹中创建PluginListTableColumnTest.vue文件并在内部编写代码。该文件是一个Vue的组件。下面以一个在展示文本后面添加666的插件编写方法举例：
```
<template>
	<div>
		{{value}}666
	</div>
</template>
<script>
export default {
	props: {
		render: { //这里的render数据是后台经过json_encode传来的
			type: Object,
			default () {
				return {
					"type": "PluginListTableColumnTest",
					"col": "",
					"tip": ""
				};
			}
		},
		value: { //这里是用来展示的数据
			type: [String, Number],
			default: 0
		}
	},
	data() {
		return {};
	}
}
</script>
```
编写完成后你需要到AntOA/frontend文件夹中重新使用yarn build进行编译才能使用。如果你是前端想在开发过程中使用你可以直接把整个网站代码down下来然后在AntOA/frontend文件夹中使用yarn serve 进行调试~
### 插件后端部分编写
ListTableColumn系列插件开发需要新建对应的Module（模块）开发，后端部分你可以在任意位置创建继承自Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase类的子类并重写父类onParse方法来实现后端功能。

假设你的模块名称为AntOAPlugins，要创建的ListTableColumn系列插件为PluginListTableColumnTest，那么你可以在AntOAPlugins/Http/Requests中创建PluginListTableColumnTest类继承PluginListTableColumnBase。下面以一个在展示文本后面添加666的插件编写方法举例：
```
use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;
class PluginListTableColumnTest extends ListTableColumnBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "PluginListTableColumnTest"
        ]);
    }

    public function onParse(&$searchResultItem, UrlParamCalculator $urlParamCalculator, $uid){
        $searchResultItem[$this->col] = $searchResultItem[$this->col] . "666";
    }
}
```
这里解释一下后端逻辑处理方法：
#### public function onParse(&$searchResultItem, UrlParamCalculator $urlParamCalculator, $uid);
后端查询结果查询出来之后供插件调用的方法。参数以外本实例自带的属性可以参考ListTableColumnBase类的定义，其中$this->col可以获取当前配置的字段是哪一个字段。
##### 参数
	- $searchResultItem 键值对数组类型，每一行的查询数据（尽量不要用foreach遍历，因为其可能带有额外定义的变量）
	- $urlParamCalculator UrlParamCalculator类型，你可以通过这个对象获取前端页面传来的任意参数
	- $uid 当前登录的用户ID
##### 返回值
这个方法没有返回值，如果你不需要处理后端逻辑甚至可以不重写本方法。

### 使用
使用GridList的column方法传入你定义的ListTableColumnBase子类实例即可。例：
```
$grid->list(new class(DB::table("user")) extends DBListOperator{})
	->column(new PluginListTableColumnTest("username", "用户名"));
```

## CreateColumn 系列插件
### 命名规范
CreateColumn系列插件命名需要以PluginCreateColumn开头，否则不识别。后端实体类返回json的type字段需要与前端的插件名称、后端插件类名一致。
### 插件前端部分编写
CreateColumn系列插件开发需要新建对应的Module（模块）开发，前端部分你需要在对应的模块中创建antoa_components/PluginCreateColumn文件夹，在里面编写CreateColumn系列插件的前端页面。

假设你的模块名称为AntOAPlugins，要创建的CreateColumn系列插件为PluginCreateColumn，那么你需要在AntOAPlugins/antoa_components/PluginCreateColumn文件夹中创建PluginCreateColumnTest.vue文件并在内部编写代码。该文件是一个Vue的组件。下面以一个输入框搜索的插件编写方法举例：
```
<template>
	<a-form-item :label="column.tip" :label-col="{span: 7}" :wrapper-col="{span: 10}">
		<a-input :placeholder="'请填写' + column.tip" :value="value" @change="onChange"></a-input>
		<slot />
	</a-form-item>
</template>
<script>
export default {
	props: {
		column: {
			type: Object,
			default () {
				return {
					"col": "id",
					"tip": "",
					"default": "",
					"type": "PluginCreateColumnTest"
				};
			}
		},
		gridApiObject: {
			type: Object,
			default () {
				return {
					api_column_change: "",
					create: "",
					create_page: "",
					delete: "",
					detail: "",
					detail_column_list: "",
					edit_page: "",
					list: "",
					list_page: "",
					path: "",
					save: "",
					api_upload: ""
				};
			},
		},
		value: {
			type: [String, Number]
		}
	},
	data() {
		return {};
	},
	methods: {
		onChange(e) {
			this.$emit("input", e.target.value);
		}
	}
}
</script>
```
编写完成后你需要到AntOA/frontend文件夹中重新使用yarn build进行编译才能使用。如果你是前端想在开发过程中使用你可以直接把整个网站代码down下来然后在AntOA/frontend文件夹中使用yarn serve 进行调试~

### 插件后端部分编写
CreateColumn系列插件开发需要新建对应的Module（模块）开发，后端部分你可以在任意位置创建继承自Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase类的子类并重写父类onGuestVal方法来实现后端功能。

假设你的模块名称为AntOAPlugins，要创建的CreateColumn系列插件为PluginCreateColumnTest，那么你可以在AntOAPlugins/Http/Requests中创建PluginCreateColumnTest类继承PluginCreateColumnBase。下面以一个输入框搜索的插件编写方法举例：
```
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;
class PluginCreateColumnTest extends CreateColumnBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "PluginCreateColumnTest"
        ]);
    }
    public function onGuestVal($guestVal){
        return $guestVal;
    }
}
```
这里解释一下后端逻辑处理方法：
#### public function onGuestVal($guestVal);
前端发起创建请求时供插件调用的方法。参数以外本实例自带的属性可以参考CreateColumnBase类的定义，其中$this->col可以获取当前配置的字段是哪一个字段。
##### 参数
	- $guestVal 客户端传来的该字段的值，你可以拿来处理该字段
	- $uid 当前登录的用户ID
##### 返回值
这个方法没有返回值，如果你不需要处理后端逻辑甚至可以不重写本方法。

### 使用
使用GridList的filter方法传入你定义的CreateColumnBase子类实例即可。例：
```
$grid->list(new class(DB::table("user")) extends DBListOperator{})
	->columnText("username", "用户名")
	->filter(new PluginListFilterTest("username", "用户名"));
```