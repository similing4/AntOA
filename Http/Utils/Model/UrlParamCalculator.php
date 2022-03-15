<?php
/**
 * FileName:UrlParamCalculator.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:9:41
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: UrlParamCalculator
 * 描述: 使用页面跳转按钮时前端页面参数实体
 */
class UrlParamCalculator {
    /**
     * @var array<UrlParamCalculatorParamItem> 跳转时对应的页面参数
     */
    public $pageParam = [];
    /**
     * @var array<UrlParamCalculatorParamItem> 跳转时对应的行参数
     */
    public $rowParam = [];

    /**
     * UrlParamCalculator constructor.
     * @param array<UrlParamCalculatorParamItem> $pageParam 页面参数
     * @param array<UrlParamCalculatorParamItem> $rowParam 行参数（只有是行按钮时需要传入）
     */
    public function __construct($pageParam = [], $rowParam = []) {
        $this->pageParam = $pageParam;
        $this->rowParam = $rowParam;
    }

    /**
     * 根据键查找对应的页面参数键值对对象
     * @param string $key 键
     * @return UrlParamCalculatorParamItem|null 找到了参数返回对应的UrlParamCalculatorParamItem对象，否则返回null
     */
    public function getPageParamByKey($key){
        foreach ($this->pageParam as $r){
            if($key == $r->key)
                return $r;
        }
        return null;
    }

    /**
     * 根据键查找对应的行参数键值对对象
     * @param string $key 键
     * @return UrlParamCalculatorParamItem|null 找到了参数返回对应的UrlParamCalculatorParamItem对象，否则返回null
     */
    public function getRowParamByKey($key){
        foreach ($this->rowParam as $r){
            if($key == $r->key)
                return $r;
        }
        return null;
    }
}
