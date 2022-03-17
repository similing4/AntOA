<?php
/**
 * FileName:EditRowButtonBlob.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/17/017
 * Time:9:42
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;


use Modules\AntOA\Http\Utils\AbstractModel\EditRowButtonBase;

abstract class EditRowButtonBlob extends EditRowButtonBase {
    public $downloadFilename;

    /**
     * EditRowButtonBlob constructor.
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
        return array_merge(parent::jsonSerialize(), [
            "type"             => "EditRowButtonBlob",
            "downloadFilename" => $this->downloadFilename
        ]);
    }
}
