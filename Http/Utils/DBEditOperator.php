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

    public function onUpdate($primaryKey, $param) {
        return $this->builder->where($primaryKey, $param[$primaryKey])->update($param);
    }
}
