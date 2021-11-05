<?php
/**
 * FileName:MultiTableDBListOperator.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/11/4
 * Time:9:40
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils;


use Exception;
use Illuminate\Support\Facades\DB;
use Modules\AntOA\Http\Utils\OperatorModel\JoinTable;
use Modules\AntOA\Http\Utils\OperatorModel\TableColumns;

class MultiTableDBListOperator extends DBListOperator {
    private $joinTables = [];
    private $tableColumnsMap = [];
    private $mainTable = "";
    private $primaryKey = "";

    /**
     * 用于列表页的左连接查询DBListOperator.
     * @param String $mainTable 主表名
     * @param array<JoinTable> $joinTables 待查询的所有的表及对应关系
     * @param array<TableColumns> $tableColumnsMap 待查询的所有的表的字段对应关系
     * @throws Exception
     */
    public function __construct($mainTable, $joinTables, $tableColumnsMap) {
        $database = DB::selectOne("select database() as db")->db;
        $pre = config("database.connections.mysql.prefix");
        $this->mainTable = $mainTable;
        $mainTableColumns = DB::selectOne("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '" . $database . "' AND TABLE_NAME = '" . $pre . $mainTable . "'");
        if (empty($mainTableColumns))
            throw new Exception("表" . $mainTable . "不存在或主键不存在");
        $this->primaryKey = $mainTable . "." . $mainTableColumns->COLUMN_NAME;
        $this->joinTables = $joinTables;
        $this->tableColumnsMap = $tableColumnsMap;
        $builder = DB::table($mainTable);
        foreach ($this->joinTables as $joinTable)
            $builder->leftJoin($joinTable->table, $joinTable->table . "." . $joinTable->id,
                "=", $joinTable->originTable . "." . $joinTable->originId);
        parent::__construct($builder);
    }

    /**
     * 根据传入的列名搜索对应的表名
     * @param String $columnName 待搜索的列名
     * @return String|null 搜索到的表名，如果不存在则返回null
     */
    private function getTableNameByColumnName($columnName) {
        foreach ($this->tableColumnsMap as $tableColumns) {
            $table = $tableColumns->getTableNameFromColumn($columnName);
            if ($table)
                return $table;
        }
        return null;
    }

    /**
     * 重写父类where方法，改为分表where
     * @param string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return MultiTableDBListOperator
     * @throws Exception
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $table = $this->getTableNameByColumnName($column);
        if ($table)
            return parent::where($table . '.' . $column, $operator, $value, $boolean);
        throw new Exception("列" . $column . "不存在，请在tableColumnsMap中配置！");
    }

    /**
     * 重写父类方法，改为分表select
     * @param $columns
     * @return MultiTableDBListOperator
     * @throws Exception 字段不存在时报错
     */
    public function select($columns) {
        $needs = [];
        foreach ($columns as $r) {
            $table = $this->getTableNameByColumnName($r);
            if ($table == null)
                throw new Exception("字段" . $r . "不存在");
            $needs [] = $table . "." . $r . " as " . $r;
        }
        return parent::select($needs);
    }

    public function find($id) {
        return $this->builder->where($this->primaryKey, $id)->first();
    }

    public function setPrimaryId($table, $column) {
        $this->primaryKey = $table . "." . $column;
        return $this;
    }
}
