<?php
/**
 * FileName:CreateColumnPicture.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:46
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Illuminate\Http\Request;
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnPicture extends CreateColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnPicture"
        ]);
    }
}
