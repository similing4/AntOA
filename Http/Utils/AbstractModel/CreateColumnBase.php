<?php
/**
 * FileName:CreateColumn.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:10:42
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use Illuminate\Http\Request;
use JsonSerializable;

/**
 * NameSpace: Modules\AntOA\Http\Utils\AbstractModel
 * ClassName: CreateColumnBase
 * 描述: 可用于编辑页的选项列对象
 */
abstract class CreateColumnBase implements JsonSerializable {
    /**
     * @var String 列对应的字段
     */
    public $col;
    /**
     * @var String 列对应的字段Label
     */
    public $tip;
    /**
     * @var String 列对应的默认值
     */
    public $defaultVal;

    /**
     * 表列构造方法基类
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param string $defaultVal 列对应默认值
     */
    public function __construct($col, $tip, $defaultVal = "") {
        $this->col = $col;
        $this->tip = $tip;
        $this->defaultVal = $defaultVal;
    }

    public function jsonSerialize() {
        return [
            "col"     => $this->col,
            "tip"     => $this->tip,
            "default" => $this->defaultVal
        ];
    }

    public function onGuestVal($req, $uid){
        return $req[$this->col];
    }

    /**
     * 是否需要使用ApiDetailColumnList接口（接口中type为create时才会调用）
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiDetailColumnList(){
        return false;
    }

    /**
     * 是否需要使用ApiUpload接口（接口中type为create时才会调用）
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiUpload(){
        return false;
    }

    /**
     * @param Request $request 请求数据
     * @param string $uid 登录用户UID
     * @return string 返回给前端的json数据
     */
    public function dealApiDetailColumnList(Request $request, $uid){
        return json_encode([
            "status" => 0,
            "msg" => "接口不存在"
        ]);
    }
}
