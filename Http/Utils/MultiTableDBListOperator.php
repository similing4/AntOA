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
    private $mainTable = "";
    private $mainTableAlias = "";
    private $databasePrefix = "";
    private $joinTables = [];
    private $tableColumnsMap = [];
    private $primaryKey = "";

    /**
     * 用于列表页的左连接查询DBListOperator.
     * @param String $mainTable 主表名
     * @param String $mainTableAlias 主表别名
     * @param array<JoinTable> $joinTables 待查询的所有的表及对应关系
     * @param array<TableColumns> $tableColumnsMap 待查询的所有的表的字段对应关系
     * @throws Exception
     */
    public function __construct($mainTable, $mainTableAlias, $joinTables, $tableColumnsMap) {
        $this->mainTable = $mainTable;
        $this->mainTableAlias = $mainTableAlias;
        $this->databasePrefix = config("database.connections.mysql.prefix");
        $this->joinTables = $joinTables;
        $this->tableColumnsMap = $tableColumnsMap;
        $database = DB::selectOne("select database() as db")->db;
        $mainTableColumns = DB::selectOne("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '" . $database . "' AND TABLE_NAME = '" . $this->databasePrefix . $mainTable . "'");
        if (empty($mainTableColumns))
            throw new Exception("表" . $this->mainTable . "不存在或主键不存在");
        $this->primaryKey = $this->mainTableAlias . "." . $mainTableColumns->COLUMN_NAME;
        $builder = DB::table($this->mainTable . " as " . $this->mainTableAlias);
        foreach ($this->joinTables as $joinTable)
            $builder->leftJoin($joinTable->table . " as " . $joinTable->tableAlias, $joinTable->tableAlias . "." . $joinTable->id,
                "=", $joinTable->originTable . "." . $joinTable->originId);
        parent::__construct($builder);
    }

    /**
     * 根据传入的列名搜索对应的TableColumns
     * @param String $columnName 待搜索的列名
     * @return TableColumns|null 搜索到的TableColumns对象，如果不存在则返回null
     */
    private function getTableColumnsByColumnName($columnName) {
        foreach ($this->tableColumnsMap as $tableColumns) {
            if ($tableColumns->isTableColumnsContainsColumn($columnName))
                return $tableColumns;
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
        $tableColumns = $this->getTableColumnsByColumnName($column);
        if ($tableColumns)
            return parent::where($tableColumns->tableAlias . '.' . $tableColumns->tableColumnsMap[$column], $operator, $value, $boolean);
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
            $tableColumns = $this->getTableColumnsByColumnName($r);
            if ($tableColumns == null)
                throw new Exception("字段" . $r . "不存在");
            $needs [] = $tableColumns->tableAlias . "." . $tableColumns->tableColumnsMap[$r] . " as " . $r;
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
