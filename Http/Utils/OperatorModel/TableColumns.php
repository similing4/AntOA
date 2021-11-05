<?php
/**
 * FileName:TableColumns.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/11/4
 * Time:9:58
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\OperatorModel;

use Exception;
use Illuminate\Support\Facades\DB;

class TableColumns {
    public $tableColumnsMap = [];

    /**
     * @param String $table 表名
     * @param array|String $columns 表对应需要的列的键值对数组，字符串时只能是*号
     * @return TableColumns
     * @throws Exception 如果表字段已经设置过了会直接抛出异常
     */
    public static function create($table, $columns) {
        return (new self())->table($table, $columns);
    }

    /**
     * @param $table
     * @param $columns
     * @return $this
     * @throws Exception
     */
    public function table($table, $columns) {
        if ($columns == "*") {
            $pre = config("database.connections.mysql.prefix");
            $columns = DB::select("DESC " . $pre . $table);
            $a = [];
            foreach ($columns as $column)
                $a[] = $column->Field;
            $this->table($table, $a);
            return $this;
        }
        foreach ($this->tableColumnsMap as $k => $vs) {
            foreach ($columns as $columnKey => $column)
                if (in_array($column, array_values($vs)))
                    throw new Exception("字段" . $column . "已存在于表" . $k . "中");
        }
        $this->tableColumnsMap[$table] = $columns;
        return $this;
    }

    public function getTables() {
        return array_keys($this->tableColumnsMap);
    }

    /**
     * @param $table
     * @return bool|mixed
     */
    public function getTableColumns($table) {
        if ($this->tableColumnsMap[$table])
            return $this->tableColumnsMap[$table];
        return false;
    }

    /**
     * @param $name
     * @return string|null
     */
    public function getTableNameFromColumn($name) {
        foreach ($this->tableColumnsMap as $table => $columns) {
            foreach ($columns as $k => $column)
                if ($column == $name)
                    return $table;
        }
        return null;
    }
}
