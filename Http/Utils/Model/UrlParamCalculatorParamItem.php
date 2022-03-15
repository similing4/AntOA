<?php
/**
 * FileName:UrlParamCalculatorParamItem.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:9:44
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: UrlParamCalculatorParamItem
 * 描述: 页面请求的单个参数实体表示
 */
class UrlParamCalculatorParamItem {
    /**
     * @var string 键
     */
    public $key;
    /**
     * @var string 值
     */
    public $val;

    /**
     * UrlParamCalculatorParamItem constructor.
     * @param string $key 参数键
     * @param string $val 参数值
     */
    public function __construct($key, $val) {
        $this->key = $key;
        $this->val = $val;
    }
}
