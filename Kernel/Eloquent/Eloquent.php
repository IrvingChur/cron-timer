<?php

namespace Kernel\Eloquent;


use Illuminate\Database\Capsule\Manager;
use Kernel\Logger\StructuredQueryLogger;
use Library\Common\ConfigureLibrary;

class Eloquent
{
    public function initialize(?bool $isChildren = false)
    {
        // 子进程与父进程不能同用连接
        if ($isChildren == true) {
            Manager::connection()->reconnect();
            return;
        }

        $arrConfigure = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['databaseConfigure'];
        $arrTradeConfigure = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['tradeConfigure'];
        $isOpenDebug  = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['sqlDebug'];
        $objCapsule   = new Manager();
        $objCapsule->addConnection($arrConfigure);
        $objCapsule->addConnection($arrTradeConfigure,'trade');
        if (!$isOpenDebug) {
            $objCapsule->getConnection()->disableQueryLog();
        }
        $objCapsule->setAsGlobal();
        $objCapsule->bootEloquent();
        $objCapsule->setEventDispatcher(new StructuredQueryLogger());
    }
}