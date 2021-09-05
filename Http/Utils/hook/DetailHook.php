<?php
/**
 * FileName:DetailHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:18:14
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface DetailHook
 * @package Modules\AntOA\Http\Utils\hook
 * 详情接口后置钩子
 */
interface DetailHook  {
    /**
     * 详情接口后置钩子
     * @param array $response 列表paginate经过数组化的数据
     * @return array 返回传入参数
     */
    public function hook($response);
}
