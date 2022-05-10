<?php
/**
 * FileName:CreateColumnFilesLocal.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/5/10/010
 * Time:13:41
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnFilesLocal extends CreateColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnFilesLocal"
        ]);
    }
}
