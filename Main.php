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

(new Main())->run($argv);