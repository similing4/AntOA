<?php
/**
 * FileName:GridEditForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:15:37
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use JsonSerializable;
use Modules\AntOA\Http\Utils\hook\CreateOrEditColumnChangeHook;

class GridEditForm implements JsonSerializable {
    private $_table;
    const COLUMN_TEXT = "COLUMN_TEXT"; //文本数据
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
    private $columns = []; //编辑页的所有行（col）、注释（tip）、类型（type）、额外数据（extra）
    private $columnsApiButton = []; //编辑页的每行自定义API按钮
    private $changeHook = null; //数据变更时的钩子

    /**
     * 构造方法
     * @param DBEditOperator $table 表接口
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
     * @param String $columnType 列表类型，可选类型为GridEditForm对应的静态属性
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $extra 该列类型的对应额外数据
     * @return GridEditForm 返回this以便链式调用
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
     * 指定一列后方请求数据的按钮
     * @param string $col 列名
     * @param string $buttonName 按钮名称
     * @param string $url 按钮请求的接口地址
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridEditForm 返回this以便链式调用
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
     * @return GridEditForm 返回this以便链式调用
     */
    public function setChangeHook($cols, CreateOrEditColumnChangeHook $hook) {
        $this->changeHook = [
            "columns" => $cols,
            "hook"    => $hook
        ];
        return $this;
    }
}
