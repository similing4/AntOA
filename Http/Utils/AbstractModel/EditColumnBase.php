<?php
/**
 * FileName:EditColumn.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:10:42
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;

/**
 * NameSpace: Modules\AntOA\Http\Utils\AbstractModel
 * ClassName: EditColumnBase
 * 描述: 可用于编辑页的选项列对象
 */
abstract class EditColumnBase implements JsonSerializable {
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

    /**
     * 当客户端数据传入时
     * @param $req
     * @return string 返回需要接下来
     */
    public function onGuestVal($req, $uid){
        return $req[$this->col];
    }

    /**
     * 当服务端数据传出时
     * @param $res
     * @param $uid
     * @return string 返回需要接下来
     */
    public function onServerVal($res, $uid){
        return $res[$this->col];
    }
}
