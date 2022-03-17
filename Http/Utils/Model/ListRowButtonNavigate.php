<?php
/**
 * FileName:ListRowButtonNavigate.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:10:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonNavigate
 * 描述: 列表页每行跳转页面按钮实体类
 */
abstract class ListRowButtonNavigate extends ListRowButtonBase {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListRowButtonNavigate"
        ]);
    }
}
