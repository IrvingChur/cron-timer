<?php

namespace Kernel\Eloquent;


use Illuminate\Database\Capsule\Manager;
use Kernel\Logger\StructuredQueryLogger;
use Library\Common\ConfigureLibrary;

class Eloquent
{
    public function initialize()
    {
        $arrConfigure = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['databaseConfigure'];
        $isOpenDebug  = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['sqlDebug'];
        $objCapsule   = new Manager();
        $objCapsule->addConnection($arrConfigure);
        if (!$isOpenDebug) {
            $objCapsule->getConnection()->disableQueryLog();
        }
        $objCapsule->setAsGlobal();
        $objCapsule->bootEloquent();
        $objCapsule->setEventDispatcher(new StructuredQueryLogger());
    }
}