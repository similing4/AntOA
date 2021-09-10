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
 * Interface DeleteHook
 * @package Modules\AntOA\Http\Utils\hook
 * 删除接口前置钩子
 */
interface DeleteHook {
    /**
     * 删除接口前置钩子
     * @param string $id 待删除的内容ID
     * @return array|null 返回传入参数，如果返回null则不进行删除操作。
     */
    public function hook($id);
}
