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

class GridCreateForm implements JsonSerializable {
    private $_table; //DBCreateOperator类型对象
    const COLUMN_TEXT = "COLUMN_TEXT"; //文本数据
    const COLUMN_TEXTAREA = "COLUMN_TEXTAREA"; //多行文本数据
    const COLUMN_PASSWORD = "COLUMN_PASSWORD"; //密码数据
    const COLUMN_SELECT = "COLUMN_SELECT"; //下拉单选
    const COLUMN_RADIO = "COLUMN_RADIO"; //Radio单选
    const COLUMN_CHECKBOX = "COLUMN_CHECKBOX"; //多选
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
    private $defaultValues = [];

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
            "columns_api_button" => $this->columnsApiButton
        ];
    }

    /**
     * 序列化对象
     * @return String 序列化后的JSON
     */
    public function json() {
        return json_encode([
            "columns"        => $this->columns,
            "default_values" => $this->defaultValues,
            "columns_api_button" => $this->columnsApiButton
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
     * 设置默认值
     * @param array $defaultValues 默认值的键值对数组
     * @return GridCreateForm 返回this以便链式调用
     */
    public function defaultVal($defaultValues) {
        $this->defaultValues = $defaultValues;
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

}
