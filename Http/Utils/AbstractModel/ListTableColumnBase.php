<?php
/**
 * FileName:ListTableColumnBase.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:15:59
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;
use JsonSerializable;

abstract class ListTableColumnBase implements JsonSerializable {
    /**
     * @var String 列对应的字段
     */
    public $col;
    /**
     * @var String 列对应的字段Label
     */
    public $tip;

    /**
     * 表列构造方法基类
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     */
    public function __construct($col, $tip) {
        $this->col = $col;
        $this->tip = $tip;
    }

    public function jsonSerialize() {
        return [
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }

    public function onParse(&$searchResultItem, UrlParamCalculator $urlParamCalculator, $uid){
    }
}
