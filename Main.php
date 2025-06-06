<?php

class Main {

    public function __construct()
    {
        include WEBPATH . '/Kernel/KernelMain.php';
    }

    public function run(array $argv)
    {
        (new \Kernel\KernelMain())->run($argv);
    }

}

try {
    define('WEBPATH', realpath(__DIR__ . '/'));
    (new Main())->run($argv);
} catch (\Throwable $throwable) {
    (new \Kernel\Exception\ExceptionHandler())->throwableHandler($throwable);
}
