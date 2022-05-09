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

use Exception;

/**
 * Interface CreateHook
 * @package Modules\AntOA\Http\Utils\hook
 * 创建接口前置钩子
 */
interface CreateHook {
    /**
     * 创建接口前置钩子
     * @param array $param 传入参数
     * @return array|null 返回传入参数，如果返回null则不进行插入操作。
     * @throws Exception
     */
    public function hook($param);
}
