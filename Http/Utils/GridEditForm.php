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
use Modules\AntOA\Http\Utils\AbstractModel\CreateOrEditColumnChangeHookCollection;
use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;
use Modules\AntOA\Http\Utils\AbstractModel\EditColumnCollection;
use Modules\AntOA\Http\Utils\AbstractModel\EditRowButtonBase;
use Modules\AntOA\Http\Utils\AbstractModel\EditRowButtonBaseCollection;
use Modules\AntOA\Http\Utils\hook\CreateOrEditColumnChangeHook;
use Modules\AntOA\Http\Utils\Model\CascaderNode;
use Modules\AntOA\Http\Utils\Model\EditColumnCascader;
use Modules\AntOA\Http\Utils\Model\EditColumnChildrenChoose;
use Modules\AntOA\Http\Utils\Model\EditColumnDisplay;
use Modules\AntOA\Http\Utils\Model\EditColumnDivideNumber;
use Modules\AntOA\Http\Utils\Model\EditColumnEnum;
use Modules\AntOA\Http\Utils\Model\EditColumnEnumCheckBox;
use Modules\AntOA\Http\Utils\Model\EditColumnEnumRadio;
use Modules\AntOA\Http\Utils\Model\EditColumnEnumTreeCheckBox;
use Modules\AntOA\Http\Utils\Model\EditColumnFile;
use Modules\AntOA\Http\Utils\Model\EditColumnFileLocal;
use Modules\AntOA\Http\Utils\Model\EditColumnFiles;
use Modules\AntOA\Http\Utils\Model\EditColumnFilesLocal;
use Modules\AntOA\Http\Utils\Model\EditColumnHidden;
use Modules\AntOA\Http\Utils\Model\EditColumnPassword;
use Modules\AntOA\Http\Utils\Model\EditColumnPicture;
use Modules\AntOA\Http\Utils\Model\EditColumnPictureLocal;
use Modules\AntOA\Http\Utils\Model\EditColumnPictures;
use Modules\AntOA\Http\Utils\Model\EditColumnPicturesLocal;
use Modules\AntOA\Http\Utils\Model\EditColumnRichText;
use Modules\AntOA\Http\Utils\Model\EditColumnText;
use Modules\AntOA\Http\Utils\Model\EditColumnTextarea;
use Modules\AntOA\Http\Utils\Model\EditColumnTimestamp;
use Modules\AntOA\Http\Utils\Model\EditRowButtonApi;
use Modules\AntOA\Http\Utils\Model\EditRowButtonApiWithConfirm;
use Modules\AntOA\Http\Utils\Model\EditRowButtonBlob;
use Modules\AntOA\Http\Utils\Model\EditRowButtonNavigate;
use Modules\AntOA\Http\Utils\Model\EditRowButtonRichText;
use Modules\AntOA\Http\Utils\Model\EnumOption;
use Modules\AntOA\Http\Utils\Model\GridListEasy;
use Modules\AntOA\Http\Utils\Model\TreeNode;

class GridEditForm implements JsonSerializable {
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
     * @var DBEditOperator DBEditOperator对象
     */
    private $_table;
    /**
     * @var string 被用作跳转到编辑页及调用删除功能时传入的主键列名
     */
    public $primaryKey = "id";
    /**
     * @var EditColumnCollection
     * 编辑页的所有表单项
     */
    private $editColumnCollection;
    /**
     * @var EditRowButtonBaseCollection
     * 编辑页的每行自定义API按钮
     */
    private $editRowButtonBaseCollection;
    /**
     * @var CreateOrEditColumnChangeHookCollection
     * 数据变更时的钩子
     */
    private $createOrEditColumnChangeHookCollection;

    /**
     * 构造方法
     * @param DBEditOperator $table 表接口
     */
    public function __construct($table) {
        $this->_table = $table;
        $this->editColumnCollection = new EditColumnCollection();
        $this->editRowButtonBaseCollection = new EditRowButtonBaseCollection();
        $this->createOrEditColumnChangeHookCollection = new CreateOrEditColumnChangeHookCollection();
    }

    /**
     * 获取数据库操作对象
     * @return DBEditOperator 数据库操作DB的Builder对象
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
     * @return GridEditForm
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
            "editColumnCollection"                   => $this->editColumnCollection,
            "editRowButtonBaseCollection"            => $this->editRowButtonBaseCollection,
            "createOrEditColumnChangeHookCollection" => $this->createOrEditColumnChangeHookCollection
        ];
    }
    /**
     * 获取所有列对象
     * @return array<EditColumnBase>
     */
    public function getEditColumnList() {
        return $this->editColumnCollection->getItems();
    }

    /**
     * 指定一列
     * @param EditColumnBase $columnItem 编辑页的行对象
     * @return GridEditForm 返回this以便链式调用
     */
    public function column(EditColumnBase $columnItem) {
        $this->editColumnCollection->addItem($columnItem);
        return $this;
    }

    /**
     * 指定一个文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnText($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnText($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个进行预除运算的数字字段，提交时会乘回来
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param Number $divide 除数
     * @param string $unit 单位，默认为空
     * @param String $defaultVal 默认值（没除的数）
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnNumberDivide($col, $colTip, $divide, $unit = '', $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnDivideNumber($col, $colTip, $divide, $unit, $defaultVal));
        return $this;
    }

    /**
     * 指定一个Textarea
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnTextarea($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnTextarea($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个密码输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnPassword($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnPassword($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个下拉单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 单选的选项
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnSelect($col, $colTip, array $options, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnEnum($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个Radio单选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 单选的选项
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnRadio($col, $colTip, array $options, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnEnumRadio($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<EnumOption> $options 多选的选项
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnCheckbox($col, $colTip, array $options, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnEnumCheckBox($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个树形多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<TreeNode> $options 详见https://www.antdv.com/components/tree-select-cn
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnTreeCheckbox($col, $colTip, array $options, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnEnumTreeCheckBox($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个时间选择输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnTimestamp($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnTimestamp($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个时富文本输入框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnRichText($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnRichText($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个图片选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnPicture($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnPicture($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnFile($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnFile($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个图片多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnPictures($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnPictures($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件多选框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnFiles($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnFiles($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个级联选择框
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param array<CascaderNode> $options 详见https://www.antdv.com/components/cascader-cn
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnCascader($col, $colTip, array $options, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnCascader($col, $colTip, $options, $defaultVal));
        return $this;
    }

    /**
     * 指定一个展示框，不会被提交
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 没有对应的查询值时的默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnDisplay($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnDisplay($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个隐藏的行，会被提交，可以用来接其它页面传来的参数
     * @param String $col 数据库列名
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnHidden($col) {
        $this->editColumnCollection->addItem(new EditColumnHidden($col));
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
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnChildrenChoose($col, $colTip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnChildrenChoose($col, $colTip, $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal));
        return $this;
    }

    /**
     * 指定一列后方请求数据的按钮
     * @param EditRowButtonBase $buttonItem 需要添加的行按钮对象
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowButton(EditRowButtonBase $buttonItem) {
        $this->editRowButtonBaseCollection->addItem($buttonItem);
        return $this;
    }

    /**
     * 添加内容变更钩子
     * @param CreateOrEditColumnChangeHook $hook 行数据变更时的钩子
     * @return GridEditForm 返回this以便链式调用
     */
    public function setChangeHook(CreateOrEditColumnChangeHook $hook) {
        $this->createOrEditColumnChangeHookCollection->addItem($hook);
        return $this;
    }

    /**
     * 创建一个每行页面跳转按钮
     * @param EditRowButtonNavigate $editRowButtonItem 按钮项
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowNavigateButton(EditRowButtonNavigate $editRowButtonItem) {
        $this->editRowButtonBaseCollection->addItem($editRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行API调用按钮
     * @param EditRowButtonApi $editRowButtonItem 按钮项
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowApiButton(EditRowButtonApi $editRowButtonItem) {
        $this->editRowButtonBaseCollection->addItem($editRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行文件BLOB下载调用按钮
     * @param EditRowButtonBlob $editRowButtonItem 按钮项
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowBlobButton(EditRowButtonBlob $editRowButtonItem) {
        $this->editRowButtonBaseCollection->addItem($editRowButtonItem);
        return $this;
    }

    /**
     * 创建一个需要弹窗确认的每行API调用按钮
     * @param EditRowButtonApiWithConfirm $editRowButtonItem 按钮项
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowApiButtonWithConfirm(EditRowButtonApiWithConfirm $editRowButtonItem) {
        $this->editRowButtonBaseCollection->addItem($editRowButtonItem);
        return $this;
    }

    /**
     * 创建一个每行弹窗展示富文本的模态框的按钮
     * @param EditRowButtonRichText $editRowButtonItem 按钮项
     * @return GridEditForm 返回this以便链式调用
     */
    public function rowRichTextButton(EditRowButtonRichText $editRowButtonItem) {
        $this->editRowButtonBaseCollection->addItem($editRowButtonItem);
        return $this;
    }

    /**
     * 指定一个图片选择框并上传到服务器本地public/antoa_uploads/下
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnPictureLocal($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnPictureLocal($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件选择框并上传到服务器本地public/antoa_uploads/下
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnFileLocal($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnFileLocal($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个图片多选框并上传到服务器本地public/antoa_uploads/下
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnPicturesLocal($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnPicturesLocal($col, $colTip, $defaultVal));
        return $this;
    }

    /**
     * 指定一个文件多选框并上传到服务器本地public/antoa_uploads/下
     * @param String $col 数据库列名
     * @param String $colTip 在列表页该列的的表头名称
     * @param String $defaultVal 默认值
     * @return GridEditForm 返回this以便链式调用
     */
    public function columnFilesLocal($col, $colTip, $defaultVal = '') {
        $this->editColumnCollection->addItem(new EditColumnFilesLocal($col, $colTip, $defaultVal));
        return $this;
    }
}
