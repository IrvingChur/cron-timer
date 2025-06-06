<?php

namespace Kernel\Task;


use Model\Kernel\KernelModel;

class TaskCommon
{
    protected static $objTaskClass;

    /**
     * @describe 设置任务名称
     * @param string $taskClass 任务名称
     * @return void
     */
    public static function setTaskClass(KernelModel $taskClass)
    {
        self::$objTaskClass = $taskClass;
    }

    /**
     * @describe 获取任务名称
     * @return string
     */
    public static function getTaskClass()
    {
        return self::$objTaskClass;
    }
}