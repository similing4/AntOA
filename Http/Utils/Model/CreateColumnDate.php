<?php
/**
 * FileName:CreateColumnDate.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2024/2/27
 * Time:13:03
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnDate extends CreateColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnDate"
        ]);
    }
}
