<?php
/**
 * FileName:ListTableColumnPicture.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/14/014
 * Time:17:29
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\ListTableColumnBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListTableColumnPicture
 * 描述:图片列，将查出的数据当做图片url解析成图片展示出来
 */
class ListTableColumnPicture extends ListTableColumnBase {
    /**
     * @var string 图片宽度（单位由字符串自身指定，如10vw或100px等）
     */
    public $width;
    /**
     * @var string 图片高度（单位由字符串自身指定，如10vw或100px等）
     */
    public $height;

    /**
     * ListTableColumnPicture constructor.
     * @param string $col 列对应的字段
     * @param string $tip 在列表页该列的的表头名称
     * @param string $width 图片宽度（单位由字符串自身指定，如10vw或100px等）
     * @param string $height 图片高度（单位由字符串自身指定，如10vw或100px等）
     */
    public function __construct($col, $tip, $width, $height) {
        parent::__construct($col, $tip);
        $this->width = $width;
        $this->height = $height;
    }

    public function jsonSerialize() {
        return [
            "type"   => "ListTableColumnPicture",
            "col"    => $this->col,
            "tip"    => $this->tip,
            "width"  => $this->width,
            "height" => $this->height
        ];
    }
}
