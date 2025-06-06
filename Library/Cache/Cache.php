<?php

namespace Library\Cache;


use Library\Common\ConfigureLibrary;
use Predis\Client;

class Cache
{
    protected static $arrInstance = [];
    public $objInstance;

    protected function __construct()
    {
        $arrConfig = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['redisConfigure'];
        $connections = [
            'scheme' => 'tcp',
            'host'   => $arrConfig['host'],
            'port'   => $arrConfig['port'],
            'database' => $arrConfig['database'],
        ];
        if(isset($arrConfig['password']) && !empty($arrConfig['password'])){
            $connections['password'] = $arrConfig['password'];
        }
        $objClient = new Client($connections);
        $this->objInstance = $objClient;
    }

    protected function __clone(){}

    public static function getInstance(?string $scene = null)
    {
        $strScene = $scene ?: posix_getpid();

        if (!isset(self::$arrInstance[$strScene])) {
            self::$arrInstance[$strScene] = new self();
        }

        return (self::$arrInstance[$strScene])->objInstance;
    }
}