<?php
/**
 * FileName:GridList.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/6
 * Time:13:09
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;

use \Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use JsonSerializable;


class GridList implements JsonSerializable {
    const TEXT = "TEXT"; //文本类型展示
    const PICTURE = "PICTURE"; //图片类型展示，需在extra中指定图片宽高
    const ENUM = "ENUM"; //枚举类型展示，需要指定键值对用于确定ENUM对应关系
    const RICH_TEXT = "RICH_TEXT"; //富文本类型展示
    const FILTER_TEXT = "FILTER_TEXT"; //文本类型筛选，筛选方式为%keyword%
    const FILTER_STARTTIME = "FILTER_STARTTIME"; //开始时间类型筛选，筛选结果为大于等于该结束时间的行
    const FILTER_ENDTIME = "FILTER_ENDTIME"; //结束时间类型筛选，筛选结果为小于等于该结束时间的行
    const FILTER_ENUM = "FILTER_ENUM"; //单选类型的筛选，需要指定键值对用于确定ENUM对应关系
    private $columns = []; //列表的所有列（col）、注释（tip）、类型（type）、额外数据（extra）
    private $filter_columns = []; //列表的所有筛选列（col）、注释（tip）、类型（type）、额外数据（extra）
    private $header_buttons = []; //顶部创建外所有按钮的内容（title）、跳转链接（url）、按钮类型(type)、操作类型（btn_do_type）
    private $row_buttons = []; //每行编辑与删除外所有按钮的内容（title）、跳转链接（url）、按钮类型(type)、操作类型（btn_do_type）
    private $join = []; //左连接的所有表
    private $delete_join = []; //删除表的时候这里面的所有相关表都会根据条件删除
    private $hasCreate = true;
    private $hasEdit = true;
    private $hasDelete = true;
    private $_list = null;
    private $displayColumn = null;
    private $_order = null;

    /**
     * 构造方法
     * @param String $table 表接口
     */
    public function __construct($table) {
        $this->_list = $table;
    }

    /**
     * 获取数据库操作对象
     * @return Builder 数据库操作DB的Builder对象
     */
    public function getDBObject() {
        return DB::table($this->_list);
    }

    /**
     * 获取数据库操作对象
     * @return Builder 数据库操作DB的Builder对象
     */
    public function getDBObjectWithJoin() {
        $ret = DB::table($this->_list . " as antoa_user");
        foreach ($this->join as $join)
            $ret = $ret->join($join[0], $join[1], $join[2]);
        return $ret;
    }

    /**
     * 序列化对象为数组形式
     * @return array 序列化后的数组
     */
    public function getArr() {
        return [
            "columns"        => $this->columns,
            "join"           => $this->join,
            "delete_join"    => $this->delete_join,
            "filter_columns" => $this->filter_columns,
            "header_buttons" => $this->header_buttons,
            "row_buttons"    => $this->row_buttons,
            "hasCreate"      => $this->hasCreate,
            "hasEdit"        => $this->hasEdit,
            "hasDelete"      => $this->hasDelete,
            "displayColumn"  => $this->displayColumn,
            "orderBy"        => $this->_order
        ];
    }

    /**
     * 序列化对象
     * @return String 序列化后的JSON
     */
    public function json() {
        return json_encode([
            "columns"        => $this->columns,
            "filter_columns" => $this->filter_columns,
            "header_buttons" => $this->header_buttons,
            "row_buttons"    => $this->row_buttons,
            "hasCreate"      => $this->hasCreate,
            "hasEdit"        => $this->hasEdit,
            "hasDelete"      => $this->hasDelete,
            "displayColumn"  => $this->displayColumn
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
     * @param String $columnType 列表类型，可选类型为GridList对应的非FILTER_开头的静态属性
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array $extra 该列类型的对应额外数据
     * @return GridList 返回this以便链式调用
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
     * 设置是否显示创建按钮及使用创建页面
     * @param Boolean $bool 为真时开启创建功能，否则关闭创建功能
     * @return GridList 返回this以便链式调用
     */
    public function useCreate($bool) {
        $this->hasCreate = $bool;
        return $this;
    }

    /**
     * 设置是否显示编辑按钮及使用编辑页面
     * @param Boolean $bool 为真时开启编辑功能，否则关闭编辑功能
     * @return GridList 返回this以便链式调用
     */
    public function useEdit($bool) {
        $this->hasEdit = $bool;
        return $this;
    }

    /**
     * 设置是否显示删除按钮
     * @param Boolean $bool 为真时开启删除功能，否则关闭删除功能
     * @return GridList 返回this以便链式调用
     */
    public function useDelete($bool) {
        $this->hasDelete = $bool;
        return $this;
    }

    /**
     * 创建一个筛选项
     * @param String $columnType 筛选类型，可选类型为GridList对应的FILTER_开头的静态属性
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该筛选项的名称
     * @param array $extra 该列类型的对应额外数据
     * @return GridList 返回this以便链式调用
     */
    public function filter($columnType, $col, $colTip, $extra = []) {
        $this->filter_columns[] = [
            "type"  => $columnType,
            "col"   => $col,
            "tip"   => $colTip,
            "extra" => $extra
        ];
        return $this;
    }

    /**
     * 创建一个头部页面跳转按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮的跳转链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function navigateButton($buttonName, $url, $buttonType = 'primary') {
        $this->header_buttons[] = [
            "btn_do_type" => "navigate",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个每行页面跳转按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮的跳转链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function rowNavigateButton($buttonName, $url, $buttonType = 'primary') {
        $this->row_buttons[] = [
            "btn_do_type" => "navigate",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个头部API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function apiButton($buttonName, $url, $buttonType = 'primary') {
        $this->header_buttons[] = [
            "btn_do_type" => "api",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的头部API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function apiButtonWithConfrim($buttonName, $url, $buttonType = 'primary') {
        $this->header_buttons[] = [
            "btn_do_type" => "api_confirm",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个每行API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function rowApiButton($buttonName, $url, $buttonType = 'primary') {
        $this->row_buttons[] = [
            "btn_do_type" => "api",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的每行API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function rowApiButtonWithConfirm($buttonName, $url, $buttonType = 'primary') {
        $this->row_buttons[] = [
            "btn_do_type" => "api_confirm",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 左连接
     * @param String $table 左连接的表格
     * @param String $id1 左连接表的ID
     * @param String $id2 主表ID
     * @return GridList 返回this以便链式调用
     */
    public function leftJoin($table, $id1, $id2) {
        $this->join[] = [$table, $id1, $id2];
        return $this;
    }

    /**
     * 删除时同时对指定从表一并删除
     * @param String $table 删除时同时删除的表
     * @param String $id1 主表对应的字段，取其值作为从表的条件
     * @param String $id2 从表对应的删除条件列，该列值等于$id1指定列值时删除
     * @return GridList 返回this以便链式调用
     */
    public function deleteJoin($table, $id1, $id2) {
        $this->delete_join[] = [$table, $id1, $id2];
        return $this;
    }

    /**
     * 设置创建/编辑页选中后展示的列
     * @param String $col 展示的列
     * @return GridList 返回this以便链式调用
     */
    public function setDisplayColumn($col) {
        $this->displayColumn = $col;
        return $this;
    }

    /**
     * 设置列表的排序规则
     * @param String $col 排序的列
     * @param String $type 排序方式，如DESC，ASC
     * @return GridList 返回this以便链式调用
     */
    public function order($col, $type) {
        $this->_order = [$col, $type];
        return $this;
    }
}
