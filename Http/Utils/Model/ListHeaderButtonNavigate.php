<?php
/**
 * FileName:ListHeaderButtonNavigate.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:9:31
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListHeaderButtonNavigate
 * 描述: 列表页头部跳转页面按钮实体类
 */
abstract class ListHeaderButtonNavigate extends ListHeaderButtonBase {
    public function jsonSerialize() {
        return [
            "type"       => "ListHeaderButtonNavigate",
            "buttonText" => $this->buttonText,
            "buttonType" => $this->buttonType,
            "baseUrl"    => $this->baseUrl,
            "finalUrl"   => $this->finalUrl
        ];
    }
}
