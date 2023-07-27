<?php
/**
 * FileName:ListRowButtonClipboard.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:10:24
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonClipboard
 * 描述: 列表页每行调用API复制内容按钮实体类
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功展示data字段，否则客户端展示msg字段
 */
abstract class ListRowButtonClipboard extends ListRowButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListRowButtonClipboard"
        ]);
    }
}
