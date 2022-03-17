<?php
/**
 * FileName:CreateRowButtonApi.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/17/017
 * Time:9:40
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateRowButtonBase;

abstract class CreateRowButtonApi extends CreateRowButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateRowButtonApi"
        ]);
    }
}

