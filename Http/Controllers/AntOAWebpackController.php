<?php
/**
 * FileName:AntOAWebpackController.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/8/19
 * Time:9:37
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Routing\Controller;


class AntOAWebpackController extends Controller {
    /**
     * 登录页面
     * @return string
     */
    public function index() {
        return file_get_contents(module_path('AntOA') . "/Resources/assets/webpack/index.html");
    }
}
