<?php
/**
 * FileName:ListTableColumnCollection.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:16:09
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\AbstractModel;


class ListTableColumnCollection {
    private $array = [];
    public function addItem(ListTableColumnBase $item){
        $this->array[] = $item;
    }
    /**
     * 获取所有列对象
     * @return array<ListTableColumnBase>
     */
    public function getItems(){
        return $this->array;
    }
}
