<?php

namespace Modules\AntOA\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * NameSpace: Modules\AntOA\Console
 * ClassName: CreateManager
 * 描述: 根据数据库字段与表自动生成控制器的命令行生成器，还在开发阶段
 */
class CreateManager extends Command {
    /**
     * 命令行名称.
     *
     * @var string
     */
    protected $name = 'antoa:create';

    /**
     * 命令行描述内容.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * 创建一个新的命令行实例.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 执行命令行.
     *
     * @return mixed
     */
    public function handle() {
        //
    }

    /**
     * 获取命令行参数.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * 获取命令行配置信息.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
