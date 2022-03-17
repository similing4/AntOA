<?php
/**
 * FileName:CascaderNode.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:14:53
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use JsonSerializable;

class CascaderNode implements JsonSerializable {
    public $children = [];
    public $label;
    public $value;
    public $disabled;

    public function __construct($value, $label, $children = [], $disabled = false) {
        $this->value = $value;
        $this->label = $label;
        $this->children = $children;
        $this->disabled = $disabled;
    }

    public function jsonSerialize() {
        $ret = [
            "label" => $this->label,
            "value" => $this->value
        ];
        if ($this->disabled)
            $ret['disabled'] = $this->disabled;
        if (!empty($this->children))
            $ret['children'] = $this->children;
        return $ret;
    }

    public function addChild(CascaderNode $node) {
        $this->children[] = $node;
    }
}
