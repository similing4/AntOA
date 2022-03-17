<?php
/**
 * FileName:ListTableColumnDisplay.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:26
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnDisplay
 * 描述: 用于展示的表列，需要使用hook配置其内容值，否则展示内容为空
 */
class ListTableColumnDisplay extends ListTableColumnBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListTableColumnDisplay"
        ]);
    }
}
