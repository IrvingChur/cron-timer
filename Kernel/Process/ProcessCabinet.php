<?php

namespace Kernel\Process;


class ProcessCabinet
{
    /**
     * @describe 终止master进程
     * @return void
     */
    public function stopTheMaster()
    {
        $strLoggerPath = APP_PATH . DIRECTORY_SEPARATOR . 'Logger' . DIRECTORY_SEPARATOR . 'cron-timer.log';
        $strLogContent = file_get_contents($strLoggerPath);
        shell_exec("kill {$strLogContent}");
    }

    /**
     * @describe 记录master进程pid
     * @param int $masterPid 进程的pid
     * @return void
     */
    public function recordMasterPid(int $masterPid)
    {
        $strLoggerPath = APP_PATH . DIRECTORY_SEPARATOR . 'Logger';

        if (!is_dir($strLoggerPath)) {
            mkdir($strLoggerPath, 0777, true);
        }

        $sourFile = fopen($strLoggerPath . DIRECTORY_SEPARATOR . 'cron-timer.log', 'w');
        $strText = $masterPid;
        fwrite($sourFile, $strText);
        fclose($sourFile);
    }

    /**
     * @describe 处理命令
     * @param array $command 命令
     * @return void
     */
    public function handlerCommand(array $command)
    {
        $strModelType = $command[1];
        switch ($strModelType) {
            case 'debug' :
                $this->debugModel($command);
                DIE();
                break;
            case 'listen' :
                break;
            case 'daemon' :
                \swoole_process::daemon(false, false);
                break;
            case 'stop' :
                (new ProcessCabinet())->stopTheMaster();
                DIE();
                break;
            default :
                $sourStdout = fopen("php://stdout", "w");
                fwrite($sourStdout, "无效指令" . PHP_EOL);
                fclose($sourStdout);
                DIE();
                break;
        }
    }

    /**
     * @describe 调试模式
     * @param array $command 命令
     * @return void
     */
    protected function debugModel(array $command)
    {
        $strClass = $command[2];
        $strParam = $command[3];

        if (empty($strClass)) {
            $sourStdout = fopen("php://stdout", "w");
            fwrite($sourStdout, "Application不能为空" . PHP_EOL);
            fclose($sourStdout);
            DIE();
        }

        call_user_func([(new $strClass()), 'execute'], $strParam ?: '');
    }
}