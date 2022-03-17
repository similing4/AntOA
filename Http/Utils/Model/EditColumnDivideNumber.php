<?php
/**
 * FileName:EditColumnNumberDivide.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:10
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;

class EditColumnDivideNumber extends EditColumnBase {
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
     * @param string $defaultVal 默认值
     */
    public function __construct($col, $tip, $divide, $unit = '', $defaultVal = '') {
        parent::__construct($col, $tip, $defaultVal);
        $this->divide = $divide;
        $this->unit = $unit;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "EditColumnDivideNumber",
            "divide" => $this->divide,
            "unit"   => $this->unit
        ]);
    }
}
