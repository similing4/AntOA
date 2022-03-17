<?php
/**
 * FileName:GridListEasy.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:15:11
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use JsonSerializable;
use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\AbstractModel\ListFilterCollection;
use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;
use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonCollection;
use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;
use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonCollection;
use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;
use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnCollection;
use Modules\AntOA\Http\Utils\DBListOperator;


class GridListEasy implements JsonSerializable {
    /**
     * @var ListFilterCollection
     * 列表的所有筛选列
     */
    private $listFilterCollection;
    /**
     * @var ListTableColumnCollection
     * 列表的所有列
     */
    private $listTableColumnCollection;
    /**
     * @var ListHeaderButtonCollection
     * 顶部所有按钮
     */
    private $listHeaderButtonCollection;
    /**
     * @var ListRowButtonCollection
     * 每行选中外所有按钮
     */
    private $listRowButtonCollection;
    /**
     * @var DBListOperator
     */
    private $_list = null;

    /**
     * 构造方法
     * @param DBListOperator $table 表接口
     */
    public function __construct(DBListOperator $table) {
        $this->listFilterCollection = new ListFilterCollection();
        $this->listHeaderButtonCollection = new ListHeaderButtonCollection();
        $this->listTableColumnCollection = new ListTableColumnCollection();
        $this->listRowButtonCollection = new ListRowButtonCollection();
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
     * 获取所有筛选对象
     * @return array<ListFilterBase>
     */
    public function getFilterList() {
        return $this->listFilterCollection->getItems();
    }

    /**
     * 获取所有列对象
     * @return array<ListTableColumnBase>
     */
    public function getTableColumnList() {
        return $this->listTableColumnCollection->getItems();
    }

    /**
     * 获取所有页面顶部按钮对象
     * @return array<ListHeaderButtonBase>
     */
    public function getHeaderButtonList() {
        return $this->listHeaderButtonCollection->getItems();
    }

    /**
     * 删除指定页面顶部按钮对象
     * @param array<ListHeaderButtonBase> $items
     */
    public function removeHeaderButtons($items) {
        $this->listHeaderButtonCollection->removeItems($items);
    }

    /**
     * 获取所有列对象
     * @return array<ListRowButtonBase>
     */
    public function getRowButtonList() {
        return $this->listRowButtonCollection->getItems();
    }

    /**
     * 序列化对象
     * @return array 序列化后的JSON
     */
    public function jsonSerialize() {
        return [
            "listFilterCollection"       => $this->listFilterCollection,
            "listTableColumnCollection"  => $this->listTableColumnCollection,
            "listHeaderButtonCollection" => $this->listHeaderButtonCollection,
            "listRowButtonCollection"    => $this->listRowButtonCollection
        ];
    }

    /**
     * 指定一列
     * @param ListTableColumnBase $column 列对象实例
     * @return GridListEasy 返回this以便链式调用
     */
    public function column($column) {
        $this->listTableColumnCollection->addItem($column);
        return $this;
    }

    /**
     * 指定文本列
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnText($col, $colTip) {
        $this->listTableColumnCollection->addItem(new ListTableColumnText($col, $colTip));
        return $this;
    }

    /**
     * 指定展示列，可通过HOOK设置其展示值
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnDisplay($col, $colTip) {
        $this->listTableColumnCollection->addItem(new ListTableColumnDisplay($col, $colTip));
        return $this;
    }

    /**
     * 指定富文本展示列，可通过HOOK设置其展示值
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnRichDisplay($col, $colTip) {
        $this->listTableColumnCollection->addItem(new ListTableColumnRichDisplay($col, $colTip));
        return $this;
    }

    /**
     * 指定图片列
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param Number $width 图片的展示宽度，不填写单位的情况下默认单位为px
     * @param Number $height 图片的展示宽度，不填写单位的情况下默认单位为px
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnPicture($col, $colTip, $width, $height) {
        if (is_numeric($width))
            $width .= "px";
        if (is_numeric($height))
            $height .= "px";
        $this->listTableColumnCollection->addItem(new ListTableColumnPicture($col, $colTip, $width, $height));
        return $this;
    }

    /**
     * 指定单选列
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options ENUM的选项数组
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnEnum($col, $colTip, array $options) {
        $this->listTableColumnCollection->addItem(new ListTableColumnEnum($col, $colTip, $options));
        return $this;
    }

    /**
     * 指定富文本列
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnRichText($col, $colTip) {
        $this->listTableColumnCollection->addItem(new ListTableColumnRichText($col, $colTip));
        return $this;
    }

    /**
     * 隐藏列，查询出来但不展示
     * @param String $col 列名
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnHidden($col) {
        $this->listTableColumnCollection->addItem(new ListTableColumnHidden($col));
        return $this;
    }

    /**
     * 指定除以指定数值后展示的列
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param Number $divide 除数
     * @param String $unit 单位，默认为空
     * @return GridListEasy 返回this以便链式调用
     */
    public function columnDivideNumber($col, $colTip, $divide, $unit = '') {
        $this->listTableColumnCollection->addItem(new ListTableColumnDivideNumber($col, $colTip, $divide, $unit));
        return $this;
    }

    /**
     * 指定隐藏类型筛选列，用于外部传入
     * @param String $col 筛选的列名
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterHidden($col) {
        $this->listFilterCollection->addItem(new ListFilterHidden($col));
        return $this;
    }

    /**
     * 指定文本类型筛选列
     * @param String $col 筛选的列名
     * @param String $colTip 筛选项的名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterText($col, $colTip) {
        $this->listFilterCollection->addItem(new ListFilterText($col, $colTip));
        return $this;
    }

    /**
     * 指定开始时间筛选列
     * @param String $col 筛选的列名
     * @param String $colTip 筛选项的名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterStartTime($col, $colTip) {
        $this->listFilterCollection->addItem(new ListFilterStartTime($col, $colTip));
        return $this;
    }

    /**
     * 指定结束时间筛选列
     * @param String $col 筛选的列名
     * @param String $colTip 筛选项的名称
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterEndTime($col, $colTip) {
        $this->listFilterCollection->addItem(new ListFilterEndTime($col, $colTip));
        return $this;
    }

    /**
     * 指定单选类型筛选列
     * @param String $col 列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options ENUM的选项数组
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterEnum($col, $colTip, array $options) {
        $this->listFilterCollection->addItem(new ListFilterEnum($col, $colTip, $options));
        return $this;
    }

    /**
     * 创建一个筛选项
     * @param ListFilterBase $filterItem 筛选对象
     * @return GridListEasy 返回this以便链式调用
     * @deprecated
     */
    public function filter($filterItem) {
        $this->listFilterCollection->addItem($filterItem);
        return $this;
    }

    /**
     * 设置uid筛选项
     * @param String $col 数据库列名
     * @return GridListEasy 返回this以便链式调用
     */
    public function filterUid($col) {
        $this->listFilterCollection->addItem(new ListFilterUID($col));
        return $this;
    }

    /**
     * 设置是否显示创建按钮及使用创建页面
     * @param Boolean $bool 为真时开启创建功能，否则关闭创建功能
     * @return GridListEasy 返回this以便链式调用
     */
    public function useCreate($bool) {
        $this->hasCreate = $bool;
        return $this;
    }

    /**
     * 设置是否显示编辑按钮及使用编辑页面
     * @param Boolean $bool 为真时开启编辑功能，否则关闭编辑功能
     * @return GridListEasy 返回this以便链式调用
     */
    public function useEdit($bool) {
        $this->hasEdit = $bool;
        return $this;
    }

    /**
     * 设置是否显示删除按钮
     * @param Boolean $bool 为真时开启删除功能，否则关闭删除功能
     * @return GridListEasy 返回this以便链式调用
     */
    public function useDelete($bool) {
        $this->hasDelete = $bool;
        return $this;
    }

    /**
     * 创建一个头部页面跳转按钮
     * @param ListHeaderButtonNavigate $listHeaderButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function headerNavigateButton(ListHeaderButtonNavigate $listHeaderButtonItem) {
        $this->listHeaderButtonCollection->addItem($listHeaderButtonItem);
        return $this;
    }

    /**
     * 创建一个每行页面跳转按钮
     * @param ListRowButtonNavigate $listRowButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function rowNavigateButton(ListRowButtonNavigate $listRowButtonItem) {
        $this->listRowButtonCollection->addItem($listRowButtonItem);
        return $this;
    }

    /**
     * 创建一个头部API调用按钮
     * @param ListHeaderButtonApi $listHeaderButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function headerApiButton(ListHeaderButtonApi $listHeaderButtonItem) {
        $this->listHeaderButtonCollection->addItem($listHeaderButtonItem);
        return $this;
    }

    /**
     * 创建一个每行API调用按钮
     * @param ListRowButtonApi $listRowButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function rowApiButton(ListRowButtonApi $listRowButtonItem) {
        $this->listRowButtonCollection->addItem($listRowButtonItem);
        return $this;
    }

    /**
     * 创建一个头部文件BLOB下载调用按钮
     * @param ListHeaderButtonBlob $listHeaderButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function headerBlobButton(ListHeaderButtonBlob $listHeaderButtonItem) {
        $this->listHeaderButtonCollection->addItem($listHeaderButtonItem);
        return $this;
    }

    /**
     * 创建一个每行文件BLOB下载调用按钮
     * @param ListRowButtonBlob $listRowButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function rowBlobButton(ListRowButtonBlob $listRowButtonItem) {
        $this->listRowButtonCollection->addItem($listRowButtonItem);
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的头部API调用按钮
     * @param ListHeaderButtonApiWithConfirm $listHeaderButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function headerApiButtonWithConfirm(ListHeaderButtonApiWithConfirm $listHeaderButtonItem) {
        $this->listHeaderButtonCollection->addItem($listHeaderButtonItem);
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的每行API调用按钮
     * @param ListRowButtonApiWithConfirm $listRowButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function rowApiButtonWithConfirm(ListRowButtonApiWithConfirm $listRowButtonItem) {
        $this->listRowButtonCollection->addItem($listRowButtonItem);
        return $this;
    }

    /**
     * 创建一个头部弹窗展示富文本的模态框的按钮
     * @param ListHeaderButtonRichText $listHeaderButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function richTextButton(ListHeaderButtonRichText $listHeaderButtonItem) {
        $this->listHeaderButtonCollection->addItem($listHeaderButtonItem);
        return $this;
    }

    /**
     * 创建一个每行弹窗展示富文本的模态框的按钮
     * @param ListRowButtonRichText $listRowButtonItem 按钮项
     * @return GridListEasy 返回this以便链式调用
     */
    public function rowRichTextButton(ListRowButtonRichText $listRowButtonItem) {
        $this->listRowButtonCollection->addItem($listRowButtonItem);
        return $this;
    }
}
