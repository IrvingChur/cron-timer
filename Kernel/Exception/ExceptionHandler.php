<?php

namespace Kernel\Exception;

use Kernel\Logger\Logger;
use Kernel\Task\TaskCommon;
use Library\Common\WarningLibrary;
use Library\Common\ConfigureLibrary;
use Configure\SystemConfigure;

class ExceptionHandler
{
    /**
     * @describe 异常处理
     * @param \Throwable $throwable 异常类
     * @return void
     * @throws \Throwable
     */
    public function throwableHandler(\Throwable $throwable)
    {
        // 如果是本地开发,则直接抛出异常
        $strEnv = ConfigureLibrary::getConfigure(SystemConfigure::class)["env"];
        if ($strEnv == "local") {
            throw $throwable;
        }

        // 获取执行异常的任务模型
        $objTask         = TaskCommon::getTaskClass();
        $strTaskDescribe = $objTask->describe;

        // 记录日志
        Logger::error('Exception', json_encode([
            'code'    => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'inFile'  => $throwable->getFile(),
            'onLine'  => $throwable->getLine(),
            'taskDescribe' => $strTaskDescribe ?: '计划任务主进程'
        ]));

        // 发送警报
        WarningLibrary::transmitWarning(
            '定时任务异常',
            json_encode([
                'code'    => $throwable->getCode(),
                'message' => $throwable->getMessage(),
                'inFile'  => $throwable->getFile(),
                'onLine'  => $throwable->getLine(),
                'taskDescribe' => $strTaskDescribe ?: '计划任务主进程'
            ])
        );
    }
}