<?php

namespace Kernel\Process;


use Application\ApplicationInterface;
use Dao\KernelDao\KernelDao;
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
            call_user_func([$objTaskInstance, 'execute'], $task->execute_param ?: '');
        }

        // 协程阻塞
        while (count(\Swoole\Coroutine::listCoroutines()) > 1) {
            \Swoole\Coroutine::sleep(1);
        }

        $objKernelDao->complete($task);
    }
}