<?php
/**
 * FileName:ListFilterCascader.php
 * Author:张哲
 * Email:1061180002@qq.com
 * Date:2022/4/8
 * Time:10:32
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterCascader
 * 描述: 级联筛选
 */
class ListFilterCascader extends ListFilterBase {
    /**
     * @var array<CascaderNode> 多选选项数组
     */
    public $options;

    /**
     * ListFilterCascader constructor 级联选择构造方法，v-model绑定的应为字符串数组，数组中每一项应为CascaderNode中的value字段
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param array<CascaderNode> $options 多选选项数组
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, $options, $defaultVal = '') {
        parent::__construct($col, $tip, $defaultVal);
        $this->options = $options;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterCascader",
            "enum" => $this->options
        ]);
    }

    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid) {
        $param = $urlParamCalculator->getPageParamByKey($this->col);
        if ($param !== null && $param->val !== '') {
            $array = json_decode($param->val, true);
            $gridListDbObject->where($this->col, join($array, " "));
        }
    }
}
