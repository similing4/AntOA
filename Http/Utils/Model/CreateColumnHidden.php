<?php
/**
 * FileName:CreateColumnHidden.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:53
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnHidden extends CreateColumnBase {
    /**
     * 构造方法
     * @param String $col 列对应的字段
     */
    public function __construct($col, $defaultVal = '') {
        parent::__construct($col, "", $defaultVal);
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnHidden"
        ]);
    }
}
