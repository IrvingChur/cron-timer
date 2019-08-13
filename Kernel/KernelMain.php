<?php

namespace Kernel;


use Dao\KernelDao\KernelDao;
use Illuminate\Database\Capsule\Manager as DB;
use Kernel\Eloquent\Eloquent;
use Kernel\Process\ProcessCabinet;
use Kernel\Process\ProcessKernel;
use Kernel\Register\RegisterInterface;
use Library\Common\DirectoryLibrary;

class KernelMain
{
    public function __construct()
    {
        $this->registerAutoLoading();
        $this->registerOther();
        (new Eloquent())->initialize();
    }

    /**
     * @describe 注册自动加载
     * @return void
     */
    protected function registerAutoLoading()
    {
        spl_autoload_register(function (string $class) {
            $arrClassExplode = explode('\\', $class);
            $strClass = implode(DIRECTORY_SEPARATOR, $arrClassExplode);
            $strClass = dirname(__DIR__) . DIRECTORY_SEPARATOR . $strClass . '.php';

            if (file_exists($strClass)) {
                require_once $strClass;
            }
        });
    }

    /**
     * @describe 注册其它组件
     * @return void
     */
    protected function registerOther()
    {
        $strRegisterPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Kernel' . DIRECTORY_SEPARATOR . 'Register';
        $arrFiles        = DirectoryLibrary::foreachDirectory($strRegisterPath);

        foreach ($arrFiles as $item) {
            $arrFileExplode   = explode(DIRECTORY_SEPARATOR, $item);
            $arrPathExplode   = explode(DIRECTORY_SEPARATOR, dirname(__DIR__));
            $arrClass         = array_diff($arrFileExplode, $arrPathExplode);
            $strRegisterClass = implode('\\', $arrClass);
            $strRegisterClass = substr($strRegisterClass, 0, strlen($strRegisterClass) - 4);

            // 加载并注册组件
            if (class_exists($strRegisterClass)) {
                $objRegisterClassInstance = new $strRegisterClass();
                if ($objRegisterClassInstance instanceof RegisterInterface) {
                    call_user_func([(new $strRegisterClass), 'register']);
                }
            }
        }
    }

    /**
     * @describe 处理执行参数
     * @param array $argv 执行参数
     * @return void
     */
    protected function handlerArgv(array $argv)
    {
        if (!isset($argv[1])) {
            $sourStdout = fopen("php://stdout", "w");
            fwrite($sourStdout, "================================================================================" . PHP_EOL);
            fwrite($sourStdout, "Usage: php Main.php debug|listen|daemon|stop" . PHP_EOL);
            fwrite($sourStdout, "================================================================================" . PHP_EOL);
            fwrite($sourStdout, "debug  调试模式 debug [Application Class] [Param]" . PHP_EOL);
            fwrite($sourStdout, "listen 监听模式" . PHP_EOL);
            fwrite($sourStdout, "daemon 守护进程模式" . PHP_EOL);
            fwrite($sourStdout, "stop   停止" . PHP_EOL);
            fwrite($sourStdout, "================================================================================" . PHP_EOL);
            fclose($sourStdout);
            DIE();
        } else {
            (new ProcessCabinet())->handlerCommand($argv);
        }
    }

    /**
     * @describe
     * @param array $argv 执行定时任务
     * @return void
     */
    public function run(array $argv)
    {
        $this->handlerArgv($argv);
        (new ProcessCabinet())->recordMasterPid(posix_getpid());
        $objProcessKernel = new ProcessKernel();
        $objKernelDao     = new KernelDao();
        while (true) {
            DB::connection()->reconnect();
            $objProcessKernel->detection();
            $objTasks = $objKernelDao->getTasks();

            foreach ($objTasks as $item) {
                $objProcessKernel->run($item);
            }

            $objProcessKernel->processWait();
            sleep(1);
        }
    }
}