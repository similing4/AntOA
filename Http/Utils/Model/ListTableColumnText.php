<?php
/**
 * FileName:ListTableColumnText.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:19
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnText
 * 描述:普通表列，直接展示对应的数据值
 */
class ListTableColumnText extends ListTableColumnBase {
    public function jsonSerialize() {
        return [
            "type" => "ListTableColumnText",
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }
}
