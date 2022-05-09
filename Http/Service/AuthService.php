<?php
/**
 * FileName:Auth.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/4
 * Time:9:43
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Service;


use Illuminate\Support\Facades\Cache;
use Modules\AntOA\Http\Utils\AuthInterface;

class AuthService implements AuthInterface{
    /**
     * 生成持久化的TOKEN
     * @param String $uid 用户UID
     * @return String token字符串
     */
    public function makeTokenWithCache($uid) {
        $token = Cache::get('ANTOA_USER_AUTH_TOKEN_' . $uid);
        if (!$token)
            $token = md5(md5(time() . "SSSTXTTSADFFzfdsa") . rand(15, 158) . "AsWttRT878DXZDFDxz");
        Cache::put('ANTOA_USER_AUTH_TOKEN_' . $uid, $token, 86400);
        return $uid . "_" . $token;
    }

    /**
     * 从TOKEN获取UID
     * @param String $token 用户TOKEN
     * @return String|null 查询到返回用户UID，否则返回null
     */
    public function getUidFromToken($token) {
        if($token == null)
            return null;
        $arr = explode("_", $token);
        $uid = $arr[0];
        $token = Cache::get('ANTOA_USER_AUTH_TOKEN_' . $uid);
        if (!$token)
            return null;
        if ($token != $arr[1])
            return null;
        return $uid;
    }

    /**
     * 删除TOKEN
     * @param String $token 用户TOKEN
     */
    public function removeToken($token) {
        $uid = $this->getUidFromToken($token);
        Cache::forget('ANTOA_USER_AUTH_TOKEN_' . $uid);
    }
}
