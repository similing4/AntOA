<?php
/**
 * FileName:FormButtonApiUrlReturnJSON.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/17/017
 * Time:10:04
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: FormButtonApiUrlReturnJSON
 * 描述: 编辑或创建页面API按钮返回值实例（BLOB除外）
 */
class FormButtonApiUrlReturnJSON {
    public $form;
    public $msg;
    public $displayColumns;

    /**
     * FormButtonApiUrlReturnJSON constructor.
     * @param string $msg 返回提示参数，如果为空串则不提示
     * @param array<string,string> $form 赋值到表单上的键值对数组
     * @param array $displayColumns 变更显示的所有列，如果不改变则可以传空数组
     */
    public function __construct($msg = "", array $form = [], array $displayColumns = []) {
        $this->msg = $msg;
        $this->form = $form;
        $this->displayColumns = $displayColumns;
    }
}
