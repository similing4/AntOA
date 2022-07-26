<?php
/**
 * FileName:ListFilterEnum.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:20:56
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListFilterBase;
use Modules\AntOA\Http\Utils\DBListOperator;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListFilterEnum
 * 描述: 根据下拉单选框进行筛选
 */
class ListFilterEnum extends ListFilterBase {
    /**
     * @var array<EnumOption> 单选选项数组
     */
    public $options;

    /**
     * ListTableColumnEnum constructor.
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param array<EnumOption> $options 单选选项数组
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, $options, $defaultVal = "") {
        parent::__construct($col, $tip, $defaultVal);
        $this->options = $options;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListFilterEnum",
            "enum" => $this->options
        ]);
    }

    public function onFilter(DBListOperator $gridListDbObject, UrlParamCalculator $urlParamCalculator, $uid) {
        $param = $urlParamCalculator->getPageParamByKey($this->col);
        if ($param !== null && $param->val != '')
            $gridListDbObject->where($this->col, $param->val);
    }
}
