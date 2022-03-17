<?php
/**
 * FileName:CreateColumnCascader.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:14:57
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: CreateColumnCascader
 * 描述: 级联选择
 */
class CreateColumnCascader extends CreateColumnBase {
    /**
     * @var array<CascaderNode> 多选选项数组
     */
    public $options;

    /**
     * CreateColumnCascader constructor 级联选择构造方法，v-model绑定的应为字符串数组，数组中每一项应为CascaderNode中的value字段
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
            "type" => "CreateColumnCascader",
            "enum" => $this->options
        ]);
    }
}
