<?php
/**
 * FileName:ListFilterBase.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:15:58
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;
use Modules\AntOA\Http\Utils\DBListOperator;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;

abstract class ListFilterBase implements JsonSerializable {
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
    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid){
    }
}
