<?php
/**
 * FileName:ListTableColumnHidden.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:50
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnHidden
 * 描述:隐藏的筛选列，用于查询时附带查询值但不展示到列表页上
 */
class ListTableColumnHidden extends ListTableColumnBase {
    /**
     * 构造方法
     * @param String $col 列对应的字段
     */
    public function __construct($col) {
        parent::__construct($col, "");
    }

    public function jsonSerialize() {
        return [
            "type" => "ListTableColumnHidden",
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }
}
