<?php
/**
 * FileName:EditColumnCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:12:00
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


use JsonSerializable;

class EditColumnCollection implements JsonSerializable {
    private $array = [];

    public function addItem(EditColumnBase $item) {
        $this->array[] = $item;
    }

    /**
     * 获取编辑页项对象
     * @return array<EditColumnBase>
     */
    public function getItems() {
        return $this->array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
