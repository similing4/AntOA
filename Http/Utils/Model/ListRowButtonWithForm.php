<?php
/**
 * FileName:ListRowButtonWithForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/6/1/001
 * Time:11:54
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Illuminate\Http\Request;
use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonWithForm
 * 描述: 列表页每行调用API按钮实体类（要求确定后才会请求）
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功展示data字段，否则客户端展示msg字段
 */
abstract class ListRowButtonWithForm extends ListRowButtonBase {
    public $gridCreateForm;

    /**
     * ListRowButtonWithForm constructor.
     * @param $baseUrl
     * @param $buttonText
     * @param GridCreateFormEasy $gridCreateForm
     * @param string $buttonType
     */
    public function __construct($baseUrl, $buttonText, $gridCreateForm, $buttonType = "primary") {
        parent::__construct($baseUrl, $buttonText, $buttonType);
        $this->gridCreateForm = $gridCreateForm;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type"           => "ListRowButtonWithForm",
            "gridCreateForm" => $this->gridCreateForm
        ]);
    }

    /**
     * 是否需要使用ApiDetailColumnList接口（接口中type为edit时才会调用）
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiDetailColumnList() {
        return true;
    }

    /**
     * @param Request $request 请求数据
     * @param string $uid 登录用户UID
     * @return string 返回给前端的json数据
     */
    public function dealApiDetailColumnList(Request $request, $uid) {
        $req = json_decode($request->getContent(), true);
        $column = $request->get("col");
        $vModelVal = $request->get("val");
        foreach ($this->gridCreateForm->getCreateColumnList() as $columnItem) {
            if ($columnItem->isColumnNeedDealApiDetailColumnList() && $column == $columnItem->col) {
                $pageParams = [];
                foreach ($req as $k => $v)
                    $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
                $urlParamCalculator = new UrlParamCalculator($pageParams);
                $gridList = $columnItem->gridListEasy;
                $gridListDbObject = $gridList->getDBObject()->doClone();
                foreach ($gridList->getFilterList() as $r)
                    $r->onFilter($gridListDbObject, $urlParamCalculator, $uid);
                $vModelValTip = $gridListDbObject->doClone()->where($columnItem->gridListVModelCol, $vModelVal)->first();
                if ($vModelValTip == null)
                    $vModelValTip = "";
                else
                    $vModelValTip = json_decode(json_encode($vModelValTip), true)[$columnItem->gridListDisplayCol];
                $columns = [];
                foreach ($gridList->getTableColumnList() as $column) { // ListTableColumnBase
                    if (($column instanceof ListTableColumnDisplay) || ($column instanceof ListTableColumnRichDisplay))
                        continue;
                    $columns[] = $column->col;
                }
                $res = $gridListDbObject->select($columns)->paginate(8);
                $res = json_decode(json_encode($res), true);
                foreach ($res['data'] as &$searchResultItem) {
                    $searchResultItem['BUTTON_CONDITION_DATA'] = [];
                    $searchResultItem['BUTTON_FINAL_URL_DATA'] = [];
                    $searchResultParams = [];
                    foreach ($gridList->getTableColumnList() as $column) {
                        if ($column instanceof ListTableColumnDisplay || $column instanceof ListTableColumnRichDisplay)
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
                $hook = $columnItem->getHook();
                if ($hook != null)
                    return json_encode($hook->hook($res));
                return json_encode($res);
            }
        }
        return json_encode([
            "status" => 0,
            "msg"    => "未知错误"
        ]);
    }

    /**
     * 是否需要使用ApiUpload接口
     * @return bool 是否需要
     */
    public function isColumnNeedApiUpload() {
        foreach ($this->gridCreateForm->getCreateColumnList() as $column) {
            if ($column->isColumnNeedDealApiUpload())
                return true;
        }
        return false;
    }
}
