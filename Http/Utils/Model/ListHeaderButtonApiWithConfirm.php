<?php
/**
 * FileName:ListHeaderButtonApiWithConfirm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:10:56
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListHeaderButtonApiWithConfirm
 * 描述: 列表页头部按钮调用API按钮实体类（要求确定后才会请求）
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功展示data字段，否则客户端展示msg字段
 */
abstract class ListHeaderButtonApiWithConfirm extends ListHeaderButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListHeaderButtonApiWithConfirm"
        ]);
    }
}
