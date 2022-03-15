<?php
/**
 * FileName:ListRowButtonBlob.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:10:41
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

use Modules\AntOA\Http\Utils\AbstractModel\ListRowButtonBase;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonBlob
 * 描述: 列表页每行直接根据API下载文件（BLOB流）实体类
 */
abstract class ListRowButtonBlob extends ListRowButtonBase {
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
            "type"             => "ListRowButtonBlob",
            "buttonText"       => $this->buttonText,
            "buttonType"       => $this->buttonType,
            "baseUrl"          => $this->baseUrl,
            "finalUrl"         => $this->finalUrl,
            "downloadFilename" => $this->downloadFilename
        ];
    }
}
