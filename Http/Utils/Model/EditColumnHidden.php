<?php
/**
 * FileName:EditColumnHidden.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:53
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;

class EditColumnHidden extends EditColumnBase {
    /**
     * 构造方法
     * @param String $col 列对应的字段
     */
    public function __construct($col) {
        parent::__construct($col, "");
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "EditColumnHidden"
        ]);
    }
}
