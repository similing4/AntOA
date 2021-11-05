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
    public $id;
    public $originTable;
    public $originId;

    public function __construct($table, $id, $originTable, $originId) {
        $this->table = $table;
        $this->id = $id;
        $this->originTable = $originTable;
        $this->originId = $originId;
    }
}
