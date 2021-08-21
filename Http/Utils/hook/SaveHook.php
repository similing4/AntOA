<?php
/**
 * FileName:SaveHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:18:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface SaveHook
 * @package Modules\AntOA\Http\Utils\hook
 * 保存接口前置钩子
 */
interface SaveHook {
    /**
     * 保存接口前置钩子
     * @param array $param 传入参数
     * @return array 返回传入参数
     */
    public function hook($param);
}
