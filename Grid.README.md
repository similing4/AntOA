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
$grid->list(new class(数据库Builder对象实例) extends DBListOperator {
    //这里可以重写你各种自定义方法
});
//例如：
$grid->list(new class(DB::table("user")) extends DBListOperator {
    //这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridList实例并返回该GridList实例。您可以通过这个GridList实例来操作列表页信息。

这里的匿名类用于对应处理数据库操作，您可以直接重写内部定义的数据库处理方法，详情请见DBListOperator类。

### (2)GridList对象的column系列方法
column系列方法用于配置列表页的列数据，且能返回对象自身供链式调用。默认的方法包括但不限于：
```php
column($column);
columnText($col, $colTip);
columnDisplay($col, $colTip);
columnRichDisplay($col, $colTip);
columnPicture($col, $colTip, $width, $height);
columnEnum($col, $colTip, array $options);
columnRichText($col, $colTip);
columnHidden($col);
columnDivideNumber($col, $colTip, $divide, $unit = '');
```
使用例子如下：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
})->columnText('username', '用户名');
```
您也可以根据Http/Utils/Model中提供的实体类传入column方法中实现同样的功能。如：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
})->column(new ListTableColumnText('username', '用户名'));
```
后续我会根据需求更新这些类型，但通常RICH_TEXT配合Hook钩子就可以满足绝大多数自定义需求了。

### (3)GridList对象的filter系列方法
filter系列方法用于配置列表页的列筛选项，且能返回对象自身供链式调用。默认的方法包括但不限于：

```php
filterHidden($col);
filterText($col, $colTip);
filterStartTime($col, $colTip);
filterEndTime($col, $colTip);
filterEnum($col, $colTip, array $options);
filterMultiSelect($col, $colTip, array $options);
filterCascader($col, $colTip, array $options);
filter($filterItem);
filterUid($col);
```
使用例子如下：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
})->filterText('username', '用户名');
```
您也可以根据Http/Utils/Model中提供的实体类传入filter方法中实现同样的功能。如：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {
})->filter(new ListFilterText('username', '用户名'));
```
后续我会根据需求更新这些筛选类型。

### (3)orderby操作
直接调用DBListOperator对象的orderby方法即可。例：
```php
$grid->list((new class(new DB::table("user")) extends DBListOperator {})->orderBy('rounds', 'desc'));
```

### (4)自定义按钮
列表页的按钮分为**顶部按钮**、**每行按钮**两种。顶部按钮在筛选项的下方与数据表格的上方。而每行按钮位于每一行的右侧。

其中**顶部按钮**默认包含**创建按钮**，**每行按钮**默认包含**编辑按钮**与**删除按钮**，默认这些按钮是全部启用的。

您可以分别通过GridList对象的useCreate、useEdit、useDelete方法来设置是否启用它们。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->columnText('username', '用户名')
    ->useCreate(false)
    ->useEdit(false)
    ->useDelete(false);
```
如果您需要自定义顶部按钮，GridList对象提供了4个方法供您使用，后续可能会补充。它们分别为：
```php
headerNavigateButton(ListHeaderButtonNavigate $listHeaderButtonItem);
headerApiButton(ListHeaderButtonApi $listHeaderButtonItem);
headerBlobButton(ListHeaderButtonBlob $listHeaderButtonItem);
headerRichTextButton(ListHeaderButtonRichText $listHeaderButtonItem);
```
它们均返回对象自身实例，可以进行链式调用。对应功能详见对应方法与其参数的实体类。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->columnText('username', '用户名')
    ->headerNavigateButton(new class("http://www.baidu.com","测试按钮","primary") extends ListHeaderButtonNavigate{
        public function calcButtonParam(UrlParamCalculator $calculator) { //配置跳转页面时页面传入的参数，返回UrlParamCalculatorParamItem的对象数组
        return [];
    }

    public function judgeIsShow(UrlParamCalculator $calculator) { //根据参数判断该按钮是否显示
        return true;
    }
    }) //跳转到百度
    ->headerBlobButton(new class("/api/user/test/test","测试按钮","test.xls","primary") extends ListHeaderButtonBlob{
        public function calcButtonParam(UrlParamCalculator $calculator) { //配置调用接口时传入的参数，返回UrlParamCalculatorParamItem的对象数组
        return [];
    }

    public function judgeIsShow(UrlParamCalculator $calculator) { //根据参数判断该按钮是否显示
        return true;
    }
    }) //调用/api/user/test/test接口并使响应的二进制数据直接弹出下载
    ->headerApiButton(new class("/api/user/test/test","测试按钮","primary") extends ListHeaderButtonApi{
        public function calcButtonParam(UrlParamCalculator $calculator) { //配置调用接口时传入的参数，返回UrlParamCalculatorParamItem的对象数组
        return [];
    }

    public function judgeIsShow(UrlParamCalculator $calculator) { //根据参数判断该按钮是否显示
        return true;
    }
    }); //调用/api/user/test/test接口并对响应JSON根据status字段判定提示data或msg字段内容
```
相对于顶部按钮，GridList对象为每行按钮提供了4个方法。它们分别为：
```php
rowNavigateButton(ListRowButtonNavigate $listRowButtonItem);
rowApiButton(ListRowButtonApi $listRowButtonItem);
rowBlobButton(ListRowButtonBlob $listRowButtonItem);
rowRichTextButton(ListRowButtonRichText $listRowButtonItem);
```
它们均返回对象自身实例，可以进行链式调用。相对于header系列按钮方法，每行按钮的UrlParamCalculator多了每行的数据处理。例：
```php
$grid->list(new class(new DB::table("user")) extends DBListOperator {})
    ->columnText('username', '用户名')
    ->rowNavigateButton(new class("/race/register/list", "报名记录", "primary") extends ListRowButtonNavigate {
        public function calcButtonParam(UrlParamCalculator $calculator) { //将每行的race_id字段转换为跳转页面参数的id字段
            return [new UrlParamCalculatorParamItem("race_id", $calculator->getRowParamByKey("id")->val)];
        }
        public function judgeIsShow(UrlParamCalculator $calculator) { //根据参数判断该按钮是否显示
            return true;
        }
    });
```
如范例中的测试按钮点击后会跳转页面，假设被点击行的race_id是1，那么点击报名记录这个按钮时跳转到目标url是/race/register/list?id=1。
注意：跳转的链接如果不以http:// 或 https:// 开头的话会被认作客户端相对路径哦~

## 2.Create创建页配置

### (1)构造
Create创建页主要由GridCreateForm对象控制。创建GridCreateForm对象的方法如下：
```php
$grid->createForm(new class(new DB::table("user")) extends DBCreateOperator {
    //这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridCreateForm实例并返回该GridCreateForm实例。您可以通过这个GridCreateForm实例来操作列表页信息。

### (2)column系列操作
column系列方法用于配置列表页的列筛选项，且能返回对象自身供链式调用。默认的方法包括但不限于：

```php
function column(CreateColumnBase $columnItem);
function columnText($col, $colTip, $defaultVal = '');
function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '');
function columnTextarea($col, $colTip, $defaultVal = '');
function columnPassword($col, $colTip, $defaultVal = '');
function columnSelect($col, $colTip, array $options, $defaultVal = '');
function columnRadio($col, $colTip, array $options, $defaultVal = '');
function columnCheckbox($col, $colTip, array $options, $defaultVal = '');
function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '');
function columnTimestamp($col, $colTip, $defaultVal = '');
function columnRichText($col, $colTip, $defaultVal = '');
function columnPicture($col, $colTip, $defaultVal = '');
function columnFile($col, $colTip, $defaultVal = '');
function columnPictures($col, $colTip, $defaultVal = '');
function columnFiles($col, $colTip, $defaultVal = '');
function columnCascader($col, $colTip, array $options, $defaultVal = '');
function columnDisplay($col, $colTip, $defaultVal = '');
function columnHidden($col);
function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '');
function columnApiButton(CreateRowButtonBase $buttonItem);
```

使用例子如下：
```php
$grid->createForm(new class(DB::table("race")) extends DBCreateOperator {
})
    ->columnText('name', '比赛名称')
    ->columnTimestamp('register_end_time', '报名截止时间')
    ->columnRichText('race_explain', '比赛简介')
    ->columnText('race_qq_url', '比赛QQ群加群链接')
    ->columnRadio('status', '状态', [
        new EnumOption("0", "不可报名"),
        new EnumOption("1", "可报名"),
    ]);
```
您也可以根据Http/Utils/Model中提供的实体类传入column方法中实现同样的功能。如：
```php
$grid->createForm(new class(DB::table("race")) extends DBCreateOperator {
})->column(new CreateColumnText('name', '比赛名称'));
```

## 3.Edit编辑页配置
编辑页与创建页大体相同：

### (1)构造
Edit编辑页主要由GridEditForm对象控制。创建GridEditForm对象的方法如下：
```php
$grid->editForm(new class(new DB::table("user")) extends DBCreateOperator {
    //这里可以重写你各种自定义方法
});
```
此方法调用之后会在Grid内部自动创建GridEditForm实例并返回该GridEditForm实例。您可以通过这个GridEditForm实例来操作列表页信息。

### (2)column系列操作
column系列方法用于配置列表页的列筛选项，且能返回对象自身供链式调用。默认的方法包括但不限于：

```php
function column(EditColumnBase $columnItem);
function columnText($col, $colTip, $defaultVal = '');
function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '');
function columnTextarea($col, $colTip, $defaultVal = '');
function columnPassword($col, $colTip, $defaultVal = '');
function columnSelect($col, $colTip, array $options, $defaultVal = '');
function columnRadio($col, $colTip, array $options, $defaultVal = '');
function columnCheckbox($col, $colTip, array $options, $defaultVal = '');
function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '');
function columnTimestamp($col, $colTip, $defaultVal = '');
function columnRichText($col, $colTip, $defaultVal = '');
function columnPicture($col, $colTip, $defaultVal = '');
function columnFile($col, $colTip, $defaultVal = '');
function columnPictures($col, $colTip, $defaultVal = '');
function columnFiles($col, $colTip, $defaultVal = '');
function columnCascader($col, $colTip, array $options, $defaultVal = '');
function columnDisplay($col, $colTip, $defaultVal = '');
function columnHidden($col);
function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '');
function columnApiButton(EditRowButtonBase $buttonItem);
```

使用例子如下：
```php
$grid->editForm(new class(DB::table("race")) extends DBEditOperator {
})
    ->columnHidden('id')
    ->columnText('name', '比赛名称')
    ->columnTimestamp('register_end_time', '报名截止时间')
    ->columnRichText('race_explain', '比赛简介')
    ->columnText('race_qq_url', '比赛QQ群加群链接')
    ->columnRadio('status', '状态', [
        new EnumOption("0", "不可报名"),
        new EnumOption("1", "可报名")
    ]);
```
您也可以根据Http/Utils/Model中提供的实体类传入column方法中实现同样的功能。如：
```php
$grid->editForm(new class(DB::table("race")) extends DBCreateOperator {
})->column(new EditColumnText('name', '比赛名称'));
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
