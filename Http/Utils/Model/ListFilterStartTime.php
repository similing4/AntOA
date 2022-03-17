<?php
/**
 * FileName:ListFilterStartTime.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:20:50
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterStartTime
 * 描述: 根据选定的时间对指定字段进行大于当前选定时间的筛选
 */
class ListFilterStartTime extends ListFilterBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterStartTime"
        ]);
    }
}
