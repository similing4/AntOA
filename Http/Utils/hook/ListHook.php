<?php
/**
 * FileName:ListHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/11
 * Time:18:13
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;

/**
 * Interface ListHook
 * @package Modules\AntOA\Http\Utils\hook
 * 列表接口后置钩子
 */
interface ListHook {
    /**
     * 列表接口后置钩子
     * @param array $response 列表paginate经过数组化的数据
     * @return array 返回传入参数
     */
    public function hook($response);
}
