<?php
/**
 * FileName:ListRowButtonWithForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/6/1/001
 * Time:11:54
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonWithForm
 * 描述: 列表页每行调用API按钮实体类（要求确定后才会请求）
 * 注：被调用的API返回值应为JSON格式且包含status字段，status字段为1时客户端成功展示data字段，否则客户端展示msg字段
 */
abstract class ListRowButtonWithForm extends ListRowButtonBase {
    public $gridCreateForm;

    /**
     * ListRowButtonWithForm constructor.
     * @param $baseUrl
     * @param $buttonText
     * @param GridCreateFormEasy $gridCreateForm
     * @param string $buttonType
     */
    public function __construct($baseUrl, $buttonText, $gridCreateForm, $buttonType = "primary") {
        parent::__construct($baseUrl, $buttonText, $buttonType);
        $this->gridCreateForm = $gridCreateForm;
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type" => "ListRowButtonWithForm",
            "gridCreateForm" => $this->gridCreateForm
        ]);
    }
}
