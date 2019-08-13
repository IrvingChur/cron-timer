<?php

class Main {

    public function __construct()
    {
        include './Kernel/KernelMain.php';
    }

    public function run(array $argv)
    {
        (new \Kernel\KernelMain())->run($argv);
    }

}

try {
    (new Main())->run($argv);
} catch (\Throwable $throwable) {
    (new \Kernel\Exception\ExceptionHandler())->throwableHandler($throwable);
}