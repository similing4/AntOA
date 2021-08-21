<?php
/**
 * FileName:Grid.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/6
 * Time:11:10
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;

use Modules\AntOA\Http\Utils\hook\CreateHook;
use Modules\AntOA\Http\Utils\hook\DetailHook;
use Modules\AntOA\Http\Utils\hook\ListHook;
use Modules\AntOA\Http\Utils\hook\SaveHook;

/**
 * NameSpace: Modules\AntOA\Http\Utils
 * ClassName: Grid
 * 描述: AntOA的CURD配置核心类，用于向用户开放CURD基础配置
 */
class Grid {
    /**
     * @var GridList
     * GridList对象，用于列表页渲染
     */
    private $_list = null;
    /**
     * @var GridCreateForm
     * GridCreateForm对象，用于创建页渲染
     */
    private $_create_form = null;
    /**
     * @var GridEditForm
     * GridEditForm对象，用于编辑页渲染
     */
    private $_edit_form = null;
    /**
     * @var ListHook
     * ListHook对象，列表页钩子
     */
    private $_deal_list = null;
    /**
     * @var CreateHook
     * CreateHook对象，创建页钩子
     */
    private $_deal_create = null;
    /**
     * @var DetailHook
     * DetailHook对象，编辑页详情钩子
     */
    private $_deal_detail = null;
    /**
     * @var SaveHook
     * SaveHook对象，编辑页保存钩子
     */
    private $_deal_save = null;

    /**
     * 初始化GridList对象用于列表页及其接口渲染
     * @param String $table 表接口
     * @return GridList 返回GridList对象
     */
    public function list($table) {
        $this->_list = new GridList($table);
        return $this->_list;
    }

    /**
     * 初始化GridCreateForm对象用于创建页及其接口渲染
     * @param String $table 表接口
     * @return GridCreateForm 返回GridCreateForm对象
     */
    public function createForm($table) {
        $this->_create_form = new GridCreateForm($table);
        return $this->_create_form;
    }

    /**
     * 初始化GridEditForm对象用于编辑页及其接口渲染
     * @param String $table 表接口
     * @return GridEditForm 返回GridEditForm对象
     */
    public function editForm($table) {
        $this->_edit_form = new GridEditForm($table);
        return $this->_edit_form;
    }

    /**
     * 获取GridList对象
     * @return GridList 返回GridList对象
     */
    public function getGridList() {
        return $this->_list;
    }

    /**
     * 获取GridList对象
     * @return GridCreateForm 返回GridCreateForm对象
     */
    public function getCreateForm() {
        return $this->_create_form;
    }

    /**
     * 获取GridList对象
     * @return GridEditForm 返回GridEditForm对象
     */
    public function getEditForm() {
        return $this->_edit_form;
    }

    /**
     * 获取ListHook对象
     * @return ListHook
     */
    public function getListHook() {
        return $this->_deal_list;
    }

    /**
     * 获取创建接口钩子
     * @return CreateHook
     */
    public function getCreateHook() {
        return $this->_deal_create;
    }

    /**
     * 获取详情接口钩子
     * @return DetailHook
     */
    public function getDetailHook() {
        return $this->_deal_detail;
    }

    /**
     * 获取保存接口钩子
     * @return SaveHook
     */
    public function getSaveHook() {
        return $this->_deal_save;
    }

    /**
     * 列表接口后置钩子
     * @param ListHook $func 列表接口后置钩子
     * @return Grid 返回this
     */
    public function hookList($func) {
        $this->_deal_list = $func;
        return $this;
    }

    /**
     * 创建接口前置钩子
     * @param CreateHook $func 钩子函数
     * @return Grid 返回this
     */
    public function hookCreate($func) {
        $this->_deal_create = $func;
        return $this;
    }

    /**
     * 详情接口后置钩子
     * @param DetailHook $func 钩子函数
     * @return Grid 返回this
     */
    public function hookDetail($func) {
        $this->_deal_detail = $func;
        return $this;
    }

    /**
     * 保存接口前置钩子
     * @param SaveHook $func 钩子函数
     * @return Grid 返回this
     */
    public function hookSave($func) {
        $this->_deal_save = $func;
        return $this;
    }
}
