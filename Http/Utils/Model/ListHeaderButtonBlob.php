<?php
/**
 * FileName:ListHeaderButtonBlob.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:10:39
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListHeaderButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListHeaderButtonBlob
 * 描述: 列表页头部直接根据API下载文件（BLOB流）实体类
 */
abstract class ListHeaderButtonBlob extends ListHeaderButtonBase {
    public $downloadFilename;

    /**
     * ListHeaderButtonBlob constructor.
     * @param $baseUrl
     * @param $buttonText
     * @param $downloadFilename
     * @param string $buttonType
     */
    public function __construct($baseUrl, $buttonText, $downloadFilename, $buttonType = "primary") {
        parent::__construct($baseUrl, $buttonText, $buttonType);
        $this->downloadFilename = $downloadFilename;
    }

    public function jsonSerialize() {
        return [
            "type"             => "ListHeaderButtonBlob",
            "buttonText"       => $this->buttonText,
            "buttonType"       => $this->buttonType,
            "baseUrl"          => $this->baseUrl,
            "finalUrl"         => $this->finalUrl,
            "downloadFilename" => $this->downloadFilename
        ];
    }
}
