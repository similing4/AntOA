<?php
/**
 * FileName:AntOAUserController.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/15
 * Time:11:31
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Controllers;


use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\AntOA\Http\Utils\AuthInterface;

class AntOAUserController extends Controller {
    protected $auth = null; //Auth对象，如果你需要自己实现授权Token，你可以在Service文件夹下实现AuthInterface接口并在AntOAServiceProvider中修改接口实现类绑定。

    public function __construct(AuthInterface $auth) {
        $this->auth = $auth;
    }

    /**
     * 根据TOKEN获取UID，失败时抛出异常，只对API有效！
     * @param Request $request
     * @return string UID
     * @throws Exception token不合法时登录失效
     */
    protected function getUserInfo(Request $request) {
        $token = $request->header('Authorization');
        if ($token == null)
            throw new Exception("登录失效");
        $uid = $this->auth->getUidFromToken($token);
        if (!$uid)
            throw new Exception("登录失效");
        $user = DB::table("antoa_user")->find($uid);
        if ($user->status == 0)
            throw new Exception("账户已被封禁");
        return $uid;
    }
    
    public function changePassword(Request $request) {
        try {
            $uid = $this->getUserInfo($request);
            $data = $request->input();
            $password = $data['password'];
            if ($password == "")
                throw new Exception("新密码不能为空");
            DB::table("antoa_user")->where("id", $uid)->update([
                "password" => md5($password)
            ]);
            return [
                "status" => 1,
                "msg"    => "密码修改成功"
            ];
        } catch (Exception $e) {
            return [
                "status" => 0,
                "msg"    => $e->getMessage()
            ];
        }
    }
}
