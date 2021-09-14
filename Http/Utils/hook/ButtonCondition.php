<?php
/**
 * FileName:ButtonCondition.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/14
 * Time:15:42
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface ButtonCondition
 * @package Modules\AntOA\Http\Utils\hook
 * 按钮条件对象
 */
interface ButtonCondition {
    /**
     * 按钮条件钩子，返回true时显示该按钮，否则不显示该按钮
     * @param array $rowData 行数据
     * @return boolean 返回true时显示该按钮，否则不显示该按钮
     */
    public function isShow($rowData);
}
