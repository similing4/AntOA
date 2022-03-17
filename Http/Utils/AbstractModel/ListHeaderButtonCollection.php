<?php
/**
 * FileName:ListHeaderButtonCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:16:08
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;

use JsonSerializable;

class ListHeaderButtonCollection implements JsonSerializable {
    private $array = [];

    public function addItem(ListHeaderButtonBase $item) {
        $this->array[] = $item;
    }

    public function removeItems(array $items) {
        $this->array = array_filter($this->array, function ($item) use ($items) {
            return !in_array($item, $items);
        });
    }

    /**
     * 获取所有行按钮对象
     * @return array<ListHeaderButtonBase>
     */
    public function getItems() {
        return $this->array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
