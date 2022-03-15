<?php
/**
 * FileName:ListRowButtonNavigateNormal.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/3/15/015
 * Time:14:06
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonNavigateNormal
 * 描述: 根据传入行的指定字段进行页面跳转
 */
class ListRowButtonNavigateNormal extends ListRowButtonNavigate {
    /**
     * @var string 行数据对应需要的传入列
     */
    private $destCol;

    /**
     * ListRowButtonNavigateNormal constructor.
     * @param string $baseUrl 按钮点击后需要跳转到或需要调用API的不带参数的URL
     * @param string $buttonText 按钮显示的文本
     * @param string $destCol 行数据对应需要的传入列
     * @param string $buttonType 按钮类型
     */
    public function __construct($baseUrl, $buttonText, $destCol, $buttonType = "primary") {
        parent::__construct($baseUrl, $buttonText, $buttonType);
        $this->destCol = $destCol;
    }

    /**
     * @inheritDoc
     */
    public function calcButtonParam(UrlParamCalculator $calculator) {
        return [$calculator->getRowParamByKey($this->destCol)];
    }

    /**
     * @inheritDoc
     */
    public function judgeIsShow(UrlParamCalculator $calculator) {
        return true;
    }
}
