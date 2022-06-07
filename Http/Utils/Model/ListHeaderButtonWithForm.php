<?php
/**
 * FileName:ListHeaderButtonWithForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/6/7/007
 * Time:10:19
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;

abstract class ListHeaderButtonWithForm extends ListHeaderButtonBase {
    public $gridCreateForm;

    /**
     * ListHeaderButtonWithForm constructor.
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
            "type"           => "ListHeaderButtonWithForm",
            "gridCreateForm" => $this->gridCreateForm
        ]);
    }

}
