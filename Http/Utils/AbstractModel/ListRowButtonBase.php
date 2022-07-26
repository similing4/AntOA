<?php
/**
 * FileName:ListRowButtonBase.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:16:01
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use Illuminate\Http\Request;
use JsonSerializable;
use Modules\AntOA\Http\Utils\Model\GridCreateFormEasy;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculatorParamItem;

abstract class ListRowButtonBase implements JsonSerializable {
    public $buttonText;
    public $buttonType;
    public $baseUrl;
    public $finalUrl;

    /**
     * 构造函数
     * @param string $baseUrl 按钮点击后需要跳转到或需要调用API的不带参数的URL
     * @param string $buttonText 按钮显示的文本
     * @param string $buttonType 按钮类型
     */
    public function __construct($baseUrl, $buttonText, $buttonType = "primary") {
        $this->buttonText = $buttonText;
        $this->buttonType = $buttonType;
        $this->baseUrl = $baseUrl;
    }

    /**
     * 根据页面参数计算实际调用地址，返回值会被赋值到finalUrl中
     * @param UrlParamCalculator $calculator 传入的页面参数的实例
     * @return string 并入到baseURL的URL参数
     */
    public function calcButtonFinalUrl(UrlParamCalculator $calculator) {
        $param = [];
        foreach ($this->calcButtonParam($calculator) as $item)
            $param[] = $item->key . "=" . $item->val;
        return $this->baseUrl . "?" . join("&", $param);
    }

    /**
     * 根据页面参数计算实际调用地址，返回值将会被用作finalUrl参数
     * @param UrlParamCalculator $calculator 传入的页面参数的实例
     * @return array<UrlParamCalculatorParamItem> 并入到baseURL的URL参数
     */
    abstract public function calcButtonParam(UrlParamCalculator $calculator);

    /**
     * 根据页面参数计算当前按钮是否显示
     * @param UrlParamCalculator $calculator 传入的页面参数的实例
     * @return bool 返回真则显示，否则不显示
     */
    abstract public function judgeIsShow(UrlParamCalculator $calculator);

    public function jsonSerialize() {
        return [
            "buttonText" => $this->buttonText,
            "buttonType" => $this->buttonType,
            "baseUrl"    => $this->baseUrl,
            "finalUrl"   => $this->finalUrl
        ];
    }

    /**
     * 是否需要使用ApiDetailColumnList接口
     * @return bool 是否需要
     */
    public function isColumnNeedDealApiDetailColumnList(){
        return false;
    }

    /**
     * 是否需要使用ApiUpload接口
     * @return bool 是否需要
     */
    public function isColumnNeedApiUpload(){
        return false;
    }

    /**
     * @param Request $request 请求数据
     * @param string $uid 登录用户UID
     * @return string 返回给前端的json数据
     */
    public function dealApiDetailColumnList(Request $request, $uid){
        return json_encode([
            "status" => 0,
            "msg" => "接口不存在"
        ]);
    }
}
