<?php
/**
 * FileName:ListTableColumnDivideNumber.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:53
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnDivideNumber
 * 描述: 当查出的数值需要除以一定的数值（如分转元）时可以使用该类的实例，该实例会将数据值除以指定值并可选附加单位
 */
class ListTableColumnDivideNumber extends ListTableColumnBase {
    /**
     * @var Number 除数
     */
    public $divide;
    /**
     * @var String 单位，默认为空
     */
    public $unit;

    /**
     * @param String $col 列名
     * @param String $tip 在列表页该列的的表头名称
     * @param Number $divide 除数
     * @param String $unit 单位，默认为空
     */
    public function __construct($col, $tip, $divide, $unit = '') {
        parent::__construct($col, $tip);
        $this->divide = $divide;
        $this->unit = $unit;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListTableColumnDivideNumber",
            "divide" => $this->divide,
            "unit"   => $this->unit
        ]);
    }
}
