<?php
/**
 * FileName:CreateOrEditColumnChangeHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/17
 * Time:13:56
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;


/**
 * Interface CreateOrEditColumnChangeHook
 * @package Modules\AntOA\Http\Utils\hook
 * 创建页或编辑页指定数据变更钩子
 */
interface CreateOrEditColumnChangeHook {
    /**
     * 创建页或编辑页指定数据变更钩子，页面初次加载时及指定行数据变更时回调。
     * @param array $formData 正在编辑的所有数据
     * @param String $column 筛选中被修改的字段
     * @return array 返回数据的data域将被Object.assign到form中填充字段，而display域将指定要展示的列数组（要包括自身），如果display字段为null或false则展示全部表单项。
     */
    public function hook($formData, $column);
}
