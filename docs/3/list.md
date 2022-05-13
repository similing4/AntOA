## GridList对象用法
首先我们看列表页的分块：
![/admin/work/list](grid_1.jpg)
这里除了统计部分由控制器的statistic方法配置、面包屑部分由antoa/config.php配置外，其余部分均由GridList配置。

GridList配置需要在AntOAController的子类中的grid方法中配置。依然以UserController举例：
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

## GridList对象的实例化
GridList对象需要由AntOAController的grid方法的$grid参数的list方法创建。该参数接收一个DBListOperator对象。你需要按照你的需求自行构造DBListOperator对象。例：
```
	function grid(Grid $grid) {
    	$grid->list(new class(DB::table("user")) extends DBListOperator{}); //这里参数是一个继承自DBListOperator的匿名对象，返回值是GridList对象。
    }
```
其中DBListOperator类属于Illuminate\Database\Query\Builder的扩展类，它的定义如下：
```
namespace Modules\AntOA\Http\Utils;
use Illuminate\Database\Query\Builder;
abstract class DBListOperator {
    public $builder; //DB类产生的对象，于构造方法中传入

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }

    public function doClone() {
        $ret = clone $this;
        $ret->builder = clone $this->builder;
        return $ret;
    }

    //where方法，设置的对应column会作为条件传入。你可以根据column自定义设置传入条件内容
    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

    //whereIn方法，设置的对应column会作为条件传入。你可以根据column自定义设置传入条件内容
    public function whereIn($column, $values, $boolean = 'and', $not = false) {
        $this->builder->whereIn($column, $values, $boolean, $not);
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
你可以在你的实体类中重写这个父类方法来实现你的各种功能。

## GridList对象的filter系列实例方法
filter系列方法用于列表页的筛选相关配置。该方法可以根据DBListOperator中查询的字段进行筛选。

**注：下列内容中提到的“数据库”均指的是DBListOperator实例指定的查询结果。**

### public function filter($filterItem);
filter的通用方法
#### 参数：
* $filterItem 任意ListFilterBase子类的实例。所有继承自该类的实例均以ListFilter开头，且均定义于AntOA/Http/Utils/Model下。可用的实例如下：
	- ListFilterCascader 级联筛选（如用于省市区）
	- ListFilterStartTime 时间段开始时间筛选
	- ListFilterEndTime 时间段结束时间筛选
	- ListFilterEnum 下拉单选筛选（类似于html的select标签）
	- ListFilterHidden 隐式筛选（根据URL参数筛选）
	- ListFilterMultiSelect 多选筛选（使用whereIn筛选，没选择则不筛选）
	- ListFilterText 文本筛选（like %内容%）
	- ListFilterUID 根据当前登录的用户UID进行筛选
#### 返回值：
返回this供链式调用

### public function filterHidden($col);
隐式筛选（根据URL参数筛选）
#### 参数：
* $col 筛选的数据库对应的字段名。
#### 返回值：
返回this供链式调用

### public function filterText($col, $colTip);
文本筛选（like %内容%）
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function filterStartTime($col, $colTip);
时间段开始时间筛选
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function filterEndTime($col, $colTip);
时间段结束时间筛选
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function filterEnum($col, $colTip, array $options);
下拉单选筛选（类似于html的select标签）
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
* $options 选项（EnumOption对象数组）
#### 返回值：
返回this供链式调用

### public function filterMultiSelect($col, $colTip, array $options);
多选筛选（使用whereIn筛选，没选择则不筛选）
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
* $options 选项（EnumOption对象数组）
#### 返回值：
返回this供链式调用

### public function filterCascader($col, $colTip, array $options);
级联筛选（如用于省市区）
#### 参数：
* $col 筛选的数据库对应的字段名。
* $colTip 提示内容
* $options 选项（CascaderNode对象数组）
#### 返回值：
返回this供链式调用

### public function filterUid($col);
根据当前登录的用户UID进行筛选
#### 参数：
* $col 筛选的数据库对应的字段名。
#### 返回值：
返回this供链式调用


## GridList对象的column系列实例方法
column系列方法用于列表页的表格的列的配置。该方法可以将DBListOperator中对应的查询字段通过一定的变化展示到页面上。

**注：下列内容中提到的“数据库”均指的是DBListOperator实例指定的查询结果。**

### public function column($column);
column的通用方法
#### 参数：
* $column 任意ListTableColumnBase子类的实例。所有继承自该类的实例均以ListTableColumn开头，且均定义于AntOA/Http/Utils/Model下。可用的实例如下：
	- ListTableColumnDisplay 只用于展示，需要通过hook方法配置其值，默认没有值。
	- ListTableColumnDivideNumber 用于将数据库行中指定列的查询结果以数字除以固定数值展示。如将金额（单位分）100转换为1元展示。
	- ListTableColumnEnum 用于将数据库行中指定列根据提供的字典翻译为对应的展示内容。如0为禁用1为启用，查询结果为0时就展示禁用。
	- ListTableColumnHidden 用于查询数据库指定列，但不在页面上展示
	- ListTableColumnPicture 用于将数据库行中指定列作为图片地址输出为图片在表内展示。
	- ListTableColumnRichDisplay 只用于展示富文本内容，需要通过hook方法配置其值，默认没有值
	- ListTableColumnRichText 用于数据库行中指定列作为富文本内容输出。
	- ListTableColumnText 用于将数据库行中指定列作为普通文本内容输出。
#### 返回值：
返回this供链式调用

### public function columnText($col, $colTip);
用于将数据库行中指定列作为普通文本内容输出。
#### 参数：
* $col 数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function columnDisplay($col, $colTip);
只用于展示，需要通过hook方法配置其值，默认没有值。
#### 参数：
* $col 查询结果中对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function columnRichDisplay($col, $colTip);
只用于展示富文本内容，需要通过hook方法配置其值，默认没有值
#### 参数：
* $col 查询结果中对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function columnPicture($col, $colTip, $width, $height);
用于将数据库行中指定列作为图片地址输出为图片在表内展示。
#### 参数：
* $col 数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function columnEnum($col, $colTip, array $options);
用于将数据库行中指定列根据提供的字典翻译为对应的展示内容。如0为禁用1为启用，查询结果为0时就展示禁用。
#### 参数：
* $col 数据库对应的字段名。
* $colTip 提示内容
* $options EnumOpiton对象的数组
#### 返回值：
返回this供链式调用
#### 范例：
```
$gridList->columnEnum("status","状态", [
	new EnumOpiton("0", "禁用"),
	new EnumOpiton("1", "启用")
]);
```

### public function columnRichText($col, $colTip);
用于数据库行中指定列作为富文本内容输出。
#### 参数：
* $col 数据库对应的字段名。
* $colTip 提示内容
#### 返回值：
返回this供链式调用

### public function columnHidden($col);
用于查询数据库指定列，但不在页面上展示。
#### 参数：
* $col 数据库对应的字段名。
#### 返回值：
返回this供链式调用

### public function columnDivideNumber($col, $colTip, $divide, $unit = '');
用于将数据库行中指定列的查询结果以数字除以固定数值展示。如将金额（单位分）100转换为1元展示。
#### 参数：
* $col 数据库对应的字段名。
* $colTip 提示内容
* $divide 被除数（如100分转为1元应传100）
* $unit 单位，默认为空（如100转为1元应传入“元”）
#### 返回值：
返回this供链式调用



## HeaderButton系列方法
列表页的按钮分为顶部按钮（HeaderButton）、每行按钮（RowButton）两种。HeaderButton系列方法用于配置顶部按钮。

默认情况下顶部按钮只有一个“创建”按钮，你可以使用useCreate(false);方法禁用创建按钮。
下面的参数类均继承自ListHeaderButtonBase类，实例化时都需要实现如下两个方法：
```
/**
 * 根据页面参数计算实际调用地址，返回值将会被用作finalUrl参数
 * @param UrlParamCalculator $calculator 传入的页面参数的实例
 * @return array<UrlParamCalculatorParamItem> 并入到baseURL的URL参数
 */
abstract public function calcButtonParam(UrlParamCalculator $calculator);

/**
 * 根据页面参数计算当前按钮是否显示
 * @param UrlParamCalculator $calculator 传入的页面参数的实例
 * @return bool 返回真则显示，否则不显示
 */
abstract public function judgeIsShow(UrlParamCalculator $calculator);
```
其中calcButtonParam方法用于计算按钮跳转或调用的目标链接的携带参数，其UrlParamCalculator $calculator封装了页面携带的参数信息。

其中judgeIsShow方法用于根据页面参数判断是否显示这个按钮。

### public function headerNavigateButton(ListHeaderButtonNavigate $listHeaderButtonItem);
设置跳转到新页面的按钮
#### 参数：
* $listHeaderButtonItem ListHeaderButtonNavigate实例。
#### 返回值：
返回this供链式调用

### public function headerApiButton(ListHeaderButtonApi $listHeaderButtonItem);
设置调用普通API接口的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为成功提示；失败时status为0，msg为失败原因）
#### 参数：
* $listHeaderButtonItem ListHeaderButtonApi实例。
#### 返回值：
返回this供链式调用

### public function headerApiButtonWithConfirm(ListHeaderButtonApiWithConfirm $listHeaderButtonItem);
设置需要确认后才能调用普通API接口的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为成功提示；失败时status为0，msg为失败原因）
#### 参数：
* $listHeaderButtonItem ListHeaderButtonApiWithConfirm实例。
#### 返回值：
返回this供链式调用

### public function headerBlobButton(ListHeaderButtonBlob $listHeaderButtonItem);
设置调用返回文件流API接口的顶部按钮
#### 参数：
* $listHeaderButtonItem ListHeaderButtonBlob实例。这个实例在实例化时需要传入至少三个参数：
	- $baseUrl 调用的URL地址（不带参数）
	- $buttonText 按钮文本
	- $downloadFilename 下载的文件名
	- $buttonType = "primary" 按钮样式
#### 返回值：
返回this供链式调用

### public function headerRichTextButton(ListHeaderButtonRichText $listHeaderButtonItem);
设置点击后根据提供的链接调用接口后将接口返回的内容作为富文本展示的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为富文本内容；失败时status为0，msg为失败原因）
#### 参数：
* $listHeaderButtonItem ListHeaderButtonRichText实例。
#### 返回值：
返回this供链式调用

## RowButton系列方法
列表页的按钮分为顶部按钮（HeaderButton）、每行按钮（RowButton）两种。RowButton系列方法用于配置每行按钮。

默认情况下每行按钮只有一个“编辑”按钮和一个“删除”按钮，你可以使用useEdit(false);方法禁用编辑按钮、useDelete(false);方法禁用删除按钮。
下面的参数类均继承自ListRowButtonBase类，实例化时都需要实现如下两个方法：
```
/**
 * 根据页面参数计算实际调用地址，返回值将会被用作finalUrl参数
 * @param UrlParamCalculator $calculator 传入的页面参数的实例
 * @return array<UrlParamCalculatorParamItem> 并入到baseURL的URL参数
 */
abstract public function calcButtonParam(UrlParamCalculator $calculator);

/**
 * 根据页面参数计算当前按钮是否显示
 * @param UrlParamCalculator $calculator 传入的页面参数的实例
 * @return bool 返回真则显示，否则不显示
 */
abstract public function judgeIsShow(UrlParamCalculator $calculator);
```
其中calcButtonParam方法用于计算按钮跳转或调用的目标链接的携带参数，其UrlParamCalculator $calculator封装了页面携带的参数信息及对应行的数据信息。

其中judgeIsShow方法用于根据页面参数信息及对应行的数据信息判断是否显示这个按钮。

### public function rowNavigateButton(ListRowButtonNavigate $listRowButtonItem);
设置跳转到新页面的按钮
#### 参数：
* $listRowButtonItem ListRowButtonNavigate实例。
#### 返回值：
返回this供链式调用

### public function rowApiButton(ListRowButtonApi $listRowButtonItem);
设置调用普通API接口的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为成功提示；失败时status为0，msg为失败原因）
#### 参数：
* $listRowButtonItem ListRowButtonApi实例。
#### 返回值：
返回this供链式调用

### public function rowApiButtonWithConfirm(ListRowButtonApiWithConfirm $listRowButtonItem);
设置需要确认后才能调用普通API接口的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为成功提示；失败时status为0，msg为失败原因）
#### 参数：
* $listRowButtonItem ListRowButtonApiWithConfirm实例。
#### 返回值：
返回this供链式调用

### public function rowBlobButton(ListRowButtonBlob $listRowButtonItem);
设置调用返回文件流API接口的顶部按钮
#### 参数：
* $listRowButtonItem ListRowButtonBlob实例。这个实例在实例化时需要传入至少三个参数：
	- $baseUrl 调用的URL地址（不带参数）
	- $buttonText 按钮文本
	- $downloadFilename 下载的文件名
	- $buttonType = "primary" 按钮样式
#### 返回值：
返回this供链式调用

### public function rowRichTextButton(ListRowButtonRichText $listRowButtonItem);
设置点击后根据提供的链接调用接口后将接口返回的内容作为富文本展示的顶部按钮（返回值应返回JSON，其字段成功时status为1，data为富文本内容；失败时status为0，msg为失败原因）
#### 参数：
* $listRowButtonItem ListRowButtonRichText实例。
#### 返回值：
返回this供链式调用