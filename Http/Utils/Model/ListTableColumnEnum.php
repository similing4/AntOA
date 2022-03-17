<?php
/**
 * FileName:ListTableColumnEnum.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:33
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnEnum
 * 描述:用于根据对应状态展示对应内容的实例
 */
class ListTableColumnEnum extends ListTableColumnBase {
    /**
     * @var array<EnumOption> 单选选项数组
     */
    public $options;

    /**
     * ListTableColumnEnum constructor.
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     * @param array<EnumOption> $options 单选选项数组
     */
    public function __construct($col, $tip, $options) {
        parent::__construct($col, $tip);
        $this->options = $options;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListTableColumnEnum",
            "enum" => $this->options
        ]);
    }
}
