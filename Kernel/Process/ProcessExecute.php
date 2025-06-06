<?php

namespace Kernel\Process;


use Application\ApplicationInterface;
use Dao\KernelDao\KernelDao;
use Kernel\Task\TaskCommon;
use Model\Kernel\KernelModel;

class ProcessExecute
{
    /**
     * @describe 执行任务
     * @param KernelModel $task 任务模型
     * @return void
     */
    public function handler(KernelModel $task)
    {
        $objKernelDao    = new KernelDao();
        $objKernelDao->initializeTask($task);

        // 实例并执行任务
        $objTaskInstance = new $task->execute_class();
        if ($objTaskInstance instanceof ApplicationInterface && !$objKernelDao->isFirstExecuteTask()) {
            // 保存执行模型
            TaskCommon::setTaskClass($task);
            call_user_func([$objTaskInstance, 'execute'], $task->execute_param ?: '');
        }

        $objKernelDao->complete($task);
    }
}