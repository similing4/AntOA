# Grid类在AntOAController的子类控制器中的使用
Grid类需要在继承了AntOAController类的控制器中使用。

AntOAController类的grid方法会注入Grid的实例对象。

Grid实例主要控制三个页面，它们分别是List（列表页）、Create（创建页）、Edit（编辑页）。

Grid实例主要控制四个接口，它们分别是List（列表接口）、Create（创建接口）、Detail（详情接口）、Save（保存接口）。

下面解释一下Grid实例如何控制这些页面与接口。

注：接下来所述的所有$grid变量均为AntOAController类的grid方法注入的Grid实例。

## 1.List列表页配置
### (1)构造
List列表页主要由GridList对象控制。创建GridList对象的方法如下：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
    //这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridList实例并返回该GridList实例。您可以通过这个GridList实例来操作列表页信息。

这里的匿名类用于对应处理数据库操作，详情请见DBListOperator类。

### (2)column操作
column方法用于配置列表页的列数据，且能返回对象自身供链式调用。使用方法如下：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
    //这里可以重写你各种自定义方法
})->column("列类型", '列名', '列展示名');
```
示例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
    //这里可以重写你各种自定义方法
})->column(GridList::TEXT, 'username', '用户名');
```
这里的$grid->list参数需要传入一个DBListOperator抽象类子类的实例，这里推荐直接使用匿名对象重写父类方法。DBListOperator类定义如下：
```php
<?php
abstract class DBListOperator {
    public $builder; //DB类产生的对象，于构造方法中传入

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
	
	//where方法，设置的对应column会作为条件传入。你可以根据column自定义设置传入条件内容
    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

	//orderBy方法，你可以在这里自定义设置排序规则。
    public function orderBy($column, $direction) {
        $this->builder->orderBy($column, $direction);
        return $this;
    }

	//select方法，如果你有连接查询你可以在这里将查询字段格式化为正确的字段解决冲突。
    public function select($columns) {
        $this->builder->select($columns);
        return $this;
    }

	//分页方法，不建议直接重写本方法，建议直接通过hook修改结果。
    public function paginate($pageCount) {
        return $this->builder->paginate($pageCount);
    }

	//当编辑页或创建页使用column为COLUMN_CHILDREN_CHOOSE类型时，extra需要使用本方法。
    public function first() {
        return $this->builder->first();
    }

	//detail判断、删除时判断
    public function find($id) {
        return $this->builder->find($id);
    }

	//删除时进行的操作，除了重写这里之外，你也可以直接重写AntOAController的delete方法
    public function delete($id) {
        return $this->builder->delete($id);
    }
}
```
这里的“**列类型**”为GridList里的常量，可用的常量如下：
```php
const TEXT = "TEXT"; //文本类型展示
const PICTURE = "PICTURE"; //图片类型展示，需在extra中指定图片宽高
const ENUM = "ENUM"; //枚举类型展示，需要指定键值对用于确定ENUM对应关系
const RICH_TEXT = "RICH_TEXT"; //富文本类型展示
const DISPLAY = "DISPLAY"; //文本类型展示，且不从数据库查询。需要通过HOOK设置
const RICH_DISPLAY = "RICH_DISPLAY"; //富文本类型展示，且不从数据库查询。需要通过HOOK设置
//例：
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->column(GridList::PICTURE, 'icon', '用户头像',[
        "width"  => '50px',
        "height" => '50px'
    ])
    ->column(GridList::ENUM, 'state', '用户状态',[
        "0" => "禁用",
        "1" => "启用"
    ])
    ->column(GridList::RICH_TEXT, 'log', '用户备注');
```
后续我会根据需求更新这些类型，但通常RICH_TEXT配合Hook钩子就可以满足绝大多数自定义需求了。

### (2)filter操作
filter方法用于配置列表页的列筛选项，且能返回对象自身供链式调用。使用方法如下：
```php
//模板：
$grid->list(new class(new DB::table("user")) extends DBListOperator {
    //这里可以重写你各种自定义方法
})->filter("列筛选类型", '列名', '筛选列展示名');
//示例：
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->filter(GridList::FILTER_TEXT, 'username', '用户名');
```
这里的“**列筛选类型**”为GridList里的常量，可用的常量如下：
```php
const FILTER_HIDDEN = "FILTER_HIDDEN"; //隐藏类型筛选，用于外部传入
const FILTER_TEXT = "FILTER_TEXT"; //文本类型筛选，筛选方式为%keyword%
const FILTER_STARTTIME = "FILTER_STARTTIME"; //开始时间类型筛选，筛选结果为大于等于该结束时间的行
const FILTER_ENDTIME = "FILTER_ENDTIME"; //结束时间类型筛选，筛选结果为小于等于该结束时间的行
const FILTER_ENUM = "FILTER_ENUM"; //单选类型的筛选，需要指定键值对用于确定ENUM对应关系
//例：
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->column(GridList::TEXT, 'create_time', '注册时间')
    ->column(GridList::ENUM, 'state', '用户状态',[
        "0" => "禁用",
        "1" => "启用"
    ])
    ->filter(GridList::FILTER_TEXT,'username','用户名')
    ->filter(GridList::FILTER_STARTTIME,'create_time','在此注册时间之后')
    ->filter(GridList::FILTER_ENDTIME,'create_time','在此注册时间之前')
    ->filter(GridList::FILTER_ENUM,'state','状态',[
        "0" => "禁用",
        "1" => "启用"
    ]);
```
后续我会根据需求更新这些筛选类型。

### (3)order操作
等同于DB的order操作，例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->order("create_time","desc");
```

### (4)自定义按钮
列表页的按钮分为**顶部按钮**、**每行按钮**两种。顶部按钮在筛选项的下方与数据表格的上方。而每行按钮位于每一行的右侧。

其中**顶部按钮**默认包含**创建按钮**，**每行按钮**默认包含**编辑按钮**与**删除按钮**，默认这些按钮是全部启用的。

您可以分别通过GridList对象的useCreate、useEdit、useDelete方法来设置是否启用它们。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->useCreate(false)
    ->useEdit(false)
    ->useDelete(false);
```
如果您需要自定义顶部按钮，GridList提供了三个方法供您使用。它们分别为：navigateButton、apiButton、apiButtonWithConfirm。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->navigateButton("测试按钮","http://www.baidu.com","primary") //跳转到百度
    ->apiButton("测试按钮","/api/user/test/test","primary") //调用/api/user/test/test接口并对响应JSON根据status字段判定提示msg字段内容
    ->apiButtonWithConfirm("测试按钮","/api/user/test/test","primary"); //与apiButton相同，但调用接口前会要求用户确认
```
相对于顶部按钮，每行按钮则为另外三个方法。它们分别为：rowNavigateButton、rowApiButton、rowApiButtonWithConfirm。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->column(GridList::TEXT, 'username', '用户名')
    ->rowNavigateButton("测试按钮","http://www.baidu.com","primary") //跳转到百度
    ->rowApiButton("测试按钮","/api/user/test/test","primary") //调用/api/user/test/test接口并对响应JSON根据status字段判定提示msg字段内容
    ->rowApiButtonWithConfirm("测试按钮","/api/user/test/test","primary"); //与rowApiButton相同，但调用接口前会要求用户确认
```
每行按钮会自动带上该行的id参数。如范例中的测试按钮点击后会弹窗确认，确认后会调用/api/user/test/test?id=1。

如果您想传入您定义的页面的参数不为id，如/api/user/test/test?sid=1

那么您可以定义第四个参数为你要传入的参数，例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->rowApiButtonWithConfirm("测试按钮","/api/user/test/test","primary","sid"); //与rowApiButton相同，但调用接口前会要求用户确认
```

## 2.Create创建页配置

### (1)构造
Create创建页主要由GridCreateForm对象控制。创建GridCreateForm对象的方法如下：
```php
$grid->createForm(new class(new DB::table("user")) extends DBCreateOperator {
    //这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridCreateForm实例并返回该GridCreateForm实例。您可以通过这个GridCreateForm实例来操作列表页信息。

这里的表名是指DB::table的参数，暂时还不支持Model操作。

### (2)column操作
column方法用于配置列表页的列数据，且能返回对象自身供链式调用。使用方法如下：
```php
//模板：
$grid->createForm(DBCreateOperator对象)->column("列类型", '列名', '列展示名');
//示例：
$grid->createForm(new DB::table("user")) extends DBCreateOperator {
    //这里可以重写你各种自定义方法
})->column(GridCreateForm::COLUMN_TEXT, 'username', '用户名');
```
这里的$grid->createForm参数需要传入一个DBCreateOperator抽象类子类的实例，这里推荐直接使用匿名对象重写父类方法。DBCreateOperator类定义如下：
```php
<?php
abstract class DBCreateOperator {
    public $builder; //构造方法，为DB类的Builder

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
	
	//你可以在这里重写插入方法，但不推荐直接在这里写，你可以在CreateHook中进行插入信息的修改。
    public function insert(array $values) {
        return $this->builder->insert($values);
    }
}
```
这里的“**列类型**”为GridCreateForm里的常量，可用的常量如下：
```php
const COLUMN_TEXT = "COLUMN_TEXT"; //文本数据，表现形式为一个文本框
const COLUMN_TEXTAREA = "COLUMN_TEXTAREA"; //多行文本数据，表现形式为一个textarea
const COLUMN_PASSWORD = "COLUMN_PASSWORD"; //密码数据，表现形式为一个密码输入框
const COLUMN_SELECT = "COLUMN_SELECT"; //下拉单选，表现形式为一个select，需传入Extra参数为一个键值对数组，如["1"=>"启用","2"=>"禁用"]，那么用户会选择启用与禁用，而你能拿到1或2
const COLUMN_RADIO = "COLUMN_RADIO"; //Radio单选，表现形式为input type=radio的单选，需传入Extra参数与COLUMN_SELECT一致
const COLUMN_CHECKBOX = "COLUMN_CHECKBOX"; //多选，表现形式为input type=checkbox的多选，需传入Extra参数与COLUMN_SELECT一致，但你获取到的值为多选选中的键数组
const COLUMN_TIMESTAMP = "COLUMN_TIMESTAMP"; //时间选择，表现形式为点击后弹出时间与日期的选择
const COLUMN_RICHTEXT = "COLUMN_RICHTEXT"; //富文本，表现形式为WangEditor的富文本编辑器
const COLUMN_PICTURE = "COLUMN_PICTURE"; //图片，表现形式为上传并预览单张图片
const COLUMN_FILE = "COLUMN_FILE"; //文件，表现形式为上传并预览单个文件
const COLUMN_PICTURES = "COLUMN_PICTURES"; //多图片，表现形式为上传并预览多张图片
const COLUMN_CHOOSE = "COLUMN_CHOOSE"; //级联选择，表现形式参考[级联选择](https://www.antdv.com/components/cascader-cn/#API)，需传入Extra参数为Cascader组件的options格式，对应数据为Cascader组件的v-model格式的数组的json_encode格式
const COLUMN_FILES = "COLUMN_FILES"; //多文件，表现形式为上传并预览多个文件
const COLUMN_DISPLAY = "COLUMN_DISPLAY"; //只用来展示的行，不会提交。可以通过extra传入要展示的富文本信息。
const COLUMN_HIDDEN = "COLUMN_HIDDEN"; //隐藏的行，会提交，所有column配置内容均可通过页面传入参数注入。
const COLUMN_CHILDREN_CHOOSE = "COLUMN_CHILDREN_CHOOSE"; //子表选择，将子表的ID作为值进行选择，Extra中需传入GridList类的实例并配置displayColumn。
//例：
$grid->createForm(new DB::table("user")) extends DBCreateOperator {
	//这里可以重写你各种自定义方法
})
->column(GridCreateForm::COLUMN_TEXT, 'username', '用户名')
->column(GridCreateForm::COLUMN_PICTURE, 'icon', '用户头像',[
	"width"  => '50px',
	"height" => '50px'
])
->column(GridCreateForm::COLUMN_SELECT, 'state', '用户状态',[
	"0" => "禁用",
	"1" => "启用"
])
->column(GridCreateForm::COLUMN_RICHTEXT, 'log', '用户备注');
```
## 3.Edit编辑页配置
编辑页与创建页大体相同：

### (1)构造
Edit编辑页主要由GridEditForm对象控制。创建GridEditForm对象的方法如下：
```php
$grid->editForm(new DB::table("user")) extends DBEditOperator {
	//这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridEditForm实例并返回该GridEditForm实例。您可以通过这个GridEditForm实例来操作列表页信息。

这里的$grid->editForm参数需要传入一个DBEditOperator抽象类子类的实例，这里推荐直接使用匿名对象重写父类方法。DBEditOperator类定义如下：
```php
<?php
abstract class DBEditOperator {
    public $builder;

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
	
	//用于获取detail数据
    public function find($id) {
        return $this->builder->find($id);
    }

	//用于更新数据，更新时会默认以第一个传入的column为条件更新数据。你可以重写这个方法自定义更新数据
    public function onUpdate($columns, $param) {
        return $this->builder->where($columns[0]['col'], $param[$columns[0]['col']])->update($param);
    }
}
```

### (2)column操作
column方法用于配置列表页的列数据，且能返回对象自身供链式调用。使用方法如下：
```php
//模板：
$grid->editForm(DBEditOperator对象)->column("列类型", '列名', '列展示名');
//示例：
$grid->editForm(new class(DB::table("user")) extends DBEditOperator {
    //这里可以重写你各种自定义方法
})->column(GridEditForm::COLUMN_TEXT, 'username', '用户名');
```
这里的“**列类型**”为GridEditForm里的常量，可用的常量如下：
```php
const COLUMN_TEXT = "COLUMN_TEXT"; //文本数据，表现形式为一个文本框
const COLUMN_TEXTAREA = "COLUMN_TEXTAREA"; //多行文本数据，表现形式为一个textarea
const COLUMN_PASSWORD = "COLUMN_PASSWORD"; //密码数据，表现形式为一个密码输入框
const COLUMN_SELECT = "COLUMN_SELECT"; //下拉单选，表现形式为一个select
const COLUMN_RADIO = "COLUMN_RADIO"; //Radio单选，表现形式为input type=radio的单选
const COLUMN_CHECKBOX = "COLUMN_CHECKBOX"; //多选，表现形式为input type=checkbox的多选
const COLUMN_TIMESTAMP = "COLUMN_TIMESTAMP"; //时间选择，表现形式为点击后弹出时间与日期的选择
const COLUMN_RICHTEXT = "COLUMN_RICHTEXT"; //富文本，表现形式为WangEditor的富文本编辑器
const COLUMN_PICTURE = "COLUMN_PICTURE"; //图片，表现形式为上传并预览单张图片
const COLUMN_FILE = "COLUMN_FILE"; //文件，表现形式为上传并预览单个文件
const COLUMN_PICTURES = "COLUMN_PICTURES"; //多图片，表现形式为上传并预览多张图片
const COLUMN_FILES = "COLUMN_FILES"; //多文件，表现形式为上传并预览多个文件
const COLUMN_DISPLAY = "COLUMN_DISPLAY"; //只用来展示的行，不会提交
const COLUMN_HIDDEN = "COLUMN_HIDDEN"; //隐藏的行，会提交
const COLUMN_CHILDREN_CHOOSE = "COLUMN_CHILDREN_CHOOSE"; //子表选择，将子表的ID作为值进行选择
//例：
$grid->editForm(new class(DB::table("user")) extends DBEditOperator {
	//这里可以重写你各种自定义方法
})
->column(GridEditForm::COLUMN_TEXT, 'username', '用户名')
->column(GridEditForm::COLUMN_PICTUR, 'icon', '用户头像',[
	"width"  => '50px',
	"height" => '50px'
])
->column(GridEditForm::COLUMN_SELECT, 'state', '用户状态',[
	"0" => "禁用",
	"1" => "启用"
])
->column(GridEditForm::COLUMN_RICHTEXT, 'log', '用户备注');
```

## 4. 钩子操作
钩子函数共分四种，分别对应四个接口：list、detail、create、save。

对于list与detail，钩子触发于结果响应前。

对于create与save，钩子触发于获取参数后但执行操作前。

例：
```php
$grid->hookList(new class() implements ListHook {
	public function hook($response) {
		foreach ($response['data'] as &$r)
			$r['size'] = intval($r['size']) / 1024;
		return $response;
	}
});
$grid->hookDetail(new class() implements DetailHook {
	public function hook($response) {
		$response['data']['size'] = intval($response['data']['size']) / 1024;
		return $response;
	}
});
$grid->hookCreate(new class() implements CreateHook {
	public function hook($param) {
		$param['size'] = intval($param['size'] * 1024);
		return $param;
	}
});
$grid->hookSave(new class() implements SaveHook {
	public function hook($param) {
		$param['size'] = intval($param['size'] * 1024);
		return $param;
	}
});
```
