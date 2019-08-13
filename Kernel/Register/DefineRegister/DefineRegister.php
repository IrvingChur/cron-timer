<?php

namespace Kernel\Register\DefineRegister;


use Kernel\Register\RegisterInterface;

class DefineRegister implements RegisterInterface
{
    public function register()
    {
        date_default_timezone_set('PRC');
        define('APP_PATH', dirname(dirname(dirname(__DIR__))));
    }
}