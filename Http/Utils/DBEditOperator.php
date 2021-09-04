<?php
/**
 * FileName:DBEditOperator.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/4
 * Time:10:28
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use Illuminate\Database\Query\Builder;

class DBEditOperator {
    public $builder;

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }

    public function find($id) {
        return $this->builder->find($id);
    }

    public function onUpdate($columns, $param) {
        return $this->builder->where($columns[0]['col'], $param[$columns[0]['col']])->update($param);
    }
}
