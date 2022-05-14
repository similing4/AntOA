GridEditForm与GridCreateForm内容大体一致，只不过GridEditForm必须要提供主键ID的column。
## GridEditForm对象用法
GridEditForm配置需要在AntOAController的子类中的grid方法中配置。依然以UserController举例：
```
class UserController extends AntOAController {
    public function __construct(AuthInterface $auth) { //这个构造方法不能省略
        parent::__construct($auth);
    }

    public function grid(Grid $grid) {
    	//这里编写grid相关操作
    }

    public function statistic(Request $req) {
        return "";
    }

    protected function checkPower($uid) {
        return true;
    }
}
?>
```

## GridEditForm对象的实例化
GridEditForm对象需要由AntOAController的grid方法的$grid参数的editForm方法创建。该参数接收一个DBEditOperator对象。你需要按照你的需求自行构造DBEditOperator对象。例：
```
	function grid(Grid $grid) {
    	$grid->editForm(new class(DB::table("user")) extends DBEditOperator{}); //这里参数是一个继承自DBEditOperator的匿名对象，返回值是GridEditForm对象。
    }
```
其中DBEditOperator类属于Illuminate\Database\Query\Builder的扩展类，它的定义如下：
```
use Illuminate\Database\Query\Builder;
class DBEditOperator {
    public $builder;
    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
    public function find($id) {
        return $this->builder->find($id);
    }
    public function onUpdate($primaryKey, $param) {
        return $this->builder->where($primaryKey, $param[$primaryKey])->update($param);
    }
}
```
你可以在你的实体类中重写这个父类方法来实现你的各种功能。

## GridEditForm对象的column系列实例方法
column系列方法用于表单项的配置。该方法可以将配置的信息以表单的形式展示到页面上并实现功能。
### public function column(EditColumnBase $columnItem);
column的通用方法
#### 参数：
* $column 任意EditColumnBase子类的实例。所有继承自该类的实例均以EditColumn开头，且均定义于AntOA/Http/Utils/Model下。可用的实例如下：
    - EditColumnCascader 级联选择对象（参考[AntDesignVue-Cascader组件](https://www.antdv.com/components/cascader-cn)）
    - EditColumnChildrenChoose 表单项为一个按钮，点击该按钮后弹出列表页进行单选
    - EditColumnDisplay 仅用于展示，需要通过hook设置其值
    - EditColumnDivideNumber 用于展示数据与数据库字段为除以指定数值关系的需求。如输入的是元数据库中存储是分的情况。
    - EditColumnEnum 下拉单选（Select标签样式）
    - EditColumnEnumCheckBox 多选框
    - EditColumnEnumRadio 圆形Radio单选
    - EditColumnEnumTreeCheckBox 下拉多选（Select标签样式）
    - EditColumnFile 选择文件并上传到七牛云，后台接到七牛云目标文件链接
    - EditColumnFileLocal 选择文件并上传到服务端本地，后台接到上传文件的绝对地址
    - EditColumnFiles 选择多个文件并上传到七牛云，后台接到七牛云目标文件链接的数组json
    - EditColumnFilesLocal 选择多个文件并上传到服务端本地，后台接到上传文件的绝对地址的数组json
    - EditColumnHidden 隐藏字段，需要通过ChangeHook配置其值
    - EditColumnPassword 密码输入，插入时需要通过SaveHook对其加密。
    - EditColumnPicture 选择图片并上传到七牛云，后台接到七牛云目标图片链接
    - EditColumnPictureLocal 选择图片并上传到服务端本地，后台接到上传图片的绝对地址
    - EditColumnPictures 选择多个图片并上传到七牛云，后台接到七牛云目标图片链接的数组json
    - EditColumnPicturesLocal 选择多个图片并上传到服务端本地，后台接到上传图片的绝对地址的数组json
    - EditColumnRichText 富文本输入（WangEditor）
    - EditColumnText 普通文本输入
    - EditColumnTextarea 多行文本域输入
    - EditColumnTimestamp 时间输入
#### 返回值：
返回this供链式调用

### public function columnText($col, $colTip, $defaultVal = '');
设置一个普通文本输入的表单项
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '');
设置一个用于展示数据与数据库字段为除以指定数值关系的表单项。如输入的是元数据库中存储是分的情况。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $divide 除数
* $unit 展现给用户的单位名，如元
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnTextarea($col, $colTip, $defaultVal = '');
设置一个多行文本域输入的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnPassword($col, $colTip, $defaultVal = '');
设置一个密码输入的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnSelect($col, $colTip, array $options, $defaultVal = '');
设置一个下拉单选的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options 选项数组（array<EnumOption>类型）
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnRadio($col, $colTip, array $options, $defaultVal = '');
设置一个Radio圆圈式的单选的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options 选项数组（array<EnumOption>类型）
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnCheckbox($col, $colTip, array $options, $defaultVal = '');
设置一个Checkbox方块打勾式的多选的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options 选项数组（array<EnumOption>类型）
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '');
设置一个下拉多选的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options 选项数组（array<TreeNode>类型）
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnTimestamp($col, $colTip, $defaultVal = '');
设置一个时间输入的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnRichText($col, $colTip, $defaultVal = '');
设置一个富文本输入的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnPicture($col, $colTip, $defaultVal = '');
设置一个选择图片（上传到七牛云）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnFile($col, $colTip, $defaultVal = '');
设置一个选择文件（上传到七牛云）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnPictures($col, $colTip, $defaultVal = '');
设置一个选择多个图片（上传到七牛云）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnFiles($col, $colTip, $defaultVal = '');
设置一个选择多个文件（上传到七牛云）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnCascader($col, $colTip, array $options, $defaultVal = '');
设置一个级联选择的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options 选项数组（array<CascaderNode>类型）
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnDisplay($col, $colTip, $defaultVal = '');
设置一个只用于展示的表单项（不会提交）。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnHidden($col, $defaultVal);
设置一个隐藏的表单项（会提交但不会展示）。
#### 参数：
* $col 表单name
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '');
设置一个表单项为一个按钮，点击该按钮后弹出列表页进行单选的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $gridListEasy GridList的简化版对象
* $gridListVModelCol 需要作为表单值的GridListEasy查询结果列
* $gridListDisplayCol 需要作为表单选项展示的GridListEasy查询结果列
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnPictureLocal($col, $colTip, $defaultVal = '');
设置一个选择图片（上传到服务器本地）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnFileLocal($col, $colTip, $defaultVal = '');
设置一个选择文件（上传到服务器本地）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnPicturesLocal($col, $colTip, $defaultVal = '');
设置一个选择多个图片（上传到服务器本地）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

### public function columnFilesLocal($col, $colTip, $defaultVal = '');
设置一个选择多个文件（上传到服务器本地）的表单项。
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 保留字段，没有用处
#### 返回值：
返回this供链式调用

## GridEditForm对象的按钮系列方法
按钮系列方法用于表单项的对应的按钮配置。该方法可以将一个按钮添加到指定选项的右侧，点击这个按钮的时候调用自定义的接口实现功能。

由于按钮系列方法的实体类均继承自EditRowButtonBase，因此在实例化类时你需要手动实现实例的抽象方法：
```
abstract public function judgeIsShow(UrlParamCalculator $calculator);
```

### public function rowButton(EditRowButtonBase $buttonItem);
在指定表单项后方添加一个请求数据的按钮。按钮返回值可以赋值到当前已有表单项中（页面跳转的除外）。
#### 参数：
* $buttonItem 任意EditRowButtonBase子类的实例。所有继承自该类的实例均以EditRowButton开头，且均定义于AntOA/Http/Utils/Model下。可用的实例如下：
    - EditRowButtonApi 点击后直接调用API的按钮
    - EditRowButtonApiWithConfirm 点击后弹出确认框，用户确认后调用API接口的按钮
    - EditRowButtonBlob 点击后下载API接口返回的文件流的按钮
    - EditRowButtonNavigate 点击后跳转页面的按钮
    - EditRowButtonRichText 点击后弹出文本框展示API调用结果的按钮
#### 返回值：
返回this供链式调用

### public function rowNavigateButton(EditRowButtonNavigate $editRowButtonItem);
在指定表单项后方添加一个用于跳转页面的按钮。
#### 参数：
* $editRowButtonItem EditRowButtonNavigate类的实例
#### 返回值：
返回this供链式调用

### public function rowApiButton(EditRowButtonApi $editRowButtonItem);
在指定表单项后方添加一个调用API并将返回值填充到表单的按钮。
#### 参数：
* $editRowButtonItem EditRowButtonApi类的实例
#### 返回值：
返回this供链式调用

### public function rowBlobButton(EditRowButtonBlob $editRowButtonItem);
在指定表单项后方添加一个调用API并将返回值作为文件流下载的按钮。
#### 参数：
* $editRowButtonItem EditRowButtonBlob类的实例
#### 返回值：
返回this供链式调用

### public function rowApiButtonWithConfirm(EditRowButtonApiWithConfirm $editRowButtonItem);
在指定表单项后方添加一个需要弹窗确认之后调用API并将返回值填充到表单的按钮。
#### 参数：
* $editRowButtonItem EditRowButtonApiWithConfirm类的实例
#### 返回值：
返回this供链式调用

### public function rowRichTextButton(EditRowButtonRichText $editRowButtonItem);
在指定表单项后方添加一个调用API并将返回值作为富文本弹窗展示的按钮。
#### 参数：
* $editRowButtonItem EditRowButtonRichText类的实例
#### 返回值：
返回this供链式调用

## GridEditForm对象的钩子方法
如果你想监听用户表单的内容的变化，你可以通过本方法来实现。比如你可以使用本功能实现点击按钮后切换展示的表单项。

### public function setChangeHook(CreateOrEditColumnChangeHook $hook);
为指定表单项添加一个变化监听钩子，这个值发生变化时会触发参数对应的回调方法。
#### 参数：
* $hook CreateOrEditColumnChangeHook类的实例，内含用于客户端回调的抽象方法。
#### 返回值：
返回this供链式调用