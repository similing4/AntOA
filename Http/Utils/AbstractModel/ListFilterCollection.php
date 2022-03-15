<?php
/**
 * FileName:ListFilterCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:16:06
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


class ListFilterCollection {
    private $array = [];
    public function addItem(ListFilterBase $item){
        $this->array[] = $item;
    }

    /**
     * 获取所有筛选项对象
     * @return array<ListFilterBase>
     */
    public function getItems(){
        return $this->array;
    }
}
