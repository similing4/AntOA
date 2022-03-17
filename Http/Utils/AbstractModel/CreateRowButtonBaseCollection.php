<?php
/**
 * FileName:CreateRowButtonBaseCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:14:31
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;

class CreateRowButtonBaseCollection implements JsonSerializable {
    private $array = [];

    public function addItem(CreateRowButtonBase $item) {
        $this->array[] = $item;
    }

    public function removeItems(array $items) {
        $this->array = array_filter($this->array, function ($item) use ($items) {
            return !in_array($item, $items);
        });
    }

    /**
     * 获取所有行按钮对象
     * @return array<CreateRowButtonBase>
     */
    public function getItems() {
        return $this->array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
