<?php
/**
 * FileName:TreeNode.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/16/016
 * Time:11:28
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use JsonSerializable;

class TreeNode implements JsonSerializable {
    public $children = [];
    public $title;
    public $value;
    public $key;
    public $disabled;

    public function __construct($value, $title, $children = [], $disabled = false) {
        $this->value = $value;
        $this->key = $value;
        $this->title = $title;
        $this->children = $children;
        $this->disabled = $disabled;
    }

    public function jsonSerialize() {
        $ret = [
            "title" => $this->title,
            "value" => $this->value,
            "key"   => $this->key
        ];
        if ($this->disabled)
            $ret['disabled'] = $this->disabled;
        if (!empty($this->children))
            $ret['children'] = $this->children;
        return $ret;
    }

    public function addChild(TreeNode $node) {
        $this->children[] = $node;
    }
}
