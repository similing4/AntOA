<?php
/**
 * FileName:DBListOperator.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/4
 * Time:9:49
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use Illuminate\Database\Query\Builder;

abstract class DBListOperator {
    public $builder;

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function orderBy($column, $direction) {
        $this->builder->orderBy($column, $direction);
        return $this;
    }

    public function select($columns) {
        $this->builder->select($columns);
        return $this;
    }

    public function paginate($pageCount) {
        return $this->builder->paginate($pageCount);
    }

    public function first() {
        return $this->builder->first();
    }

    public function find($id) {
        return $this->builder->find($id);
    }

    public function delete($id) {
        return $this->builder->delete($id);
    }
}
