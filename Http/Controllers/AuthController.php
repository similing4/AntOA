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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\AntOA\Http\Utils\AuthInterface;
use Modules\AntOA\Http\Utils\Qiniu\Auth as QiniuAuth;

class AuthController {
    protected $auth = null;

    public function __construct(AuthInterface $auth) {
        $this->auth = $auth;
    }

    /**
     * 登录页面
     * @return View
     */
    public function page_login() {
        return view('antoa::auth/login');
    }

    /**
     * 注销页面
     * @param Request $request
     * token：token
     */
    public function page_logout(Request $request) {
        $auth = $this->auth;
        $auth->removeToken($request->get("token"));
        header("Location:/antoa/auth/login");
        exit;
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
            $menu = $this->getConfigMenu($uid);
            $accessKey = $qiniu['access_key'];
            $secretKey = $qiniu['secret_key'];
            $bucketName = $qiniu['bucket'];
            $auth = new QiniuAuth($accessKey, $secretKey);
            $token = $auth->uploadToken($bucketName, null, 300, [
                "detectMime" => 3,
                "saveKey"    => '$(etag)' . '$(ext)'
            ], true);
            $routes = $this->makeRoutes($uid);
            return json_encode([
                "status" => 1,
                "host"   => $qiniu['url'],
                "menu"   => $menu,
                "token"  => $token,
                "routes" => $routes
            ]);
        } catch (Exception $e) {
            return json_encode([
                "status" => 0,
                "msg"    => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    private function makeRoutes($uid) {
        $user = DB::table("antoa_user")->where("id", $uid)->first();
        if (!$user)
            throw new Exception("登录失效");
        $roles = json_decode($user->role, true);
        $configRoutes = config('antoa.menu_routes');
        $configRoutes[] = [
            "title"      => "修改密码",
            "visible"    => false,
            "children"   => [
                [
                    "visible"    => false,
                    "uri"        => "/antoa/user/change_password",
                    "title"      => "修改密码",
                    "role_limit" => []
                ]
            ],
            "role_limit" => []
        ];
        foreach ($configRoutes as &$fmenu) {
            if (!array_key_exists("role_limit", $fmenu))
                $fmenu['role_limit'] = [];
            if (array_key_exists("children", $fmenu))
                foreach ($fmenu['children'] as &$child) {
                    if (!array_key_exists("role_limit", $child))
                        $child['role_limit'] = [];
                }
        }
        $routes = [];
        $id = 0;
        foreach ($configRoutes as $r2) {
            if(count($r2['role_limit']) !== 0 && count(array_intersect($r2['role_limit'], $roles)) == 0)
                continue;
            $id++;
            $ra = [
                "path"     => "/parent/" . $id,
                "router"   => 'bparent',
                "name"     => $r2['title'],
                "children" => [],
                "invisible" => array_key_exists('visible', $r2) ? !$r2['visible'] : false
            ];
            if (array_key_exists('uri', $r2))
                $ra["path"] = $r2['uri'];
            if (array_key_exists('isHome', $r2) && $r2['isHome']) {
                $ra = [
                    "router"   => 'bparent',
                    "name"     => $r2['title'],
                    "children" => ['home']
                ];
                $routes[] = $ra;
                continue;
            }
            if (array_key_exists("children", $r2))
                foreach ($r2['children'] as $child) {
                    if(count($child['role_limit']) !== 0 && count(array_intersect($child['role_limit'], $roles)) == 0)
                        continue;
                    $router = $this->getRouterFromPath($child['uri']);
                    $breadcrumb = [
                        "首页",
                        array_key_exists('breadcrumbTitle', $r2) ? $r2['breadcrumbTitle'] : $r2['title'],
                        array_key_exists('breadcrumbTitle', $child) ? $child['breadcrumbTitle'] : $child['title']
                    ];
                    $ra['children'][] = [
                        "router"    => $router['router'],
                        "name"      => $child['title'],
                        "path"      => $router['path'],
                        "invisible" => array_key_exists('visible', $child) ? !$child['visible'] : false,
                        "meta"      => [
                            "page" => [
                                "breadcrumb" => $breadcrumb
                            ]
                        ]
                    ];
                }
            else {
                $router = $this->getRouterFromPath($r2['uri']);
                $breadcrumb = [
                    "首页",
                    array_key_exists('breadcrumbTitle', $r2) ? $r2['breadcrumbTitle'] : $r2['title'],
                ];
                $ra = [
                    "router"    => $router['router'],
                    "name"      => $r2['title'],
                    "path"      => $router['path'],
                    "meta"      => [
                        "page" => [
                            "breadcrumb" => $breadcrumb
                        ]
                    ]
                ];
            }
            $routes[] = $ra;
        }
        $root = ['router' => 'root', 'children' => $routes];
        return [$root];
    }

    private function getRouterFromPath($uri) {
        $startsWith = function ($haystack, $needle) {
            return strncmp($haystack, $needle, strlen($needle)) === 0;
        };
        $endsWith = function ($haystack, $needle) {
            return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
        };
        if ($startsWith($uri, "http") === 0)
            return [
                "router" => "url",
                "path"   => "/url?url=" . $uri
            ];
        $url = parse_url($uri);
        $path = $url['path'];
        if ($endsWith($path, "/create")) {
            return [
                "router" => "create",
                "path"   => $path
            ];
        }
        if ($endsWith($path, "/edit")) {
            return [
                "router" => "edit",
                "path"   => $path
            ];
        }
        if ($endsWith($path, "/list")) {
            return [
                "router" => "list",
                "path"   => $path
            ];
        }
        return [
            "router" => $path,
            "path"   => $uri,
        ];
    }

    private function getConfigMenu($uid) {
        $user = DB::table("antoa_user")->where("id", $uid)->first();
        if (!$user)
            throw new Exception("登录失效");
        $roles = json_decode($user->role, true);
        $menus = config('antoa.menu_routes');
        $menu_return = [];
        foreach ($menus as &$fmenu) {
            if (!array_key_exists("role_limit", $fmenu))
                $fmenu['role_limit'] = [];
            if (!array_key_exists("children", $fmenu))
                $fmenu['children'] = [];
            foreach ($fmenu['children'] as &$child) {
                if (!array_key_exists("role_limit", $child))
                    $child['role_limit'] = [];
            }
        }
        $menus = json_decode(json_encode($menus), true);
        foreach ($menus as $fmenu2) {
            if (count($fmenu2['role_limit']) === 0 || count(array_intersect($fmenu2['role_limit'], $roles)) > 0) {
                $menu_item = json_decode(json_encode($fmenu2), true);
                $menu_item['children'] = [];
                foreach ($fmenu2['children'] as $child) {
                    if (count($child['role_limit']) === 0 || count(array_intersect($child['role_limit'], $roles)) > 0)
                        $menu_item['children'][] = $child;
                }
                $menu_return[] = $menu_item;
            }
        }
        return $menu_return;
    }
}
