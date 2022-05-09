<?php
/**
 * FileName:ListRowButtonNavigateWithParamChange.php
 * Author:Shengxinyu
 * Email:845206213@qq.com
 * Date:2022/5/9/009
 * Time:10:54
 */
declare(strict_types=1);

namespace Modules\AntOA\Http\Utils\Model;

/**
 * NameSpace: Modules\AntOA\Http\Utils\Model
 * ClassName: ListRowButtonNavigateWithParamChange
 * 描述: 根据传入行的指定字段进行变化后进行页面跳转
 */
class ListRowButtonNavigateWithParamChange extends ListRowButtonNavigate {

    /**
     * @var string 行数据对应需要的传入列
     */
    private $destCol;

    /**
     * ListRowButtonNavigateNormal constructor.
     * @param string $baseUrl 按钮点击后需要跳转到或需要调用API的不带参数的URL
     * @param string $buttonText 按钮显示的文本
     * @param array $destColSourceColMap 目标页面的字段=>当前选中行的字段
     * @param string $buttonType 按钮类型
     */
    public function __construct($baseUrl, $buttonText, $destColSourceColMap, $buttonType = "primary") {
        parent::__construct($baseUrl, $buttonText, $buttonType);
        $this->destCol = $destColSourceColMap;
    }

    /**
     * @inheritDoc
     */
    public function calcButtonParam(UrlParamCalculator $calculator) {
        $ret = [];
        foreach ($this->destCol as $dest => $source)
            $ret[] = new UrlParamCalculatorParamItem($dest, $calculator->getRowParamByKey($source)->val);
        return $ret;
    }

    /**
     * @inheritDoc
     */
    public function judgeIsShow(UrlParamCalculator $calculator) {
        return true;
    }
}
