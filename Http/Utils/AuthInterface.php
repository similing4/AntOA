<?php
/**
 * FileName:AuthInterface.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/21
 * Time:11:48
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;

/**
 * Interface AuthInterface 授权TOKEN管理类
 * @package Modules\AntOA\Http\Utils
 */
interface AuthInterface {
    /**
     * 生成持久化的TOKEN
     * @param String $uid 用户UID
     * @return String token字符串
     */
    public function makeTokenWithCache($uid);

    /**
     * 从TOKEN获取UID
     * @param String $token 用户TOKEN
     * @return String|null 查询到返回用户UID，否则返回null
     */
    public function getUidFromToken($token);

    /**
     * 删除TOKEN
     * @param String $token 用户TOKEN
     */
    public function removeToken($token);
}
