<?php

namespace Modules\AntOA\Http\Controllers;

use App\Traits\ReturnMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\AntOA\Http\Utils\AuthInterface;
use Modules\AntOA\Http\Utils\Grid;
use Modules\AntOA\Http\Utils\Model\CreateColumnChildrenChoose;
use Modules\AntOA\Http\Utils\Model\CreateColumnFile;
use Modules\AntOA\Http\Utils\Model\CreateColumnFiles;
use Modules\AntOA\Http\Utils\Model\CreateColumnPicture;
use Modules\AntOA\Http\Utils\Model\CreateColumnPictures;
use Modules\AntOA\Http\Utils\Model\EditColumnChildrenChoose;
use Modules\AntOA\Http\Utils\Model\EditColumnFile;
use Modules\AntOA\Http\Utils\Model\EditColumnFiles;
use Modules\AntOA\Http\Utils\Model\EditColumnPicture;
use Modules\AntOA\Http\Utils\Model\EditColumnPictures;
use Modules\AntOA\Http\Utils\Model\ListFilterCascader;
use Modules\AntOA\Http\Utils\Model\ListFilterEndTime;
use Modules\AntOA\Http\Utils\Model\ListFilterEnum;
use Modules\AntOA\Http\Utils\Model\ListFilterHidden;
use Modules\AntOA\Http\Utils\Model\ListFilterMultiSelect;
use Modules\AntOA\Http\Utils\Model\ListFilterStartTime;
use Modules\AntOA\Http\Utils\Model\ListFilterText;
use Modules\AntOA\Http\Utils\Model\ListFilterUID;
use Modules\AntOA\Http\Utils\Model\ListTableColumnDisplay;
use Modules\AntOA\Http\Utils\Model\ListTableColumnRichDisplay;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculatorParamItem;


/**
 * NameSpace: Modules\AntOA\Http\Controllers
 * ClassName: AntOAController
 * 描述: AntOA模块核心基类，所有需要使用后台功能的控制器均要继承该类并实现抽象方法grid与statistic。
 */
abstract class AntOAController extends Controller {
    protected $gridObj = null; //grid对象
    protected $auth = null; //Auth对象，如果你需要自己实现授权Token，你可以在Service文件夹下实现AuthInterface接口并在AntOAServiceProvider中修改接口实现类绑定。

    public function __construct(AuthInterface $auth) {
        $this->auth = $auth;
        $this->gridObj = new Grid();
        try {
            $this->getUserInfo(request());
        } catch (Exception $e) {
            die(json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]));
        }
        $this->grid($this->gridObj);
    }

    /**
     * 初始化Grid对象
     * @param Grid $grid
     */
    public abstract function grid(Grid $grid);

    /**
     * 处理统计数据
     * @param Request $req 客户端请求参数
     * @return string 统计结果
     */
    public abstract function statistic(Request $req);

    /**
     * 根据TOKEN获取UID，失败时抛出异常，只对API有效！
     * @param Request $request
     * @return string UID
     * @throws Exception token不合法时登录失效
     */
    protected function getUserInfo(Request $request) {
        $token = $request->header('Authorization');
        if ($token == null)
            throw new Exception("登录失效");
        $uid = $this->auth->getUidFromToken($token);
        if (!$uid)
            throw new Exception("登录失效");
        $user = DB::table("antoa_user")->find($uid);
        if ($user->status == 0)
            throw new Exception("账户已被封禁");
        if (!$this->checkPower($uid))
            throw new Exception("权限不足");
        return $uid;
    }

    /**
     * 获取页面所需的必要参数
     * @param Request $request
     * @return array 参数数组
     */
    protected function getCustomParam(Request $request) {
        $path = $request->path();
        $apiPos = strpos($path, "api/");
        if ($apiPos !== false)
            $path = substr($path, $apiPos + 4);
        $path = substr($path, 0, strrpos($path, '/'));
        return [
            "grid" => $this->gridObj,
            "api"  => [
                "path"               => $request->path(),
                "list"               => "/api/" . $path . "/list",
                "create"             => "/api/" . $path . "/create",
                "detail"             => "/api/" . $path . "/detail",
                "save"               => "/api/" . $path . "/save",
                "delete"             => "/api/" . $path . "/delete",
                "detail_column_list" => "/api/" . $path . "/detail_column_list",
                "api_column_change"  => "/api/" . $path . "/column_change",
                "list_page"          => "/" . $path . "/list",
                "create_page"        => "/" . $path . "/create",
                "edit_page"          => "/" . $path . "/edit"
            ]
        ];
    }

    /**
     * 列表数据API
     * @param Request $request
     * @return String 列表页数据JSON
     */
    public function api_list(Request $request) {
        try {
            if ($this->gridObj->getGridList() == null)
                throw new Exception("页面配置信息不存在");
            $uid = null;
            try {
                $uid = $this->getUserInfo($request);
            } catch (Exception $e) {
                return json_encode([
                    "status" => 0,
                    "msg"    => "登录失效"
                ]);
            }
            $gridListDbObject = $this->gridObj->getGridList()->getDBObject();
            $gridList = $this->gridObj->getGridList();
            $req = json_decode($request->getContent(), true);
            $pageParams = [];
            foreach ($req as $k => $v)
                $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
            $urlParamCalculator = new UrlParamCalculator($pageParams);
            foreach ($gridList->getFilterList() as $r) { //ListFilterBase
                $param = $urlParamCalculator->getPageParamByKey($r->col);
                if ($r instanceof ListFilterText) {
                    if ($param !== null && $param->val != '')
                        $gridListDbObject->where($r->col, 'like', "%" . $param->val . "%");
                } else if ($r instanceof ListFilterHidden || $r instanceof ListFilterEnum) {
                    if ($param !== null && $param->val != '')
                        $gridListDbObject->where($r->col, $param->val);
                } else if ($r instanceof ListFilterStartTime) {
                    $param = $urlParamCalculator->getPageParamByKey($r->col . "_starttime");
                    if ($param !== null && $param->val != '')
                        $gridListDbObject->where($r->col, ">", $param->val);
                } else if ($r instanceof ListFilterEndTime) {
                    $param = $urlParamCalculator->getPageParamByKey($r->col . "_endtime");
                    if ($param !== null && $param->val != '')
                        $gridListDbObject->where($r->col, "<", $param->val);
                } else if ($r instanceof ListFilterUID) {
                    $gridListDbObject->where($r->col, $uid);
                } else if ($r instanceof ListFilterMultiSelect) {
                    if ($param !== null && $param->val != '') {
                        $array = json_decode($param->val, true);
                        $gridListDbObject->whereIn($r->col, $array);
                    }
                } else if ($r instanceof ListFilterCascader) {
                    if ($param !== null && $param->val != '') {
                        $array = json_decode($param->val, true);
                        $gridListDbObject->where($r->col, join($array, " "));
                    }
                }
            }
            $columns = [];
            foreach ($gridList->getTableColumnList() as $column) { // ListTableColumnBase
                if (($column instanceof ListTableColumnDisplay) || ($column instanceof ListTableColumnRichDisplay))
                    continue;
                $columns[] = $column->col;
            }
            $res = $gridListDbObject->select($columns)->paginate(15);
            $res = json_decode(json_encode($res), true);
            foreach ($res['data'] as &$searchResultItem) {
                $searchResultItem['BUTTON_CONDITION_DATA'] = [];
                $searchResultItem['BUTTON_FINAL_URL_DATA'] = [];
                $searchResultParams = [];
                foreach ($gridList->getTableColumnList() as $column) {
                    if ($column instanceof ListTableColumnDisplay || $column instanceof ListTableColumnRichDisplay)
                        $searchResultItem[$column->col] = '';
                    $searchResultParams[] = new UrlParamCalculatorParamItem($column->col, $searchResultItem[$column->col]);
                }
                $rowParamCalculator = new UrlParamCalculator($pageParams, $searchResultParams);
                foreach ($gridList->getRowButtonList() as $rowButtonItem) {
                    $searchResultItem['BUTTON_FINAL_URL_DATA'][] = $rowButtonItem->calcButtonFinalUrl($rowParamCalculator);
                    $searchResultItem['BUTTON_CONDITION_DATA'][] = $rowButtonItem->judgeIsShow($rowParamCalculator);
                }
            }
            $res['status'] = 1;
            $res['statistic'] = $this->statistic($request);
            $hook = $this->gridObj->getListHook();
            if ($hook != null)
                return json_encode($hook->hook($res));
            return json_encode($res);
        } catch (Exception $e2) {
            return json_encode([
                "status" => 0,
                "msg"    => $e2->getMessage() . " at row " . $e2->getLine()
            ]);
        }
    }

    /**
     * 获取页面配置信息JSON
     * @param Request $request
     * @return String 页面配置信息JSON
     */
    public function api_grid_config(Request $request) {
        try {
            $this->getUserInfo($request);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => "登录失效"
            ]);
        }
        $gridList = $this->gridObj->getGridList();
        $gridCreate = $this->gridObj->getCreateForm();
        $gridEdit = $this->gridObj->getEditForm();

        $req = json_decode($request->getContent(), true);
        $pageParams = [];
        foreach ($req as $k => $v)
            $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
        $urlParamCalculator = new UrlParamCalculator($pageParams);
        $removeList = [];
        foreach ($gridList->getHeaderButtonList() as $headerButtonItem) { //ListHeaderButtonBase
            if (!$headerButtonItem->judgeIsShow($urlParamCalculator))
                $removeList[] = $headerButtonItem;
            $headerButtonItem->finalUrl = $headerButtonItem->calcButtonFinalUrl($urlParamCalculator);
        }
        $gridList->removeHeaderButtons($removeList);
        return json_encode([
            "status" => 1,
            "grid"   => [
                "list"   => $gridList,
                "create" => $gridCreate,
                "edit"   => $gridEdit
            ],
            "api"    => $this->getCustomParam($request)['api']
        ]);
    }

    /**
     * 创建一条记录的API
     * @param Request $request
     * @return String 通用成功失败返回
     */
    public function api_create(Request $request) {
        try {
            if ($this->gridObj->getCreateForm() == null)
                throw new Exception("页面配置信息不存在");
            try {
                $this->getUserInfo($request);
            } catch (Exception $e) {
                return json_encode([
                    "status" => 0,
                    "msg"    => "登录失效"
                ]);
            }
            $req = json_decode($request->getContent(), true);
            $gridCreateForm = $this->gridObj->getCreateForm();
            $param = [];
            foreach ($gridCreateForm->getCreateColumnList() as $col) //EditColumnBase
                $param[$col->col] = $col->onGuestVal($req[$col->col]);
            $hook = $this->gridObj->getCreateHook();
            if ($hook != null)
                $param = $hook->hook($param);
            if ($param != null)
                $gridCreateForm->getDBObject()->insert($param);
            return json_encode([
                "status" => 1,
                "data"   => "创建成功"
            ]);
        } catch (Exception $e2) {
            return json_encode([
                "status" => 0,
                "msg"    => $e2->getMessage()
            ]);
        }
    }

    /**
     * 获取数据详情API
     * @param Request $request
     * @return String 通用成功失败返回
     */
    public function api_detail(Request $request) {
        try {
            if ($this->gridObj->getEditForm() == null)
                throw new Exception("页面配置信息不存在");
            try {
                $this->getUserInfo($request);
            } catch (Exception $e) {
                return json_encode([
                    "status" => 0,
                    "msg"    => "登录失效"
                ]);
            }
            $json = $request->getContent();
            $req = json_decode($json, true);
            $gridEditForm = $this->gridObj->getEditForm();
            $res = $gridEditForm->getDBObject()->find($req[$gridEditForm->primaryKey]);
            if (!$res)
                throw new Exception("该项目不存在");
            $res = json_decode(json_encode($res), true);
            foreach ($gridEditForm->getEditColumnList() as $col) //EditColumnBase
                $res[$col->col] = $col->onServerVal($res[$col->col]);
            $hook = $this->gridObj->getDetailHook();
            if ($hook != null)
                return json_encode($hook->hook([
                    "status" => 1,
                    "data"   => $res
                ]));
            return json_encode([
                "status" => 1,
                "data"   => $res
            ]);
        } catch (Exception $e2) {
            return json_encode([
                "status" => 0,
                "msg"    => $e2->getMessage()
            ]);
        }
    }

    /**
     * 保存数据API
     * @param Request $request
     * @return String 通用成功失败返回
     */
    public function api_save(Request $request) {
        try {
            if ($this->gridObj->getEditForm() == null)
                throw new Exception("页面配置信息不存在");
            try {
                $this->getUserInfo($request);
            } catch (Exception $e) {
                return json_encode([
                    "status" => 0,
                    "msg"    => "登录失效"
                ]);
            }
            $req = json_decode($request->getContent(), true);
            $gridEditForm = $this->gridObj->getEditForm();
            $param = [];
            foreach ($gridEditForm->getEditColumnList() as $col) //EditColumnBase
                $param[$col->col] = $col->onGuestVal($req[$col->col]);
            $hook = $this->gridObj->getSaveHook();
            if ($hook != null)
                $param = $hook->hook($param);
            if ($param != null)
                $gridEditForm->getDBObject()->onUpdate($gridEditForm->primaryKey, $param);
            return json_encode([
                "status" => 1,
                "data"   => "保存成功"
            ]);
        } catch (Exception $e2) {
            return json_encode([
                "status" => 0,
                "msg"    => $e2->getMessage()
            ]);
        }
    }

    /**
     * 编辑或创建页的表格选择项数据所需列表API
     * @param Request $request
     * @return String 通用成功失败返回
     */
    public function api_detail_column_list(Request $request) {
        $uid = 0;
        try {
            $uid = $this->getUserInfo($request);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => "登录失效"
            ]);
        }
        $type = $request->get("type");
        $column = $request->get("col");
        $vModelVal = $request->get("val");
        try {
            if ($this->gridObj->getEditForm() == null && $type == "edit")
                throw new Exception("页面配置信息不存在");
            if ($this->gridObj->getCreateForm() == null && $type == "create")
                throw new Exception("页面配置信息不存在");
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
        if ($type == "create") {
            foreach ($this->gridObj->getCreateForm()->getCreateColumnList() as $columnItem) {
                if ($columnItem instanceof CreateColumnChildrenChoose && $column == $columnItem->col) {
                    $req = json_decode($request->getContent(), true);
                    $pageParams = [];
                    foreach ($req as $k => $v)
                        $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
                    $urlParamCalculator = new UrlParamCalculator($pageParams);
                    $gridList = $columnItem->gridListEasy;
                    $gridListDbObject = $gridList->getDBObject()->doClone();
                    $req = json_decode($request->getContent(), true);
                    foreach ($gridList->getFilterList() as $r) {
                        $param = $urlParamCalculator->getPageParamByKey($r->col);
                        if ($r instanceof ListFilterText) {
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, 'like', "%" . $param->val . "%");
                        } else if ($r instanceof ListFilterHidden || $r instanceof ListFilterEnum) {
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, $param->val);
                        } else if ($r instanceof ListFilterStartTime) {
                            $param = $urlParamCalculator->getPageParamByKey($r->col . "_starttime");
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, ">", $param->val);
                        } else if ($r instanceof ListFilterEndTime) {
                            $param = $urlParamCalculator->getPageParamByKey($r->col . "_endtime");
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, "<", $param->val);
                        } else if ($r instanceof ListFilterUID) {
                            $gridListDbObject->where($r->col, $uid);
                        }
                    }
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
        } else if ($type == "edit") {
            foreach ($this->gridObj->getEditForm()->getEditColumnList() as $columnItem) {
                if ($columnItem instanceof EditColumnChildrenChoose && $column == $columnItem->col) {
                    $req = json_decode($request->getContent(), true);
                    $pageParams = [];
                    foreach ($req as $k => $v)
                        $pageParams[] = new UrlParamCalculatorParamItem($k, $v);
                    $urlParamCalculator = new UrlParamCalculator($pageParams);
                    $gridList = $columnItem->gridListEasy;
                    $gridListDbObject = $gridList->getDBObject()->doClone();
                    $req = json_decode($request->getContent(), true);
                    foreach ($gridList->getFilterList() as $r) {
                        $param = $urlParamCalculator->getPageParamByKey($r->col);
                        if ($r instanceof ListFilterText) {
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, 'like', "%" . $param->val . "%");
                        } else if ($r instanceof ListFilterHidden || $r instanceof ListFilterEnum) {
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, $param->val);
                        } else if ($r instanceof ListFilterStartTime) {
                            $param = $urlParamCalculator->getPageParamByKey($r->col . "_starttime");
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, ">", $param->val);
                        } else if ($r instanceof ListFilterEndTime) {
                            $param = $urlParamCalculator->getPageParamByKey($r->col . "_endtime");
                            if ($param !== null && $param->val != '')
                                $gridListDbObject->where($r->col, "<", $param->val);
                        } else if ($r instanceof ListFilterUID) {
                            $gridListDbObject->where($r->col, $uid);
                        }
                    }
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
        }
        return json_encode([
            "status" => 0,
            "msg"    => "指定字段不存在"
        ]);
    }

    /**
     * 删除数据API
     * @param Request $request
     * id：待删除的主键ID
     * @return String 通用成功失败返回
     */
    public function api_delete(Request $request) {
        try {
            if ($this->gridObj->getGridList() == null)
                throw new Exception("页面配置信息不存在");
            if (!$this->gridObj->getGridList()->hasDelete)
                throw new Exception("非法操作");
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
        try {
            $this->getUserInfo($request);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => "登录失效"
            ]);
        }
        try {
            $obj = $this->gridObj->getGridList()->getDBObject();
            $id = $request->get("id");
            if (!$id)
                throw new Exception("缺少参数ID");
            $resp = $obj->find($id);
            if (!$resp)
                throw new Exception("项目不存在");
            $hook = $this->gridObj->getDeleteHook();
            if ($hook != null)
                $id = $hook->hook($id);
            if ($id != null)
                $obj->delete($id);
            $resp = json_encode($resp);
            $resp = json_decode($resp, true);
            return json_encode([
                "status" => 1,
                "data"   => "删除成功"
            ]);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
    }

    /**
     * 创建页与编辑页响应变化的API
     * @param Request $request
     * @return String 详见CreateOrEditColumnChangeHook
     */
    public function api_column_change(Request $request) {
        try {
            $this->getUserInfo($request);
            $content = $request->getContent();
            if (!$content)
                throw new Exception("非法操作");
            $content = json_decode($content, true);
            if (!$content)
                throw new Exception("非法操作");
            $changeHookList = null;
            if (!array_key_exists("type", $content) || !array_key_exists("form", $content))
                throw new Exception("非法操作");
            $type = $content['type'];
            $col = $content['col'];
            if ($type == "create") {
                if ($this->gridObj->getCreateForm() == null)
                    throw new Exception("页面配置信息不存在");
                $changeHookList = $this->gridObj->getCreateForm()->getChangeHookList();
            } else if ($type == "edit") {
                if ($this->gridObj->getEditForm() == null)
                    throw new Exception("页面配置信息不存在");
                $changeHookList = $this->gridObj->getEditForm()->getChangeHookList();
            } else
                throw new Exception("非法操作");
            $changeHookList = array_filter($changeHookList, function ($t) use ($col) {
                return $t->col == $col;
            });
            if (empty($changeHookList))
                throw new Exception("页面配置信息不存在");
            $data = $changeHookList[0]->hook($content['form'], $content['page']);
            return json_encode([
                "status"         => 1,
                "data"           => $data->data,
                "displayColumns" => $data->display
            ]);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
    }

    public function uploadFile(Request $request) {
        try {
            $uid = $this->getUserInfo($request);
            $_type = $request->get("type");
            $_col = $request->get("col");
            if ($_type == "create") {
                $gridCreateForm = $this->gridObj->getCreateForm();
                if ($gridCreateForm == null)
                    throw new Exception("非法请求");
                $column = null;
                foreach ($gridCreateForm->getCreateColumnList() as $col)
                    if ($_col == $col->col && ($col instanceof CreateColumnFile
                            || $col instanceof CreateColumnFiles
                            || $col instanceof CreateColumnPicture
                            || $col instanceof CreateColumnPictures))
                        $column = $col;
                if ($column == null)
                    throw new Exception("非法请求");
            } else {
                $gridEditForm = $this->gridObj->getEditForm();
                if ($gridEditForm == null)
                    throw new Exception("非法请求");
                $column = null;
                foreach ($gridEditForm->getEditColumnList() as $col)
                    if ($_col == $col->col && ($col instanceof EditColumnFile
                            || $col instanceof EditColumnFiles
                            || $col instanceof EditColumnPicture
                            || $col instanceof EditColumnPictures))
                        $column = $col;
                if ($column == null)
                    throw new Exception("非法请求");
            }
            $file = $request->file('file');
            $fileExt = $file->getClientOriginalExtension();
            $destDir = base_path("public/antoa_uploads");
            $destFile = $uid . "_" . time() . md5($file->getFilename()) . $fileExt;
            if(!file_exists($destDir))
                mkdir($destDir);
            $path = $file->move($destDir, $destFile);
            return json_encode([
                "status"         => 1,
                "data"           => $destFile
            ]);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
    }

    /**
     * 根据UID对控制器下所有接口进行鉴权
     * @param String $uid 用户UID
     * @return Boolean 返回真则验权通过，否则验权失败
     */
    protected abstract function checkPower($uid);
}
