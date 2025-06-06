<?php

namespace Kernel\Register;


use Predis\Autoloader;

class PredisRegister implements RegisterInterface
{
    public function register()
    {
        Autoloader::register();
    }
}