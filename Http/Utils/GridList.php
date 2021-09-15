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
use Modules\AntOA\Http\Utils\hook\ButtonCondition;


class GridList implements JsonSerializable {
    const TEXT = "TEXT"; //文本类型展示
    const DISPLAY = "DISPLAY"; //文本类型展示，且不从数据库查询。需要通过HOOK设置
    const RICH_DISPLAY = "RICH_DISPLAY"; //富文本类型展示，且不从数据库查询。需要通过HOOK设置
    const PICTURE = "PICTURE"; //图片类型展示，需在extra中指定图片宽高
    const ENUM = "ENUM"; //枚举类型展示，需要指定键值对用于确定ENUM对应关系
    const RICH_TEXT = "RICH_TEXT"; //富文本类型展示
    const FILTER_HIDDEN = "FILTER_HIDDEN"; //隐藏类型筛选，用于外部传入
    const FILTER_TEXT = "FILTER_TEXT"; //文本类型筛选，筛选方式为%keyword%
    const FILTER_STARTTIME = "FILTER_STARTTIME"; //开始时间类型筛选，筛选结果为大于等于该结束时间的行
    const FILTER_ENDTIME = "FILTER_ENDTIME"; //结束时间类型筛选，筛选结果为小于等于该结束时间的行
    const FILTER_ENUM = "FILTER_ENUM"; //单选类型的筛选，需要指定键值对用于确定ENUM对应关系
    private $columns = []; //列表的所有列（col）、注释（tip）、类型（type）、额外数据（extra）
    private $filter_columns = []; //列表的所有筛选列（col）、注释（tip）、类型（type）、额外数据（extra）
    private $filter_user = []; //列表页根据用户UID筛选的列（col）、类型（type）
    private $header_buttons = []; //顶部创建外所有按钮的内容（title）、跳转链接（url）、按钮类型(type)、操作类型（btn_do_type）
    private $row_buttons = []; //每行编辑与删除外所有按钮的内容（title）、跳转链接（url）、按钮类型(type)、操作类型（btn_do_type）
    private $join = []; //左连接的所有表
    private $delete_join = []; //删除表的时候这里面的所有相关表都会根据条件删除
    private $hasCreate = true; //列表页是否有创建按钮
    private $hasEdit = true; //列表页是否有编辑按钮
    private $hasDelete = true; //列表页是否有删除按钮
    private $_list = null; //DBListOperator对象
    private $displayColumn = null; //编辑页与创建页中使用的字段
    private $_order = null; //排序规则

    /**
     * 构造方法
     * @param DBListOperator $table 表接口
     */
    public function __construct($table) {
        $this->_list = $table;
    }

    /**
     * 获取数据库操作对象
     * @return DBListOperator 数据库操作DB的Builder对象
     */
    public function getDBObject() {
        return $this->_list;
    }

    /**
     * 序列化对象为数组形式
     * @return array 序列化后的数组
     */
    public function getArr() {
        return [
            "_list"          => $this->_list,
            "columns"        => $this->columns,
            "join"           => $this->join,
            "delete_join"    => $this->delete_join,
            "filter_columns" => $this->filter_columns,
            "filter_user"    => $this->filter_user,
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
     * 设置uid筛选项
     * @param String $columnType 筛选类型，可选类型为GridList对应的FILTER_开头的静态属性
     * @param String $col 数据库列名
     * @return GridList 返回this以便链式调用
     */
    public function filterUid($columnType, $col) {
        $this->filter_user[] = [
            "type" => $columnType,
            "col"  => $col
        ];
        return $this;
    }

    /**
     * 创建一个头部页面跳转按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮的跳转链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param array $buttonDestColumn 跳转携带到目标页面的键值对数组，将页面的参数按照值对应的键传入新页面
     * @return GridList 返回this以便链式调用
     */
    public function navigateButton($buttonName, $url, $buttonType = 'primary', $buttonDestColumn = []) {
        $this->header_buttons[] = [
            "btn_do_type" => "navigate",
            "title"       => $buttonName,
            "url"         => $url,
            "dest_col"    => $buttonDestColumn,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个每行页面跳转按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮的跳转链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param String|array $buttonDestColumn 跳转携带ID到目标页面的参数名，默认为id。如果传入为键值对数组，则对应将页面及行对应的参数（行优先）按照值对应的键传入新页面
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowNavigateButton($buttonName, $url, $buttonType = 'primary', $buttonDestColumn = 'id', $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "navigate",
            "title"          => $buttonName,
            "url"            => $url,
            "type"           => $buttonType,
            "dest_col"       => $buttonDestColumn,
            "show_condition" => $condition
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
     * 创建一个头部文件BLOB下载调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的接口链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param String $file_name 按钮弹出下载时的文件名，默认为"文件"
     * @return GridList 返回this以便链式调用
     */
    public function blobButton($buttonName, $url, $buttonType = 'primary', $file_name = "文件") {
        $this->header_buttons[] = [
            "btn_do_type" => "blob:" . $file_name,
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
    public function apiButtonWithConfirm($buttonName, $url, $buttonType = 'primary') {
        $this->header_buttons[] = [
            "btn_do_type" => "api_confirm",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个需要弹窗输入内容的头部API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 表单提交的目标链接，post请求
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param GridCreateForm $gridCreateForm GridCreateForm对象，待展示的表单
     * @return GridList 返回this以便链式调用
     */
    public function apiButtonWithForm($buttonName, $url, $buttonType = 'primary', $gridCreateForm = null) {
        $this->header_buttons[] = [
            "btn_do_type" => "api_form",
            "title"       => $buttonName,
            "url"         => $url,
            "type"        => $buttonType,
            "extra"       => $gridCreateForm
        ];
        return $this;
    }

    /**
     * 创建一个头部弹窗展示富文本的模态框的按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $html 展示富文本的接口url
     * @param String $buttonType 按钮的type属性，默认为primary
     * @return GridList 返回this以便链式调用
     */
    public function richTextButton($buttonName, $html, $buttonType = 'primary') {
        $this->header_buttons[] = [
            "btn_do_type" => "rich_text",
            "title"       => $buttonName,
            "html"        => $html,
            "type"        => $buttonType
        ];
        return $this;
    }

    /**
     * 创建一个每行API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowApiButton($buttonName, $url, $buttonType = 'primary', $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "api",
            "title"          => $buttonName,
            "url"            => $url,
            "type"           => $buttonType,
            "show_condition" => $condition
        ];
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的每行API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowApiButtonWithConfirm($buttonName, $url, $buttonType = 'primary', $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "api_confirm",
            "title"          => $buttonName,
            "url"            => $url,
            "type"           => $buttonType,
            "show_condition" => $condition
        ];
        return $this;
    }

    /**
     * 创建一个需要弹窗输入内容的每行API调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 表单提交的目标链接，post请求
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param GridCreateForm $gridCreateForm GridCreateForm对象，待展示的表单
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowApiButtonWithForm($buttonName, $url, $buttonType = 'primary', $gridCreateForm = null, $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "api_form",
            "title"          => $buttonName,
            "url"            => $url,
            "type"           => $buttonType,
            "extra"          => $gridCreateForm,
            "show_condition" => $condition
        ];
        return $this;
    }

    /**
     * 创建一个每行弹窗展示富文本的模态框的按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $html 展示富文本的接口url，每行会带有行参数
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowRichTextButton($buttonName, $html, $buttonType = 'primary', $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "rich_text",
            "title"          => $buttonName,
            "html"           => $html,
            "type"           => $buttonType,
            "show_condition" => $condition
        ];
        return $this;
    }

    /**
     * 创建一个每行文件BLOB下载调用按钮
     * @param String $buttonName 按钮的内容文字
     * @param String $url 按钮调用的接口链接
     * @param String $buttonType 按钮的type属性，默认为primary
     * @param String $file_name 按钮弹出下载时的文件名，默认为"文件"
     * @param ButtonCondition|null $condition 是否显示该按钮的回调
     * @return GridList 返回this以便链式调用
     */
    public function rowBlobButton($buttonName, $url, $buttonType = 'primary', $file_name = "文件", $condition = null) {
        $this->row_buttons[] = [
            "btn_do_type"    => "blob:" . $file_name,
            "title"          => $buttonName,
            "url"            => $url,
            "type"           => $buttonType,
            "show_condition" => $condition
        ];
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
