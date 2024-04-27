<?php
/**
 * FileName:EditColumnDisplay.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:51
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;

class EditColumnDisplay extends EditColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "EditColumnDisplay"
        ]);
    }

    /**
     * 当客户端数据传入时
     * @param $req
     * @return string 返回需要接下来
     */
    public function onGuestVal($req, $uid) {
        return "";
    }
}
