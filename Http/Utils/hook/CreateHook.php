<?php
/**
 * FileName:CreateHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:18:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface CreateHook
 * @package Modules\AntOA\Http\Utils\hook
 * 创建接口前置钩子
 */
interface CreateHook {
    /**
     * 创建接口前置钩子
     * @param array $param 传入参数
     * @return array 返回传入参数
     */
    public function hook($param);
}
