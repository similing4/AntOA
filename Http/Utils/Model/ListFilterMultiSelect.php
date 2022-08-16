<?php
/**
 * FileName:ListFilterMultiSelect.php
 * Author:张哲
 * Email:1061180002@qq.com
 * Date:2022/4/8
 * Time:10:28
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterMultiSelect
 * 描述: 根据下拉多选框进行筛选
 */
class ListFilterMultiSelect extends ListFilterBase {
    /**
     * @var array<EnumOption> 多选选项数组
     */
    public $options;

    /**
     * ListFilterMultiSelect constructor.
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param array<EnumOption> $options 多选选项数组
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, $options, $defaultVal = "") {
        parent::__construct($col, $tip, $defaultVal);
        $this->options = $options;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterMultiSelect",
            "enum" => $this->options
        ]);
    }

    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid) {
        $param = $urlParamCalculator->getPageParamByKey($this->col);
        if ($param !== null && $param->val !== '') {
            $array = json_decode($param->val, true);
            $gridListDbObject->whereIn($this->col, $array);
        }
    }
}
