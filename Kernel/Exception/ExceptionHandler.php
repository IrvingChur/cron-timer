<?php

namespace Kernel\Exception;

use Kernel\Logger\Logger;
use Library\Common\WarningLibrary;

class ExceptionHandler
{
    /**
     * @describe 异常处理
     * @param \Throwable $throwable 异常类
     * @return void
     */
    public function throwableHandler(\Throwable $throwable)
    {
        // 记录日志
        Logger::error('Exception', json_encode([
            'code'    => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'inFile'  => $throwable->getFile(),
            'onLine'  => $throwable->getLine(),
        ]));

        // 发送警报
        WarningLibrary::transmitWarning(
            '定时任务异常',
            $throwable->getMessage()
        );

        EXIT();
    }
}