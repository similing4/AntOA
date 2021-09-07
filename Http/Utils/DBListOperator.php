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
    public $builder; //DB类产生的对象，于构造方法中传入

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }
    
    //where方法，设置的对应column会作为条件传入。你可以根据column自定义设置传入条件内容
    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

    //orderBy方法，你可以在这里自定义设置排序规则。
    public function orderBy($column, $direction) {
        $this->builder->orderBy($column, $direction);
        return $this;
    }

    //select方法，如果你有连接查询你可以在这里将查询字段格式化为正确的字段解决冲突。
    public function select($columns) {
        $this->builder->select($columns);
        return $this;
    }

    //分页方法，不建议直接重写本方法，建议直接通过hook修改结果。
    public function paginate($pageCount) {
        return $this->builder->paginate($pageCount);
    }

    //当编辑页或创建页使用column为COLUMN_CHILDREN_CHOOSE类型时，extra需要使用本方法。
    public function first() {
        return $this->builder->first();
    }

    //detail判断、删除时判断
    public function find($id) {
        return $this->builder->find($id);
    }

    //删除时进行的操作，除了重写这里之外，你也可以直接重写AntOAController的delete方法
    public function delete($id) {
        return $this->builder->delete($id);
    }
}