<?php
/**
 * FileName:ListFilterStartDate.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2024/2/27
 * Time:12:43
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterStartDate
 * 描述: 根据选定的时间对指定字段进行大于当前选定时间的筛选
 */
class ListFilterStartDate extends ListFilterBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterStartDate"
        ]);
    }

    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid) {
        $param = $urlParamCalculator->getPageParamByKey($this->col . "_startdate");
        if ($param !== null && $param->val !== '')
            $gridListDbObject->where($this->col, ">=", $param->val . " 00:00:00");
    }
}
