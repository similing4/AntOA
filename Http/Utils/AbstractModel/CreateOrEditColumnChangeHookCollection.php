<?php
/**
 * FileName:CreateOrEditColumnChangeHookCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:16:48
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;
use Modules\AntOA\Http\Utils\hook\CreateOrEditColumnChangeHook;

class CreateOrEditColumnChangeHookCollection implements JsonSerializable {
    private $array = [];

    public function addItem(CreateOrEditColumnChangeHook $item) {
        $this->array[] = $item;
    }

    /**
     * 获取所有行按钮对象
     * @return array<CreateOrEditColumnChangeHook>
     */
    public function getItems() {
        return $this->array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
