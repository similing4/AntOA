<?php
/**
 * FileName:ListFilterUID.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:21:01
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterUID
 * 描述: 根据当前登录的用户UID对指定字段进行筛选
 */
class ListFilterUID extends ListFilterBase {
    /**
     * 构造方法
     * @param String $col 列对应的字段
     */
    public function __construct($col) {
        parent::__construct($col, "");
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterUID"
        ]);
    }

    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid) {
        $gridListDbObject->where($this->col, $uid);
    }
}
