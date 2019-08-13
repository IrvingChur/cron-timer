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
        $objClient = new Client([
            'scheme' => 'tcp',
            'host'   => $arrConfig['host'],
            'port'   => $arrConfig['port'],
            'password' => $arrConfig['password'],
            'database' => $arrConfig['database'],
        ]);
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