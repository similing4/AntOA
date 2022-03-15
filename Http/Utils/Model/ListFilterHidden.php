<?php
/**
 * FileName:ListFilterColumnHidden.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:20:43
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterHidden
 * 描述: 根据页面传入的参数进行筛选
 */
class ListFilterHidden extends ListFilterBase {
    public function __construct($col, $defaultVal = "") {
        parent::__construct($col, "", $defaultVal);
    }

    public function jsonSerialize() {
        return [
            "type"    => "ListFilterHidden",
            "col"     => $this->col,
            "tip"     => $this->tip,
            "default" => $this->defaultVal
        ];
    }
}
