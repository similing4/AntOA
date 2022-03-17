<?php
/**
 * FileName:CreateRowButtonApiWithConfirm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/17/017
 * Time:9:41
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateRowButtonBase;

abstract class CreateRowButtonApiWithConfirm extends CreateRowButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateRowButtonApiWithConfirm"
        ]);
    }
}
