<?php
/**
 * FileName:DBCreateOperator.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/9/4
 * Time:10:13
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use Illuminate\Database\Query\Builder;

abstract class DBCreateOperator {
    public $builder;

    public function __construct(Builder $builder) {
        $this->builder = $builder;
    }

    public function insert(array $values) {
        return $this->builder->insert($values);
    }
}
