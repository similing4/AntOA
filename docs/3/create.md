## GridCreateForm对象用法
GridCreateForm配置需要在AntOAController的子类中的grid方法中配置。依然以UserController举例：
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

## GridCreateForm对象的实例化
GridCreateForm对象需要由AntOAController的grid方法的$grid参数的createForm方法创建。该参数接收一个DBCreateOperator对象。你需要按照你的需求自行构造DBCreateOperator对象。例：
```
	function grid(Grid $grid) {
    	$grid->createForm(new class(DB::table("user")) extends DBCreateOperator{}); //这里参数是一个继承自DBCreateOperator的匿名对象，返回值是GridCreateForm对象。
    }
```
其中DBCreateOperator类属于Illuminate\Database\Query\Builder的扩展类，它的定义如下：
```
use Illuminate\Database\Query\Builder;
abstract class DBCreateOperator {
    public $builder;
    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
    public function insert(array $values) {
        return $this->builder->insert($values);
    }
}

```
你可以在你的实体类中重写这个父类方法来实现你的各种功能。

## GridCreateForm对象的column系列实例方法
column系列方法用于表单项的配置。该方法可以将配置的信息以表单的形式展示到页面上并实现功能。
### public function column(CreateColumnBase $columnItem);
column的通用方法
#### 参数：
* $column 任意CreateColumnBase子类的实例。所有继承自该类的实例均以CreateColumn开头，且均定义于AntOA/Http/Utils/Model下。可用的实例如下：
    - CreateColumnCascader 级联选择对象（参考[AntDesignVue-Cascader组件](https://www.antdv.com/components/cascader-cn)）
    - CreateColumnChildrenChoose 表单项为一个按钮，点击该按钮后弹出列表页进行单选
    - CreateColumnDisplay 仅用于展示，需要通过hook设置其值
    - CreateColumnDivideNumber 用于展示数据与数据库字段为除以指定数值关系的需求。如输入的是元数据库中存储是分的情况。
    - CreateColumnEnum 下拉单选（Select标签样式）
    - CreateColumnEnumCheckBox 多选框
    - CreateColumnEnumRadio 圆形Radio单选
    - CreateColumnEnumTreeCheckBox 下拉多选（Select标签样式）
    - CreateColumnFile 选择文件并上传到七牛云，后台接到七牛云目标文件链接
    - CreateColumnFileLocal 选择文件并上传到服务端本地，后台接到上传文件的绝对地址
    - CreateColumnFiles 选择多个文件并上传到七牛云，后台接到七牛云目标文件链接的数组json
    - CreateColumnFilesLocal 选择多个文件并上传到服务端本地，后台接到上传文件的绝对地址的数组json
    - CreateColumnHidden 隐藏字段，需要通过ChangeHook配置其值
    - CreateColumnPassword 密码输入，插入时需要通过CreateHook对其加密。
    - CreateColumnPicture 选择图片并上传到七牛云，后台接到七牛云目标图片链接
    - CreateColumnPictureLocal 选择图片并上传到服务端本地，后台接到上传图片的绝对地址
    - CreateColumnPictures 选择多个图片并上传到七牛云，后台接到七牛云目标图片链接的数组json
    - CreateColumnPicturesLocal 选择多个图片并上传到服务端本地，后台接到上传图片的绝对地址的数组json
    - CreateColumnRichText 富文本输入（WangEditor）
    - CreateColumnText 普通文本输入
    - CreateColumnTextarea 多行文本域输入
    - CreateColumnTimestamp 时间输入
#### 返回值：
返回this供链式调用

### public function columnText($col, $colTip, $defaultVal = '');
设置一个普通文本输入的表单项
#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $divide
* $unit
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnTextarea($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnPassword($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnSelect($col, $colTip, array $options, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnRadio($col, $colTip, array $options, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnCheckbox($col, $colTip, array $options, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnTimestamp($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnRichText($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用

### public function columnPicture($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnFile($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnPictures($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnFiles($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnCascader($col, $colTip, array $options, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $options
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnDisplay($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnHidden($col, $defaultVal);

#### 参数：
* $col 表单name
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $gridListEasy
* $gridListVModelCol
* $gridListDisplayCol
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnPictureLocal($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnFileLocal($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnPicturesLocal($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用
### public function columnFilesLocal($col, $colTip, $defaultVal = '');

#### 参数：
* $col 表单name
* $colTip 表单项目左侧提示内容
* $defaultVal 默认值
#### 返回值：
返回this供链式调用