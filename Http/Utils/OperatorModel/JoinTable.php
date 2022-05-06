<?php
/**
 * FileName:JoinTable.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2021/11/4
 * Time:9:53
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\OperatorModel;


class JoinTable {
    public $table;
    public $tableAlias;
    public $id;
    public $originTable;
    public $originId;

    /**
     * 创建一个左连接表
     * @param String $table 被连接的表名
     * @param String $alias 被连接的表别名
     * @param String $id 被连接的表的外键
     * @param String $originTable 被连接表的外键指向的表别名
     * @param String $originId 被连接表的外键指向的表键
     */
    public function __construct($table, $alias, $id, $originTable, $originId) {
        $this->table = $table;
        $this->tableAlias = $alias;
        $this->id = $id;
        $this->originTable = $originTable;
        $this->originId = $originId;
    }
}
