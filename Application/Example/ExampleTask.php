<?php

namespace Application\Example;

use Application\ApplicationInterface;

class ExampleTask implements ApplicationInterface
{
    public function execute(string $param): void
    {
        echo "示例任务执行中...\n";
        echo "参数: {$param}\n";
        echo "执行时间: " . date('Y-m-d H:i:s') . "\n";
        
        // 这里可以添加你的业务逻辑
        // 比如数据处理、API调用、文件操作等
        
        echo "任务执行完成\n";
    }
}