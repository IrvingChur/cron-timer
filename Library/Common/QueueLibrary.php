<?php

namespace Library\Common;

use Illuminate\Support\Str;
use Kernel\Exception\ExceptionHandler;
use Library\Cache\Cache;


class QueueLibrary
{
    /**
     * @describe 调用website项目的任务列表
     * @param string $handle    任务名称
     * @param array  $parameter 参数
     * @return void
     */
    public static function websiteQueue(string $handle, array $parameter)
    {
        try {
            $env               = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
            $strCacheName      = "queues:" . $env . "_queue";
            $objQueueStd       = new \stdClass();
            $objQueueStd->job  = $handle;
            $objQueueStd->data = $parameter;
            $objQueueStd->id   = Str::random(32);;
            $objQueueStd->attempts = 1;
            Cache::getInstance()->LPUSH($strCacheName, json_encode($objQueueStd));
        } catch (\Throwable $throwable) {
            self::getInstance(ExceptionHandler::class)->throwableHandler($throwable);
        }

    }
}