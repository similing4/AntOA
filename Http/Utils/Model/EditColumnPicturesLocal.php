<?php
/**
 * FileName:EditColumnPicturesLocal.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/5/10/010
 * Time:13:51
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;

class EditColumnPicturesLocal extends EditColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "EditColumnPicturesLocal"
        ]);
    }
}
