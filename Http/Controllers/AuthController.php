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
                "msg"   => $e->getMessage()
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
        $token = $auth->getUidFromToken($request->header("Authorization"));
        if ($token)
            return json_encode([
                "status" => 1,
                "data"   => $token
            ]);
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
        $auth = $this->auth;
        $token = $request->header('Authorization');
        if (!$auth->getUidFromToken($token))
            return json_encode([
                "status" => 0,
                "msg"    => "登录失效"
            ]);
        $qiniu = config("antoa.config.qiniu");
        $menu = config('antoa.menu_routes');
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];
        $bucketName = $qiniu['bucket'];
        $auth = new QiniuAuth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucketName, null, 300, [
            "detectMime" => 3,
            "saveKey"    => '$(etag)' . '$(ext)'
        ], true);
        $routes = $this->makeRoutes();
        return json_encode([
            "status" => 1,
            "host"   => $qiniu['url'],
            "menu"   => $menu,
            "token"  => $token,
            "routes" => $routes
        ]);
    }

    private function makeRoutes() {
        $configRoutes = config('antoa.menu_routes');
        $routes = [];
        $id = 0;
        foreach ($configRoutes as $r2) {
            $id++;
            $ra = [
                "path"     => "/parent/" . $id,
                "router"   => 'bparent',
                "name"     => $r2['title'],
                "children" => []
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
                    "router" => $router['router'],
                    "name"   => $r2['title'],
                    "path"   => $router['path'],
                    "meta"   => [
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
}
