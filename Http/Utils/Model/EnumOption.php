<?php
/**
 * FileName:ListTableColumnEnumOption.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:35
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnEnumOption
 * 描述:单选的可选的选项
 */
class EnumOption {
    /**
     * @var bool 是否可选中
     */
    public $disabled;
    /**
     * @var string 选项选中后传给后端的值
     */
    public $value;
    /**
     * @var string 选项展示的内容
     */
    public $title;

    /**
     * ListTableColumnEnum 的选项构造方法
     * @param string $value 选项选中后传给后端的值
     * @param string $title 选项展示的内容
     * @param bool $disabled 是否禁用该选项，默认为false，即可以选中
     */
    public function __construct($value, $title, $disabled = false) {
        $this->value = $value;
        $this->title = $title;
        $this->disabled = $disabled;
    }
}
