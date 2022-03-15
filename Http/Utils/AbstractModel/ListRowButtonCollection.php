<?php
/**
 * FileName:ListRowButtonCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:16:08
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;

use JsonSerializable;

class ListRowButtonCollection implements JsonSerializable {
    private $array = [];

    public function addItem(ListRowButtonBase $item) {
        $this->array[] = $item;
    }

    /**
     * 获取所有行按钮对象
     * @return array<ListRowButtonBase>
     */
    public function getItems() {
        return $this->array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
