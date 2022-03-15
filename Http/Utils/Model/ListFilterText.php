<?php
/**
 * FileName:ListFilterText.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:18:11
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterText
 * 描述: 根据输入的文本对指定字段进行筛选
 */
class ListFilterText extends ListFilterBase {
    public function jsonSerialize() {
        return [
            "type"    => "ListFilterText",
            "col"     => $this->col,
            "tip"     => $this->tip,
            "default" => $this->defaultVal
        ];
    }
}
