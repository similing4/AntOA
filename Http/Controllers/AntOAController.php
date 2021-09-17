<?php

namespace Modules\AntOA\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\AntOA\Http\Utils\AuthInterface;
use Modules\AntOA\Http\Utils\Grid;
use Modules\AntOA\Http\Utils\GridCreateForm;
use Modules\AntOA\Http\Utils\GridEditForm;
use Modules\AntOA\Http\Utils\GridList;

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
                "list_page"          => "/" . $path . "/list",
                "create_page"        => "/" . $path . "/create",
                "edit_page"          => "/" . $path . "/edit"
            ]
        ];
    }

    /**
     * 列表页
     * @param Request $request
     * @return Renderable 列表页页面
     * @throws Exception
     */
    public function list(Request $request) {
        if ($this->gridObj->getGridList() == null)
            throw new Exception("页面配置信息不存在");
        $data = $this->getCustomParam($request);
        return view("antoa::main.list", $data);
    }

    /**
     * 创建页
     * @param Request $request
     * @return Renderable 创建页页面
     * @throws Exception
     */
    public function create(Request $request) {
        if ($this->gridObj->getCreateForm() == null)
            throw new Exception("页面配置信息不存在");
        $data = $this->getCustomParam($request);
        return view("antoa::main.create", $data);
    }

    /**
     * 编辑页
     * @param Request $request
     * @return Renderable 编辑页页面
     * @throws Exception
     */
    public function edit(Request $request) {
        if ($this->gridObj->getEditForm() == null)
            throw new Exception("页面配置信息不存在");
        $data = $this->getCustomParam($request);
        return view("antoa::main.edit", $data);
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
            $list = $this->gridObj->getGridList()->getDBObject();
            $config = $this->gridObj->getGridList()->getArr();
            $val = $request->getContent();
            $req = json_decode($val, true);
            foreach ($config['filter_columns'] as $r) {
                switch ($r['type']) {
                    case GridList::FILTER_TEXT:
                        if ($req[$r['col']] !== '')
                            $list->where($r['col'], 'like', "%" . $request->post($r['col']) . "%");
                        break;
                    case GridList::FILTER_HIDDEN:
                    case GridList::FILTER_ENUM:
                        if ($req[$r['col']] !== '')
                            $list->where($r['col'], $req[$r['col']]);
                        break;
                    case GridList::FILTER_STARTTIME:
                        if ($req[$r['col'] . "_starttime"] !== '')
                            $list->where($r['col'], ">", $req[$r['col'] . "_starttime"]);
                        break;
                    case GridList::FILTER_ENDTIME:
                        if ($req[$r['col'] . "_endtime"] !== '')
                            $list->where($r['col'], "<", $req[$r['col'] . "_endtime"]);
                        break;
                }
            }
            foreach ($config['filter_user'] as $r) {
                switch ($r['type']) {
                    case GridList::FILTER_TEXT:
                        $list->where($r['col'], 'like', "%" . $uid . "%");
                        break;
                    case GridList::FILTER_HIDDEN:
                    case GridList::FILTER_ENUM:
                        $list->where($r['col'], $uid);
                        break;
                    case GridList::FILTER_STARTTIME:
                        $list->where($r['col'], ">", $uid . "_starttime");
                        break;
                    case GridList::FILTER_ENDTIME:
                        $list->where($r['col'], "<", $uid . "_endtime");
                        break;
                }
            }
            $columns = [];
            foreach ($config['columns'] as $column) {
                if ($column['type'] == "DISPLAY" || $column['type'] == "RICH_DISPLAY")
                    continue;
                $columns[] = $column['col'];
            }
            if ($config['orderBy'] != null)
                $list = $list->orderBy($config['orderBy'][0], $config['orderBy'][1]);
            $res = $list
                ->select($columns)
                ->paginate(15);
            $res = json_decode(json_encode($res), true);
            foreach ($res['data'] as &$resi) {
                $resi['BUTTON_CONDITION_DATA'] = [];
                foreach ($config['columns'] as $column)
                    if ($column['type'] == "DISPLAY" || $column['type'] == "RICH_DISPLAY")
                        $resi[$column['col']] = '';
                foreach ($config['row_buttons'] as $rowButtonItem) {
                    if ($rowButtonItem['show_condition'] == null)
                        $resi['BUTTON_CONDITION_DATA'][] = true;
                    else
                        $resi['BUTTON_CONDITION_DATA'][] = $rowButtonItem['show_condition']->isShow($resi);
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
                "msg"    => $e2->getMessage() . $e2->getLine()
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
        if ($gridList)
            $gridList = $gridList->json();
        if ($gridCreate)
            $gridCreate = $gridCreate->json();
        if ($gridEdit)
            $gridEdit = $gridEdit->json();
        return json_encode([
            "status" => 1,
            "grid"   => [
                "list"   => json_decode($gridList, true),
                "create" => json_decode($gridCreate, true),
                "edit"   => json_decode($gridEdit, true)
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
            $tableObj = $this->gridObj->getCreateForm()->getArr();
            $param = [];
            foreach ($tableObj['columns'] as $col) {
                if ($col['type'] === GridCreateForm::COLUMN_PASSWORD)
                    $param[$col['col']] = md5($req[$col['col']]);
                else if ($col['type'] !== GridCreateForm::COLUMN_DISPLAY)
                    $param[$col['col']] = $req[$col['col']];
            }
            $hook = $this->gridObj->getCreateHook();
            if ($hook != null)
                $param = $hook->hook($param);
            if ($param != null)
                $tableObj['table']->insert($param);
            return json_encode([
                "status" => 1,
                "msg"    => "创建成功"
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
            $tableObj = $this->gridObj->getEditForm()->getArr();
            $res = $tableObj['table']->find($request->get("id"));
            if (!$res)
                throw new Exception("该项目不存在");
            $res = json_decode(json_encode($res), true);
            $needsTip = [];
            foreach ($tableObj['columns'] as $col) {
                if ($col['type'] === GridCreateForm::COLUMN_PASSWORD || $col['type'] === GridEditForm::COLUMN_PASSWORD)
                    $res[$col['col']] = "";
                if ($col['type'] === GridCreateForm::COLUMN_CHILDREN_CHOOSE || $col['type'] === GridEditForm::COLUMN_CHILDREN_CHOOSE) {
                    $needsTip[] = $col;
                }
            }
            $tip = [];
            foreach ($needsTip as $needTip) {
                $dbObj = $needTip['extra']->getDBObject();
                $list = clone $dbObj;
                foreach ($needTip['extra']->getArr()['filter_columns'] as $r) {
                    switch ($r['type']) {
                        case GridList::FILTER_TEXT:
                            if ($request->get($r['col'], "") !== '')
                                $list->where($r['col'], 'like', "%" . $request->post($r['col']) . "%");
                            break;
                        case GridList::FILTER_HIDDEN:
                        case GridList::FILTER_ENUM:
                            if ($request->get($r['col'], "") !== '')
                                $list->where($r['col'], $request->get($r['col'], ""));
                            break;
                        case GridList::FILTER_STARTTIME:
                            if ($request->get($r['col'] . "_starttime", "") !== '')
                                $list->where($r['col'], ">", $request->get($r['col'] . "_starttime", ""));
                            break;
                        case GridList::FILTER_ENDTIME:
                            if ($request->get($r['col'] . "_endtime", "") !== '')
                                $list->where($r['col'], "<", $request->get($r['col'] . "_endtime", ""));
                            break;
                    }
                }
                $key = $needTip['extra']->getArr()['columns'][0]['col'];
                $display = $needTip['extra']->getArr()['displayColumn'];
                if ($res[$needTip['col']] != "")
                    $tip[$needTip['col']] = $list->where($key, $res[$needTip['col']])->first();
            }
            $hook = $this->gridObj->getDetailHook();
            if ($hook != null)
                return json_encode($hook->hook([
                    "status" => 1,
                    "data"   => $res,
                    "tip"    => $tip
                ]));
            return json_encode([
                "status" => 1,
                "data"   => $res,
                "tip"    => $tip
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
            $tableObj = $this->gridObj->getEditForm()->getArr();
            $param = [];
            foreach ($tableObj['columns'] as $col) {
                if ($col['type'] === GridEditForm::COLUMN_PASSWORD) {
                    if ($req[$col['col']] !== '')
                        $param[$col['col']] = md5($req[$col['col']]);
                } else if ($col['type'] !== GridEditForm::COLUMN_DISPLAY)
                    $param[$col['col']] = $req[$col['col']];
            }
            $hook = $this->gridObj->getSaveHook();
            if ($hook != null)
                $param = $hook->hook($param);
            if ($param != null)
                $tableObj['table']->onUpdate($tableObj['columns'], $param);
            return json_encode([
                "status" => 1,
                "msg"    => "保存成功"
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
        try {
            $this->getUserInfo($request);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => "登录失效"
            ]);
        }
        $type = $request->get("type");
        $column = $request->get("col");
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
        $formObj = null;
        if ($type == "create")
            $formObj = $this->gridObj->getCreateForm()->getArr();
        else if ($type == "edit")
            $formObj = $this->gridObj->getEditForm()->getArr();
        if ($type != null) {
            foreach ($formObj['columns'] as $r) {
                if ((($r['type'] == GridCreateForm::COLUMN_CHILDREN_CHOOSE && $type == "create") || ($r['type'] == GridEditForm::COLUMN_CHILDREN_CHOOSE && $type == "edit")) && $column == $r['col']) {
                    $list = $r['extra']->getDBObject();
                    $config = $r['extra']->getArr();
                    $req = json_decode($request->getContent(), true);
                    foreach ($config['filter_columns'] as $r) {
                        switch ($r['type']) {
                            case GridList::FILTER_TEXT:
                                if ($req[$r['col']] !== '')
                                    $list->where($r['col'], 'like', "%" . $request->post($r['col']) . "%");
                                break;
                            case GridList::FILTER_HIDDEN:
                            case GridList::FILTER_ENUM:
                                if ($req[$r['col']] !== '')
                                    $list->where($r['col'], $req[$r['col']]);
                                break;
                            case GridList::FILTER_STARTTIME:
                                if ($req[$r['col'] . "_starttime"] !== '')
                                    $list->where($r['col'], ">", $req[$r['col'] . "_starttime"]);
                                break;
                            case GridList::FILTER_ENDTIME:
                                if ($req[$r['col'] . "_endtime"] !== '')
                                    $list->where($r['col'], "<", $req[$r['col'] . "_endtime"]);
                                break;
                        }
                    }
                    $columns = array_column($config['columns'], 'col');
                    foreach ($columns as &$column)
                        $column = $column . " as " . $column;
                    if ($config['orderBy'] != null)
                        $list = $list->orderBy($config['orderBy'][0], $config['orderBy'][1]);
                    $res = $list
                        ->select($columns)
                        ->paginate(8);
                    $res = json_decode(json_encode($res), true);
                    $res['status'] = 1;
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
            if (!$this->gridObj->getGridList()->getArr()['hasDelete'])
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
            $config = $this->gridObj->getGridList()->getArr();
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
            foreach ($config['delete_join'] as $table) {
                DB::table($table[0])->where($table[2], $resp[$table[1]])->delete();
            }
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
     * 根据UID对控制器下所有接口进行鉴权
     * @param String $uid 用户UID
     * @return Boolean 返回真则验权通过，否则验权失败
     */
    protected abstract function checkPower($uid);
}
