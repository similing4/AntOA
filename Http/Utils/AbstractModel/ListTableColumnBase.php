<?php
/**
 * FileName:ListTableColumnBase.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:15:59
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use Modules\AntOA\Http\Utils\Model\UrlParamCalculator;
use JsonSerializable;
use phpDocumentor\Reflection\Types\Boolean;

abstract class ListTableColumnBase implements JsonSerializable {
    /**
     * @var String 列对应的字段
     */
    public $col;
    /**
     * @var String 列对应的字段Label
     */
    public $tip;

    /**
     * 表列构造方法基类
     * @param String $col 列对应的字段
     * @param String $tip 列对应的字段Label
     */
    public function __construct($col, $tip) {
        $this->col = $col;
        $this->tip = $tip;
    }

    /**
     * 实例对象序列化方法，子类需重写该方法以确定给前端页面的json对象
     * @return array JSON对应的PHP数组
     */
    public function jsonSerialize() {
        return [
            "col" => $this->col,
            "tip" => $this->tip
        ];
    }

    /**
     * 是否是展示类型的实例，默认不是。如果是展示类型的实例，那么查询时将不对该实例对应字段进行查询
     */
    public function isTypeDisplay(){
        return false;
    }

    /**
     * 当列表页接口查询出结果后，可以重写该方法对对应控制的字段内容进行修改，比如可以通过这个方法将数字1分变成0.01元
     * @param array $searchResultItem 查询结果对应的行数据，可通过 $searchResultItem[$this->col] 直接获取并修改响应值，当然你也可以额外配置一些特殊字段。
     * @param UrlParamCalculator $urlParamCalculator 页面参数实例
     * @param string $uid 当前登录用户的UID
     */
    public function onParse(&$searchResultItem, UrlParamCalculator $urlParamCalculator, $uid){
    }
}
