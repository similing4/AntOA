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

    /**
     * 是否需要使用ApiUpload接口（接口中type为edit时才会调用）
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiUpload(){
        return true;
    }
}
