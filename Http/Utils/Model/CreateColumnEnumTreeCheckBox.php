<?php
/**
 * FileName:CreateColumnEnumTreeCheckBox.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:21
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnEnumTreeCheckBox extends CreateColumnBase {
    /**
     * @var array<TreeNode> 多选选项数组
     */
    public $options;

    /**
     * CreateColumnEnumTreeCheckBox constructor.
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param array<TreeNode> $options 多选选项数组
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, $options, $defaultVal = '') {
        parent::__construct($col, $tip, $defaultVal);
        $this->options = $options;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnEnumTreeCheckBox",
            "enum" => $this->options
        ]);
    }
}
