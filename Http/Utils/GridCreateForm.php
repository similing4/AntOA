<?php
/**
 * FileName:GridCreateForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/9
 * Time:16:29
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use Illuminate\Support\Facades\DB;
use JsonSerializable;
use Modules\AntOA\Http\Utils\hook\CreateOrEditColumnChangeHook;

class GridCreateForm implements JsonSerializable {
    private $_table; //DBCreateOperator类型对象
    const COLUMN_TEXT = "COLUMN_TEXT"; //文本数据
    const COLUMN_NUMBER_DIVIDE = "COLUMN_NUMBER_DIVIDE"; //进行预除运算的数字数据，提交时会乘回来
    const COLUMN_TEXTAREA = "COLUMN_TEXTAREA"; //多行文本数据
    const COLUMN_PASSWORD = "COLUMN_PASSWORD"; //密码数据
    const COLUMN_SELECT = "COLUMN_SELECT"; //下拉单选
    const COLUMN_RADIO = "COLUMN_RADIO"; //Radio单选
    const COLUMN_CHECKBOX = "COLUMN_CHECKBOX"; //多选
    const COLUMN_TREE_CHECKBOX = "COLUMN_TREE_CHECKBOX"; //树形结构多选
    const COLUMN_TIMESTAMP = "COLUMN_TIMESTAMP"; //时间选择
    const COLUMN_RICHTEXT = "COLUMN_RICHTEXT"; //富文本
    const COLUMN_PICTURE = "COLUMN_PICTURE"; //图片
    const COLUMN_FILE = "COLUMN_FILE"; //文件
    const COLUMN_PICTURES = "COLUMN_PICTURES"; //多图片
    const COLUMN_CHOOSE = "COLUMN_CHOOSE"; //级联选择
    const COLUMN_FILES = "COLUMN_FILES"; //多文件
    const COLUMN_DISPLAY = "COLUMN_DISPLAY"; //只用来展示的行，不会提交
    const COLUMN_HIDDEN = "COLUMN_HIDDEN"; //隐藏的行，会提交
    const COLUMN_CHILDREN_CHOOSE = "COLUMN_CHILDREN_CHOOSE"; //子表选择，将子表的ID作为值进行选择
    private $columns = []; //创建页的所有行（col）、注释（tip）、类型（type）、额外数据（extra）
    private $columnsApiButton = []; //创建页的每行自定义API按钮
    private $defaultValues = []; //默认值，可为数组或字符串，如果为数组那么为静态默认值，如果为字符串那么为根据接口获取的动态默认值
    private $changeHook = null; //数据变更时的钩子

    /**
     * 工厂方法用于创建空的GridCreateForm对象用于apiButtonWithForm方法。
     * @return GridCreateForm
     */
    public static function EmptyForm() {
        return new self(new class(DB::table("")) extends DBCreateOperator {
        });
    }

    /**
     * 构造方法
     * @param DBCreateOperator $table 表接口
     */
    public function __construct($table) {
        $this->_table = $table;
    }

    /**
     * 序列化对象为数组形式
     * @return array 序列化后的数组
     */
    public function getArr() {
        return [
            "table"              => $this->_table,
            "columns"            => $this->columns,
            "default_values"     => $this->defaultValues,
            "columns_api_button" => $this->columnsApiButton,
            "change_hook"        => $this->changeHook
        ];
    }

    /**
     * 序列化对象
     * @return String 序列化后的JSON
     */
    public function json() {
        return json_encode([
            "columns"            => $this->columns,
            "default_values"     => $this->defaultValues,
            "columns_api_button" => $this->columnsApiButton,
            "change_hook"        => $this->changeHook ? $this->changeHook['columns'] : null
        ]);
    }

    /**
     * 序列化对象
     * @return array 序列化后的JSON
     */
    public function jsonSerialize() {
        return json_decode($this->json(), true);
    }

    /**
     * 指定一列
     * @param String $columnType 列表类型，可选类型为GridCreateForm对应的静态属性
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $extra 该列类型的对应额外数据
     * @return GridCreateForm 返回this以便链式调用
     * @deprecated
     */
    public function column($columnType, $col, $colTip, $extra = []) {
        $this->columns[] = [
            "type"  => $columnType,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $extra
        ];
        return $this;
    }


    /**
     * 指定一个文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnText($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_TEXT,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个进行预除运算的数字字段，提交时会乘回来
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param Number $divide 除数
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnNumberDivide($col, $colTip, $divide) {
        $this->columns[] = [
            "type"  => self::COLUMN_NUMBER_DIVIDE,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $divide
        ];
        return $this;
    }

    /**
     * 指定一个Textarea
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTextarea($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_TEXTAREA,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个密码输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPassword($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_PASSWORD,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个下拉单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $keyValMap 单选的键值对数组，键为数据库的字段值，值为选择时展示的值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnSelect($col, $colTip, array $keyValMap) {
        $this->columns[] = [
            "type"  => self::COLUMN_SELECT,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => (object)$keyValMap
        ];
        return $this;
    }

    /**
     * 指定一个Radio单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $keyValMap 单选的键值对数组，键为数据库的字段值，值为选择时展示的值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnRadio($col, $colTip, array $keyValMap) {
        $this->columns[] = [
            "type"  => self::COLUMN_RADIO,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => (object)$keyValMap
        ];
        return $this;
    }

    /**
     * 指定一个多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $keyValMap 多选的键值对数组，键为数据库的字段值，值为选择时展示的值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnCheckbox($col, $colTip, array $keyValMap) {
        $this->columns[] = [
            "type"  => self::COLUMN_CHECKBOX,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => (object)$keyValMap
        ];
        return $this;
    }

    /**
     * 指定一个树形多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $keyValMap 详见https://www.antdv.com/components/tree-select-cn的tree-data属性
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTreeCheckbox($col, $colTip, array $keyValMap) {
        $this->columns[] = [
            "type"  => self::COLUMN_TREE_CHECKBOX,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $keyValMap
        ];
        return $this;
    }

    /**
     * 指定一个时间选择输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTimestamp($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_TIMESTAMP,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个时富文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnRichText($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_RICHTEXT,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个图片选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPicture($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_PICTURE,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个文件选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnFile($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_FILE,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个图片多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPictures($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_PICTURES,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个文件多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnFiles($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_FILES,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个级联选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $keyValMap 详见https://www.antdv.com/components/cascader-cn
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnChoose($col, $colTip, array $keyValMap) {
        $this->columns[] = [
            "type"  => self::COLUMN_CHOOSE,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $keyValMap
        ];
        return $this;
    }

    /**
     * 指定一个展示框，不会被提交
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $noContentVal 没有对应的查询值时的默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnDisplay($col, $colTip, $noContentVal = "") {
        $this->columns[] = [
            "type"  => self::COLUMN_DISPLAY,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $noContentVal === "" ? [] : $noContentVal
        ];
        return $this;
    }

    /**
     * 指定一个隐藏的行，会被提交，可以用来接其它页面传来的参数
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnHidden($col, $colTip) {
        $this->columns[] = [
            "type"  => self::COLUMN_HIDDEN,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => []
        ];
        return $this;
    }

    /**
     * 指定一个子表选择行，子表的第一个设置的列会被作为最终选择的值
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param GridList $gridList 用于选择的GridList实例
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnChildrenChoose($col, $colTip, GridList $gridList) {
        $this->columns[] = [
            "type"  => self::COLUMN_CHILDREN_CHOOSE,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $gridList
        ];
        return $this;
    }

    /**
     * 设置默认值
     * @param array $defaultValues 默认值的键值对数组
     * @return GridCreateForm 返回this以便链式调用
     */
    public function defaultVal($defaultValues) {
        $this->defaultValues = $defaultValues;
        return $this;
    }

    /**
     * 通过接口设置默认值
     * @param string $url 默认值接口url
     * @return GridCreateForm 返回this以便链式调用
     */
    public function defaultValFromApi($url) {
        $this->defaultValues = $url;
        return $this;
    }

    /**
     * 指定一列后方请求数据的按钮
     * @param string $col 列名
     * @param string $buttonName 按钮名称
     * @param string $url 按钮请求的接口地址
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnApiButton($col, $buttonName, $url, $buttonType = 'primary') {
        $this->columnsApiButton[] = [
            "column" => $col,
            "title"  => $buttonName,
            "url"    => $url,
            "type"   => $buttonType
        ];
        return $this;
    }

    /**
     * 添加内容变更钩子
     * @param string $cols 监听的列列表
     * @param CreateOrEditColumnChangeHook $hook 行数据变更时的钩子
     * @return GridCreateForm 返回this以便链式调用
     */
    public function setChangeHook($cols, CreateOrEditColumnChangeHook $hook) {
        $this->changeHook = [
            "columns" => $cols,
            "hook"    => $hook
        ];
        return $this;
    }
}
