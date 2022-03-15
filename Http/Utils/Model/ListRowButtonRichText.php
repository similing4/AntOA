<?php
/**
 * FileName:ListRowButtonRichText.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:11:02
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonRichText
 * 描述: 列表页每行按钮调用API并将其值作为富文本进行模态框展示的实体类
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功并将data字段作为富文本展示，否则客户端展示msg字段
 */
abstract class ListRowButtonRichText extends ListRowButtonBase {
    public function jsonSerialize() {
        return [
            "type"       => "ListRowButtonRichText",
            "buttonText" => $this->buttonText,
            "buttonType" => $this->buttonType,
            "baseUrl"    => $this->baseUrl,
            "finalUrl"   => $this->finalUrl
        ];
    }
}
