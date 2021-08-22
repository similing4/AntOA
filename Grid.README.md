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
$grid->list("你的表名");
```
此方法调用之后会在Grid内部自动创建GridList实例并返回该GridList实例。您可以通过这个GridList实例来操作列表页信息。

这里的表名是指DB::table的参数，暂时还不支持Model操作。如果想连接多张表，GridList提供了leftJoin方法，使用方法如下：
```php
//左连接模板：
$grid->list("你的表名A")->leftJoin("待左连接的表名B","B中A的外键","A的主键");
//示例：
$grid->list("user")->leftJoin("user_money","uid","id");
```
如果您需要删除主表数据行时一并删除连接表的数据时，您可以使用deleteJoin方法：
```php
$grid->list("你的表名A")->leftJoin("待左连接的表名B","B中A的外键","A的主键")->deleteJoin("待左连接同时删除的表名B","B中A的外键","A的主键");
```
注：如果使用了左连接，那么后续字段需要带上表名，如user_money.money。

### (2)column操作
column方法用于配置列表页的列数据，且能返回对象自身供链式调用。使用方法如下：
```php
//模板：
$grid->list("你的表名")->column("列类型", '列名', '列展示名');
//示例：
$grid->list("user")->column(GridList::TEXT, 'username', '用户名');
```
这里的“**列类型**”为GridList里的常量，可用的常量如下：
```php
const TEXT = "TEXT"; //文本类型展示
const PICTURE = "PICTURE"; //图片类型展示，需在extra中指定图片宽高
const ENUM = "ENUM"; //枚举类型展示，需要指定键值对用于确定ENUM对应关系
const RICH_TEXT = "RICH_TEXT"; //富文本类型展示
//例：
$grid->list("user")
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
$grid->list("你的表名")->filter("列筛选类型", '列名', '筛选列展示名');
//示例：
$grid->list("user")->filter(GridList::FILTER_TEXT, 'username', '用户名');
```
这里的“**列筛选类型**”为GridList里的常量，可用的常量如下：
```php
const FILTER_TEXT = "FILTER_TEXT"; //文本类型筛选，筛选方式为%keyword%
const FILTER_STARTTIME = "FILTER_STARTTIME"; //开始时间类型筛选，筛选结果为大于等于该结束时间的行
const FILTER_ENDTIME = "FILTER_ENDTIME"; //结束时间类型筛选，筛选结果为小于等于该结束时间的行
const FILTER_ENUM = "FILTER_ENUM"; //单选类型的筛选，需要指定键值对用于确定ENUM对应关系
//例：
$grid->list("user")
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
$grid->list("user")
    ->column(GridList::TEXT, 'username', '用户名')
    ->order("create_time","desc");
```

### (4)自定义按钮
列表页的按钮分为**顶部按钮**、**每行按钮**两种。顶部按钮在筛选项的下方与数据表格的上方。而每行按钮位于每一行的右侧。

其中**顶部按钮**默认包含**创建按钮**，**每行按钮**默认包含**编辑按钮**与**删除按钮**，默认这些按钮是全部启用的。

您可以分别通过GridList对象的useCreate、useEdit、useDelete方法来设置是否启用它们。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list("user")
    ->column(GridList::TEXT, 'username', '用户名')
    ->useCreate(false)
    ->useEdit(false)
    ->useDelete(false);
```
如果您需要自定义顶部按钮，GridList提供了三个方法供您使用。它们分别为：navigateButton、apiButton、apiButtonWithConfirm。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list("user")
    ->column(GridList::TEXT, 'username', '用户名')
    ->navigateButton("测试按钮","http://www.baidu.com","primary") //跳转到百度
    ->apiButton("测试按钮","/api/user/test/test","primary") //调用/api/user/test/test接口并对响应JSON根据status字段判定提示msg字段内容
    ->apiButtonWithConfirm("测试按钮","/api/user/test/test","primary"); //与apiButton相同，但调用接口前会要求用户确认
```
相对于顶部按钮，每行按钮则为另外三个方法。它们分别为：rowNavigateButton、rowApiButton、rowApiButtonWithConfirm。它们均返回对象自身实例，可以进行链式调用。例：
```php
$grid->list("user")
    ->column(GridList::TEXT, 'username', '用户名')
    ->rowNavigateButton("测试按钮","http://www.baidu.com","primary") //跳转到百度
    ->rowApiButton("测试按钮","/api/user/test/test","primary") //调用/api/user/test/test接口并对响应JSON根据status字段判定提示msg字段内容
    ->rowApiButtonWithConfirm("测试按钮","/api/user/test/test","primary"); //与rowApiButton相同，但调用接口前会要求用户确认
```
每行按钮会自动带上该行的id参数。

## 2.Create创建页配置
```php
        $grid->createForm("software")
            ->column(GridCreateForm::COLUMN_TEXT, 'title', '软件名')
            ->column(GridCreateForm::COLUMN_PICTURE, 'icon', '软件图标')
            ->column(GridCreateForm::COLUMN_PICTURES, 'pictures', '图集')
            ->column(GridCreateForm::COLUMN_TEXT, 'keyword', '关键词')
            ->column(GridCreateForm::COLUMN_TEXT, 'version', '版本号')
            ->column(GridCreateForm::COLUMN_TEXT, 'download_url', '软件下载地址')
            ->column(GridCreateForm::COLUMN_TEXT, 'size', '大小(MB)')
            ->column(GridCreateForm::COLUMN_TEXT, 'os_version', '系统版本要求')
            ->column(GridCreateForm::COLUMN_TEXT, 'language', '语言要求')
            ->column(GridCreateForm::COLUMN_TEXT, 'author', '作者')
            ->column(GridCreateForm::COLUMN_TEXTAREA, 'description', '应用介绍')
            ->column(GridCreateForm::COLUMN_TEXT, 'price', '价格 单位分')
            ->column(GridCreateForm::COLUMN_CHILDREN_CHOOSE, 'category_id', '所属分类', (new GridList("category"))
                ->column(GridList::TEXT, "id", "ID")
                ->column(GridList::TEXT, "title", "分类名")
                ->setDisplayColumn("title"))
            ->column(GridCreateForm::COLUMN_RADIO, 'state', '状态', [
                "0" => "禁用",
                "1" => "启用"
            ]);
        $grid->editForm("software")
            ->column(GridEditForm::COLUMN_HIDDEN, 'id', 'ID')
            ->column(GridEditForm::COLUMN_TEXT, 'title', '软件名')
            ->column(GridEditForm::COLUMN_PICTURE, 'icon', '软件图标')
            ->column(GridEditForm::COLUMN_PICTURES, 'pictures', '图集')
            ->column(GridEditForm::COLUMN_TEXT, 'keyword', '关键词')
            ->column(GridEditForm::COLUMN_TEXT, 'version', '版本号')
            ->column(GridEditForm::COLUMN_TEXT, 'download_url', '软件下载地址')
            ->column(GridEditForm::COLUMN_TEXT, 'size', '大小(MB)')
            ->column(GridEditForm::COLUMN_TEXT, 'os_version', '系统版本要求')
            ->column(GridEditForm::COLUMN_TEXT, 'language', '语言要求')
            ->column(GridEditForm::COLUMN_TEXT, 'author', '作者')
            ->column(GridEditForm::COLUMN_TEXTAREA, 'description', '应用介绍')
            ->column(GridEditForm::COLUMN_TEXT, 'price', '价格 单位分')
            ->column(GridEditForm::COLUMN_CHILDREN_CHOOSE, 'category_id', '所属分类', (new GridList("category"))
                ->column(GridList::TEXT, "id", "ID")
                ->column(GridList::TEXT, "title", "分类名")
                ->setDisplayColumn("title"))
            ->column(GridCreateForm::COLUMN_RADIO, 'state', '状态', [
                "0" => "禁用",
                "1" => "启用"
            ]);
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
