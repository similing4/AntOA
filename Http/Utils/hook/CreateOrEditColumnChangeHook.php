<?php
/**
 * FileName:CreateOrEditColumnChangeHook.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/17
 * Time:13:56
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\hook;


use JsonSerializable;
use Modules\AntOA\Http\Utils\Model\CreateOrEditColumnChangeHookReturnData;

/**
 * Interface CreateOrEditColumnChangeHook
 * @package Modules\AntOA\Http\Utils\hook
 * 创建页或编辑页指定数据变更钩子
 */
abstract class CreateOrEditColumnChangeHook implements JsonSerializable {
    /**
     * @var String 被监听的字段
     */
    public $col;

    /**
     * CreateOrEditColumnChangeHook constructor.
     * @param String $col 被监听的字段
     */
    public function __construct($col) {
        $this->col = $col;
    }

    /**
     * 创建页或编辑页指定数据变更钩子，页面初次加载时及指定行数据变更时回调。
     * @param array $formData 正在编辑的所有数据
     * @param array $pageParam 当前页的页面参数
     * @return CreateOrEditColumnChangeHookReturnData 返回数据的data域将被Object.assign到form中填充字段，而display域将指定要展示的列数组（要包括自身），如果display字段为null或false则不改变当前已有状态。
     */
    public abstract function hook(array $formData, array $pageParam);

    public function jsonSerialize() {
        return [
            "col" => $this->col
        ];
    }
}
