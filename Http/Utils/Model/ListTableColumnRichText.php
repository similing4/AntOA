<?php
/**
 * FileName:ListTableColumnRichDisplay.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:28
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnRichText
 * 描述:富文本表列
 */
class ListTableColumnRichText extends ListTableColumnBase {
    public function jsonSerialize() {
        return [
            "type" => "ListTableColumnRichText",
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }
}
