<?php
/**
 * FileName:AuthController.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/3
 * Time:16:45
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\AntOA\Http\Utils\AuthInterface;
use Modules\AntOA\Http\Utils\Qiniu\Auth as QiniuAuth;

class AuthController {
    protected $auth = null;

    public function __construct(AuthInterface $auth) {
        $this->auth = $auth;
    }

    /**
     * 登录授权
     * @param Request $request
     * username：用户名
     * password：密码
     * @return String 登录成功时data字段为token，否则msg字段为错误信息
     */
    public function login(Request $request) {
        try {
            $req = json_decode($request->getContent(), true);
            $username = $req["username"];
            $password = $req["password"];
            if ($username == "" || $password == "")
                throw new \Exception("用户名或密码不能为空");
            $result = DB::table("antoa_user")->where("username", $username)->first();
            if (!$result)
                throw new \Exception("用户名或密码错误");
            if ($result->password != md5($password))
                throw new \Exception("用户名或密码错误");
            if ($result->status == 0)
                throw new \Exception("您的账户已被禁用，如有疑问请联系管理员！");
            $result = json_decode(json_encode($result), true);
            $auth = $this->auth;
            $token = $auth->makeTokenWithCache($result['id']);
            return json_encode([
                "status" => 1,
                "data"   => $token
            ]);
        } catch (\Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage()
            ]);
        }
    }

    /**
     * TOKEN验权
     * @param Request $request
     * token：TOKEN
     * @return String 通用成功失败返回
     */
    public function auth(Request $request) {
        $auth = $this->auth;
        $uid = $auth->getUidFromToken($request->header("Authorization"));
        if ($uid) {
            return json_encode([
                "status" => 1,
                "data"   => $uid
            ]);
        }
        return json_encode([
            "status" => 0,
            "data"   => "登录失效"
        ]);
    }

    /**
     * 获取七牛云等配置API
     * @param Request $request
     * @return String 通用成功失败返回
     */
    public function api_config(Request $request) {
        try {
            $auth = $this->auth;
            $token = $request->header('Authorization');
            $uid = $auth->getUidFromToken($token);
            if (!$uid)
                throw new Exception("登录失效");
            $qiniu = config("antoa.config.qiniu");
            $accessKey = $qiniu['access_key'];
            $secretKey = $qiniu['secret_key'];
            $bucketName = $qiniu['bucket'];
            $auth = new QiniuAuth($accessKey, $secretKey);
            $token = $auth->uploadToken($bucketName, null, 300, [
                "detectMime" => 3,
                "saveKey"    => '$(etag)' . '$(ext)'
            ], true);
            $routes = $this->makeRoutes($uid);
            $titleMap = $this->makeTitleMap();
            return json_encode([
                "status"    => 1,
                "host"      => $qiniu['url'],
                "token"     => $token,
                "routes"    => $routes,
                "title_map" => $titleMap
            ]);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    private function makeTitleMap() {
        $configRoutes = config('antoa.menu_routes');
        $ret = [];
        $dfs = function ($item) use (&$dfs, &$ret) {
            if (array_key_exists("children", $item))
                foreach ($item['children'] as $r)
                    $dfs($r);
            if (!array_key_exists('path', $item))
                return;
            if (!array_key_exists('name', $item))
                return;
            $ret[] = [
                "path" => $item['path'],
                "name" => $item['name']
            ];
        };
        foreach ($configRoutes as $r)
            $dfs($r);
        $ret[] = [
            "path" => "/antoa/user/change_password",
            "name" => "修改密码"
        ];
        return $ret;
    }

    private function makeRoutes($uid) {
        $user = DB::table("antoa_user")->where("id", $uid)->first();
        if (!$user)
            throw new Exception("登录失效");
        $user = json_encode($user);
        $user = json_decode($user, true);
        $configRoutes = config('antoa.menu_routes');
        $configRoutes[] = [
            "name"     => "修改密码",
            "visible"  => false,
            "children" => [
                [
                    "visible" => false,
                    "path"    => "/antoa/user/change_password",
                    "name"    => "修改密码"
                ]
            ]
        ];
        foreach ($configRoutes as &$configRoutesItem) {
            if (array_key_exists('children', $configRoutesItem)) {
                $configRoutesItem['children'] = array_values(array_filter($configRoutesItem['children'], function ($r) use ($user) {
                    $limitVailed = true;
                    if (array_key_exists('role_limit', $r))
                        $limitVailed = $r['role_limit']($user);
                    return (!array_key_exists("visible", $r) || $r['visible']) && $limitVailed;
                }));
            }
            if (array_key_exists('isHome', $configRoutesItem) && $configRoutesItem['isHome']) {
                $configRoutesItem['children'] = [];
                unset($configRoutesItem['children']);
                $configRoutesItem['path'] = "/home";
            }
        }
        $configRoutes = array_filter($configRoutes, function ($r) {
            return !array_key_exists("visible", $r) || $r['visible'];
        });
        return $configRoutes;
    }
}
