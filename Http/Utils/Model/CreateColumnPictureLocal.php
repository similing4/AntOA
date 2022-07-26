<?php
/**
 * FileName:CreateColumnPictureLocal.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/5/10/010
 * Time:13:44
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Illuminate\Http\Request;
use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnPictureLocal extends CreateColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnPictureLocal"
        ]);
    }

    /**
     * 是否需要使用ApiUpload接口（接口中type为edit时才会调用）
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiUpload(){
        return true;
    }
}
