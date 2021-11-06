<?php
/**
 * FileName:NavigateParamHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/11/6
 * Time:10:18
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use \Illuminate\Http\Request;

interface NavigateParamHook {
    /**
     * 每行数据在跳转时进行的url拼接回调方法
     * @param array $row 行数据
     * @param Request $request 请求对象
     * @return string url参数字符串，注意不带问号
     */
    public function hook($row,Request $request);
}
