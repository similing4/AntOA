<?php
/**
 * FileName:ListHeaderBlobButtonWithForm.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2024/3/4
 * Time:21:34
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


abstract class ListHeaderBlobButtonWithForm extends ListHeaderButtonWithForm {
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "type"           => "ListHeaderBlobButtonWithForm",
            "gridCreateForm" => $this->gridCreateForm
        ]);
    }
}
