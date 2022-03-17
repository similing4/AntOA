<?php
/**
 * FileName:CreateOrEditColumnChangeHookReturnData.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:22:24
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


class CreateOrEditColumnChangeHookReturnData {
    /**
     * @var array 键为form表单的列名，值为将被覆盖为的值
     */
    public $data;
    /**
     * @var array 包含所有需要显示的表单列名，如果为空数组或空值null或者为false时则不改变当前已有状态
     */
    public $display;

    /**
     * CreateOrEditColumnChangeHookReturnData constructor.
     * @param array $data 键为form表单的列名，值为将被覆盖为的值
     * @param array $display 包含所有需要显示的表单列名，如果为空数组或空值null或者为false时则不改变当前已有状态
     */
    public function __construct($data, $display) {
        $this->data = $data;
        $this->display = $display;
    }
}
