<?php
namespace Modules\AntOA\Http\Utils\Qiniu\Storage;

use Modules\AntOA\Http\Utils\Qiniu\Auth;
use Modules\AntOA\Http\Utils\Qiniu\Config;
use Modules\AntOA\Http\Utils\Qiniu\Zone;
use Modules\AntOA\Http\Utils\Qiniu\Http\Client;
use Modules\AntOA\Http\Utils\Qiniu\Http\Error;

/**
 * 主要涉及了鉴黄接口的实现，具体的接口规格可以参考
 *
 * @link https://developer.qiniu.com/dora/manual/3674/kodo-product-introduction
 */
final class ArgusManager
{
    private $auth;
    private $config;

    public function __construct(Auth $auth, Config $config = null)
    {
        $this->auth = $auth;
        if ($config == null) {
            $this->config = new Config();
        } else {
            $this->config = $config;
        }
    }

    /**
     * 视频鉴黄
     *
     * @param $body     body信息
     * @param $vid      videoID
     *
     * @return mixed      成功返回NULL，失败返回对象Modules\AntOA\Http\Utils\Qiniu\Http\Error
     * @link  https://developer.qiniu.com/dora/manual/4258/video-pulp
     */
    public function pulpVideo($body, $vid)
    {
        $path = '/v1/video/' . $vid;

        return $this->arPost($path, $body);
    }

    private function getArHost()
    {
        $scheme = "http://";
        if ($this->config->useHTTPS == true) {
            $scheme = "https://";
        }
        return $scheme . Config::ARGUS_HOST;
    }

    private function arPost($path, $body = null)
    {
        $url = $this->getArHost() . $path;
        return $this->post($url, $body);
    }

    private function post($url, $body)
    {
        $headers = $this->auth->authorizationV2($url, 'POST', $body, 'application/json');
        $headers['Content-Type']='application/json';
        $ret = Client::post($url, $body, $headers);
        if (!$ret->ok()) {
            print($ret->statusCode);
            return array(null, new Error($url, $ret));
        }
        $r = ($ret->body === null) ? array() : $ret->json();
        return array($r, null);
    }
}
