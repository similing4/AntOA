<?php
/**
 * FileName:ListChangeHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/11/10
 * Time:11:10
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface ListChangeHook
 * @package Modules\AntOA\Http\Utils\hook
 * 创建页或编辑页指定数据变更钩子
 */
interface ListChangeHook {
    /**
     * 列表页指定筛选字段变更钩子，指定页面初始化及行数据变更时回调。
     * @param array $formData 筛选中的所有列数据
     * @param String $column 筛选中被修改的字段
     * @return array 返回数据的data域将被Object.assign到筛选字段中填充字段，select域将被按照对应筛选字段的键赋值到其extra中。
     */
    public function hook($formData, $column);
}
