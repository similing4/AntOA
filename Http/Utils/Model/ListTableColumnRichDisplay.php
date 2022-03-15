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
 * ClassName: ListTableColumnRichDisplay
 * 描述: 用于展示的富文本表列，需要使用hook配置其内容值，否则展示内容为空
 */
class ListTableColumnRichDisplay extends ListTableColumnBase {
    public function jsonSerialize() {
        return [
            "type" => "ListTableColumnRichDisplay",
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }
}
