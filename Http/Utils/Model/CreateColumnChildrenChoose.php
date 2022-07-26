<?php
/**
 * FileName:CreateColumnChildrenChoose.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:15:23
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Illuminate\Http\Request;
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;
use Modules\AntOA\Http\Utils\hook\ListHook;

class CreateColumnChildrenChoose extends CreateColumnBase {
    /**
     * @var GridListEasy 用于选择的GridList实例
     */
    public $gridListEasy;
    /**
     * @var String 选中列表项后需要作为表单值的列表列（如列表为select uid,username from user，那么此值为uid时将会将对应选中行的uid值作为对应表单值传给后端）
     */
    public $gridListVModelCol;
    /**
     * @var String 选中列表项后展示在表单中的值对应的列表列（如列表为select uid,username from user，那么此值为username时将会将对应选中行的username值展示在表单上）
     */
    public $gridListDisplayCol;
    /**
     * @var ListHook 查询结果钩子，需要手动设置
     */
    public $hook;

    /**
     * CreateColumnChildrenChoose constructor.
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param GridListEasy $gridListEasy 用于选择的GridList实例
     * @param String $gridListVModelCol 选中列表项后需要作为表单值的列表列（如列表为select uid,username from user，那么此值为uid时将会将对应选中行的uid值作为对应表单值传给后端）
     * @param String $gridListDisplayCol 选中列表项后展示在表单中的值对应的列表列（如列表为select uid,username from user，那么此值为username时将会将对应选中行的username值展示在表单上）
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, GridListEasy $gridListEasy, $gridListVModelCol, $gridListDisplayCol, $defaultVal = '') {
        parent::__construct($col, $tip, $defaultVal);
        $this->gridListEasy = $gridListEasy;
        $this->gridListVModelCol = $gridListVModelCol;
        $this->gridListDisplayCol = $gridListDisplayCol;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type"               => "CreateColumnChildrenChoose",
            "gridListEasy"       => $this->gridListEasy,
            "gridListVModelCol"  => $this->gridListVModelCol,
            "gridListDisplayCol" => $this->gridListDisplayCol
        ]);
    }

    /**
     * @param ListHook $hook 设置查询结果钩子
     */
    public function setHook(ListHook $hook) {
        $this->hook = $hook;
    }

    /**
     * @return ListHook $hook 获取查询结果钩子
     */
    public function getHook() {
        return $this->hook;
    }

    /**
     * 是否需要使用ApiDetailColumnList接口
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiDetailColumnList(){
        return true;
    }

    /**
     * @param Request $request 请求数据
     * @param string $uid 登录用户UID
     * @return string 返回给前端的json数据
     */
    public function dealApiDetailColumnList(Request $request, $uid){
        $vModelVal = $request->get("val");
        $req = json_decode($request->getContent(), true);
        $pageParams = [];
        foreach ($req as $k => $v)
            $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
        $urlParamCalculator = new UrlParamCalculator($pageParams);
        $gridList = $this->gridListEasy;
        $gridListDbObject = $gridList->getDBObject()->doClone();
        foreach ($gridList->getFilterList() as $r)
            $r->onFilter($gridListDbObject, $urlParamCalculator, $uid);
        $vModelValTip = $gridListDbObject->doClone()->where($this->gridListVModelCol, $vModelVal)->first();
        if ($vModelValTip == null)
            $vModelValTip = "";
        else
            $vModelValTip = json_decode(json_encode($vModelValTip), true)[$this->gridListDisplayCol];
        $columns = [];
        foreach ($gridList->getTableColumnList() as $column)
            if (!$column->isTypeDisplay())
                $columns[] = $column->col;
        $res = $gridListDbObject->select($columns)->paginate(8);
        $res = json_decode(json_encode($res), true);
        foreach ($res['data'] as &$searchResultItem) {
            $searchResultItem['BUTTON_CONDITION_DATA'] = [];
            $searchResultItem['BUTTON_FINAL_URL_DATA'] = [];
            $searchResultParams = [];
            foreach ($gridList->getTableColumnList() as $column) {
                if ($column->isTypeDisplay())
                    $searchResultItem[$column->col] = '';
                else
                    $column->onParse($searchResultItem, $urlParamCalculator, $uid);
                $searchResultParams[] = new UrlParamCalculatorParamItem($column->col, $searchResultItem[$column->col]);
            }
            $rowParamCalculator = new UrlParamCalculator($pageParams, $searchResultParams);
            foreach ($gridList->getRowButtonList() as $rowButtonItem) {
                $searchResultItem['BUTTON_FINAL_URL_DATA'][] = $rowButtonItem->calcButtonFinalUrl($rowParamCalculator);
                $searchResultItem['BUTTON_CONDITION_DATA'][] = $rowButtonItem->judgeIsShow($rowParamCalculator);
            }
        }
        $res['status'] = 1;
        $res['vModelValTip'] = $vModelValTip;
        $hook = $this->getHook();
        if ($hook != null)
            return json_encode($hook->hook($res));
        return json_encode($res);
    }
}
