<?php

namespace Kernel\Register\ComposerRegister;


use Kernel\Register\RegisterInterface;

class ComposerRegister implements RegisterInterface
{
    public function register()
    {
        include_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
    }
}