<?php

namespace Kernel\Process;


use Dao\KernelDao\KernelDao;
use Illuminate\Database\Capsule\Manager as DB;
use Kernel\Eloquent\Eloquent;
use Kernel\Exception\ExceptionHandler;
use Library\Common\ConfigureLibrary;
use Library\Common\WarningLibrary;
use Model\Kernel\KernelModel;

class ProcessKernel
{
    const CRON_TASK_PROCESS_NAME_FORMAT = '_cron_task_worker_%d';

    protected $intMasterPid;

    public function __construct()
    {
        $this->intMasterPid = posix_getpid();
    }

    public function run(KernelModel $task)
    {
        $objProcess = new \swoole_process(function (\swoole_process $objWorker) use($task) {
            $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
            \swoole_set_process_name(sprintf($strEnv . self::CRON_TASK_PROCESS_NAME_FORMAT, $task->id));
            $this->checkMasterProcess($objWorker);
            (new Eloquent())->initialize(true);
            try {
                call_user_func([(new ProcessExecute()), 'handler'], $task);
            } catch (\Throwable $throwable) {
                (new ExceptionHandler())->throwableHandler($throwable);
            }
        });

        $objProcess->start();
    }

    public function detection()
    {
        DB::transaction(function () {
            $objKernelDao = new KernelDao();
            $arrRunningTasks = $objKernelDao->getRunningTasks();
            $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];

            foreach ($arrRunningTasks as $item) {
                $strProcessName = sprintf($strEnv . self::CRON_TASK_PROCESS_NAME_FORMAT, $item->id);
                $strShellResult = shell_exec("ps -ef | grep \"{$strProcessName}\" | grep -v \"grep\"");

                if (empty($strShellResult)) {
                    $strTitle   = "定时任务异常";
                    $strMessage = "定时任务ID：{$item->id} - {$item->describe} - 运行异常，请及时处理";
                    WarningLibrary::transmitWarning($strTitle, $strMessage);
                    // 自动复位
                    $objKernelDao->complete($item);
                }
            }
        });
    }

    protected function checkMasterProcess($objWorker)
    {
        if (!\swoole_process::kill($this->intMasterPid, 0)) {
            $objWorker->exit();
        }
    }

    public function processWait()
    {
        while ($arrWorkerId = \swoole_process::wait(false)) {

        }
    }
}