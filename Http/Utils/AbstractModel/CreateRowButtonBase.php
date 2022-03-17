<?php
/**
 * FileName:CreateRowButtonBase.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:14:15
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;
use Modules\AntOA\Http\Utils\Model\FormButtonApiUrlReturnJSON;
use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;

abstract class CreateRowButtonBase implements JsonSerializable {
    public $bindCol;
    public $apiUrl;
    public $buttonText;
    public $buttonType;

    /**
     * 根据FormButtonApiUrlReturnJSON对象生成按钮需要的标准JSON数据
     * @param FormButtonApiUrlReturnJSON $ret
     * @return string 标准JSON数据
     */
    public static function generalApiUrlReturnJSON(FormButtonApiUrlReturnJSON $ret) {
        return json_encode([
            "status"         => 1,
            "data"           => $ret->form,
            "msg"            => $ret->msg,
            "displayColumns" => $ret->displayColumns
        ]);
    }

    /**
     * 构造函数
     * @param string $bindCol 按钮在编辑页所处行的列名
     * @param string $apiUrl 按钮点击后需要调用的不带参数的API地址。或者是直接的跳转页面的地址，受浏览器限制，不支持根据页面参数拼接跳转的页面地址
     * @param string $buttonText 按钮显示的文本
     * @param string $buttonType 按钮类型
     */
    public function __construct($bindCol, $apiUrl, $buttonText, $buttonType = "primary") {
        $this->bindCol = $bindCol;
        $this->apiUrl = $apiUrl;
        $this->buttonText = $buttonText;
        $this->buttonType = $buttonType;
    }

    /**
     * 根据页面参数计算当前按钮是否显示（只能获取到页面参数）
     * @param UrlParamCalculator $calculator 传入的页面参数的实例
     * @return bool 返回真则显示，否则不显示
     */
    abstract public function judgeIsShow(UrlParamCalculator $calculator);

    public function jsonSerialize() {
        return [
            "bindCol"    => $this->bindCol,
            "apiUrl"     => $this->apiUrl,
            "buttonText" => $this->buttonText,
            "buttonType" => $this->buttonType
        ];
    }
}
