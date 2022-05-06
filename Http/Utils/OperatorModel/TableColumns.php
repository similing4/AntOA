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
    public $table; // 需要的被查询表名
    public $tableAlias; // 需要的被查询表别名
    public $tableColumnsMap = []; //需要查询的值对应的 查询结果名=>原名

    /**
     * @param String $table 表名
     * @param String $alias 表别名
     * @param array|String $columns 表对应需要的列的 查询结果名=>原名 键值对数组，字符串时只能是*号
     * @return TableColumns
     * @throws Exception 如果表字段已经设置过了会直接抛出异常
     */
    public function __construct($table, $alias, $columns) {
        $this->table = $table;
        $this->tableAlias = $alias;
        return $this->table($columns);
    }

    /**
     * 对传入的列进行验证并填充
     * @param $columns
     * @return $this
     * @throws Exception
     */
    public function table($columns) {
        $table = $this->table;
        if ($columns == "*") {
            $pre = config("database.connections.mysql.prefix");
            $columns = DB::select("DESC " . $pre . $table);
            $a = [];
            foreach ($columns as $column)
                $a[$column->Field] = $column->Field;
            $this->table($a);
            return $this;
        }
        $this->tableColumnsMap = $columns;
        return $this;
    }

    /**
     * 判断别名字段是否在该查询结果中
     * @param string $aliasName 别名字段
     * @return boolean 存在返回真，否则返回假
     */
    public function isTableColumnsContainsColumn($aliasName) {
        return array_key_exists($aliasName, $this->tableColumnsMap);
    }
}
