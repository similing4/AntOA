<?php
/**
 * FileName:CreateColumnPassword.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\CreateColumnBase;

class CreateColumnPassword extends CreateColumnBase {

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "CreateColumnPassword"
        ]);
    }

    /**
     * 当服务端数据传出时
     * @param $serverVal
     * @return string 返回需要接下来
     */
    public function onServerVal($serverVal){
        return "";
    }
}
