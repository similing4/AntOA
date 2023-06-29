<?php
/**
 * FileName:ListHeaderButtonNavigateDownload.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2023/3/15/015
 * Time:10:21
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListHeaderButtonNavigateDownload
 * 描述: 列表页头部调用API按钮并跳转到结果URL实体类
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功展示data字段，否则客户端展示msg字段
 */
abstract class ListHeaderButtonNavigateDownload extends ListHeaderButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListHeaderButtonNavigateDownload"
        ]);
    }
}
