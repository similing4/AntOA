<?php
/**
 * FileName:EditColumnPassword.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditColumnBase;

class EditColumnPassword extends EditColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "EditColumnPassword"
        ]);
    }

    /**
     * 当服务端数据传出时
     * @param $res
	 * @param $uid
     * @return string 返回需要接下来
     */
    public function onServerVal($res, $uid){
        return "";
    }
}
