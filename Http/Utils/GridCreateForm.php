<?php
/**
 * FileName:GridCreateForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:15:37
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use JsonSerializable;
use Modules\AntOA\Http\Utils\AbstractModel\CreateOrEditColumnChangeHookCollection;
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnCollection;
use Modules\AntOA\Http\Utils\AbstractModel\CreateRowButtonBase;
use Modules\AntOA\Http\Utils\AbstractModel\CreateRowButtonBaseCollection;
use Modules\AntOA\Http\Utils\hook\CreateOrEditColumnChangeHook;
use Modules\AntOA\Http\Utils\Model\CascaderNode;
use Modules\AntOA\Http\Utils\Model\CreateColumnCascader;
use Modules\AntOA\Http\Utils\Model\CreateColumnChildrenChoose;
use Modules\AntOA\Http\Utils\Model\CreateColumnDisplay;
use Modules\AntOA\Http\Utils\Model\CreateColumnDivideNumber;
use Modules\AntOA\Http\Utils\Model\CreateColumnEnum;
use Modules\AntOA\Http\Utils\Model\CreateColumnEnumCheckBox;
use Modules\AntOA\Http\Utils\Model\CreateColumnEnumRadio;
use Modules\AntOA\Http\Utils\Model\CreateColumnEnumTreeCheckBox;
use Modules\AntOA\Http\Utils\Model\CreateColumnFile;
use Modules\AntOA\Http\Utils\Model\CreateColumnFiles;
use Modules\AntOA\Http\Utils\Model\CreateColumnHidden;
use Modules\AntOA\Http\Utils\Model\CreateColumnPassword;
use Modules\AntOA\Http\Utils\Model\CreateColumnPicture;
use Modules\AntOA\Http\Utils\Model\CreateColumnPictures;
use Modules\AntOA\Http\Utils\Model\CreateColumnRichText;
use Modules\AntOA\Http\Utils\Model\CreateColumnText;
use Modules\AntOA\Http\Utils\Model\CreateColumnTextarea;
use Modules\AntOA\Http\Utils\Model\CreateColumnTimestamp;
use Modules\AntOA\Http\Utils\Model\CreateRowButtonApi;
use Modules\AntOA\Http\Utils\Model\CreateRowButtonApiWithConfirm;
use Modules\AntOA\Http\Utils\Model\CreateRowButtonBlob;
use Modules\AntOA\Http\Utils\Model\CreateRowButtonNavigate;
use Modules\AntOA\Http\Utils\Model\CreateRowButtonRichText;
use Modules\AntOA\Http\Utils\Model\EnumOption;
use Modules\AntOA\Http\Utils\Model\GridListEasy;
use Modules\AntOA\Http\Utils\Model\TreeNode;

class GridCreateForm implements JsonSerializable {
    /*
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
    */
    /**
     * @var DBCreateOperator DBCreateOperator对象
     */
    private $_table;
    /**
     * @var string 被用作跳转到编辑页及调用删除功能时传入的主键列名
     */
    public $primaryKey = "id";
    /**
     * @var CreateColumnCollection
     * 编辑页的所有表单项
     */
    private $createColumnCollection;
    /**
     * @var CreateRowButtonBaseCollection
     * 编辑页的每行自定义API按钮
     */
    private $createRowButtonBaseCollection;
    /**
     * @var CreateOrEditColumnChangeHookCollection
     * 数据变更时的钩子
     */
    private $createOrEditColumnChangeHookCollection;

    /**
     * 构造方法
     * @param DBCreateOperator $table 表接口
     */
    public function __construct($table) {
        $this->_table = $table;
        $this->createColumnCollection = new CreateColumnCollection();
        $this->createRowButtonBaseCollection = new CreateRowButtonBaseCollection();
        $this->createOrEditColumnChangeHookCollection = new CreateOrEditColumnChangeHookCollection();
    }

    /**
     * 获取数据库操作对象
     * @return DBCreateOperator 数据库操作DB的Builder对象
     */
    public function getDBObject() {
        return $this->_table;
    }

    /**
     * 获取内容变更钩子列表
     * @return array<CreateOrEditColumnChangeHook> 返回行数据变更时的钩子
     */
    public function getChangeHookList() {
        return $this->createOrEditColumnChangeHookCollection->getItems();
    }

    /**
     * 设置列表项
     * @param string $primaryKey 被用作跳转到编辑页及调用删除功能时传入的主键列名
     * @return GridCreateForm
     */
    public function setPrimaryKey($primaryKey) {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * 序列化对象
     * @return array 序列化后的JSON
     */
    public function jsonSerialize() {
        return [
            "primaryKey"                             => $this->primaryKey,
            "createColumnCollection"                   => $this->createColumnCollection,
            "createRowButtonBaseCollection"            => $this->createRowButtonBaseCollection,
            "createOrEditColumnChangeHookCollection" => $this->createOrEditColumnChangeHookCollection
        ];
    }
    /**
     * 获取所有列对象
     * @return array<CreateColumnBase>
     */
    public function getCreateColumnList() {
        return $this->createColumnCollection->getItems();
    }

    /**
     * 指定一列
     * @param CreateColumnBase $columnItem 编辑页的行对象
     * @return GridCreateForm 返回this以便链式调用
     */
    public function column(CreateColumnBase $columnItem) {
        $this->createColumnCollection->addItem($columnItem);
        return $this;
    }

    /**
     * 指定一个文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnText($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnText($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个进行预除运算的数字字段，提交时会乘回来
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param Number $divide 除数
     * @param string $unit 单位，默认为空
     * @param String $defaultVal 默认值（没除的数）
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnDivideNumber($col, $colTip, $divide, $unit, $defaultVal));
        return $this;
    }

    /**
     * 指定一个Textarea
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTextarea($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnTextarea($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个密码输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPassword($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnPassword($col, $tip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个下拉单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 单选的选项
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnSelect($col, $colTip, array $options, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnEnum($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个Radio单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 单选的选项
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnRadio($col, $colTip, array $options, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnEnumRadio($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 多选的选项
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnCheckbox($col, $colTip, array $options, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnEnumCheckBox($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个树形多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<TreeNode> $options 详见https://www.antdv.com/components/tree-select-cn
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnEnumTreeCheckBox($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个时间选择输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnTimestamp($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnTimestamp($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个时富文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnRichText($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnRichText($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个图片选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPicture($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnPicture($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnFile($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnFile($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个图片多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnPictures($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnPictures($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnFiles($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnFiles($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个级联选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<CascaderNode> $options 详见https://www.antdv.com/components/cascader-cn
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnCascader($col, $colTip, array $options, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnCascader($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个展示框，不会被提交
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 没有对应的查询值时的默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnDisplay($col, $colTip, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnDisplay($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个隐藏的行，会被提交，可以用来接其它页面传来的参数
     * @param String $col 数据库列名
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnHidden($col) {
        $this->createColumnCollection->addItem(new CreateColumnHidden($col));
        return $this;
    }

    /**
     * 指定一个子表选择行，子表的第一个设置的列会被作为最终选择的值
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param GridListEasy $gridListEasy 用于选择的GridList实例
     * @param String $gridListVModelCol 选中列表项后需要作为表单值的列表列（如列表为select uid,username from user，那么此值为uid时将会将对应选中行的uid值作为对应表单值传给后端）
     * @param String $gridListDisplayCol 选中列表项后展示在表单中的值对应的列表列（如列表为select uid,username from user，那么此值为username时将会将对应选中行的username值展示在表单上）
     * @param String $defaultVal 默认值
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '') {
        $this->createColumnCollection->addItem(new CreateColumnChildrenChoose($col, $colTip, $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal));
        return $this;
    }

    /**
     * 指定一列后方请求数据的按钮
     * @param CreateRowButtonBase $buttonItem 需要添加的行按钮对象
     * @return GridCreateForm 返回this以便链式调用
     */
    public function columnApiButton(CreateRowButtonBase $buttonItem) {
        $this->createRowButtonBaseCollection->addItem($buttonItem);
        return $this;
    }

    /**
     * 添加内容变更钩子
     * @param CreateOrEditColumnChangeHook $hook 行数据变更时的钩子
     * @return GridCreateForm 返回this以便链式调用
     */
    public function setChangeHook(CreateOrEditColumnChangeHook $hook) {
        $this->createOrEditColumnChangeHookCollection->addItem($hook);
        return $this;
    }

    /**
     * 创建一个每行页面跳转按钮
     * @param CreateRowButtonNavigate $createRowButtonItem 按钮项
     * @return GridCreateForm 返回this以便链式调用
     */
    public function rowNavigateButton(CreateRowButtonNavigate $createRowButtonItem) {
        $this->createRowButtonBaseCollection->addItem($createRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行API调用按钮
     * @param CreateRowButtonApi $createRowButtonItem 按钮项
     * @return GridCreateForm 返回this以便链式调用
     */
    public function rowApiButton(CreateRowButtonApi $createRowButtonItem) {
        $this->createRowButtonBaseCollection->addItem($createRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行文件BLOB下载调用按钮
     * @param CreateRowButtonBlob $createRowButtonItem 按钮项
     * @return GridCreateForm 返回this以便链式调用
     */
    public function rowBlobButton(CreateRowButtonBlob $createRowButtonItem) {
        $this->createRowButtonBaseCollection->addItem($createRowButtonItem);
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的每行API调用按钮
     * @param CreateRowButtonApiWithConfirm $createRowButtonItem 按钮项
     * @return GridCreateForm 返回this以便链式调用
     */
    public function rowApiButtonWithConfirm(CreateRowButtonApiWithConfirm $createRowButtonItem) {
        $this->createRowButtonBaseCollection->addItem($createRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行弹窗展示富文本的模态框的按钮
     * @param CreateRowButtonRichText $createRowButtonItem 按钮项
     * @return GridCreateForm 返回this以便链式调用
     */
    public function rowRichTextButton(CreateRowButtonRichText $createRowButtonItem) {
        $this->createRowButtonBaseCollection->addItem($createRowButtonItem);
        return $this;
    }
}
