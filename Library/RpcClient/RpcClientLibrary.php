<?php

namespace Library\RpcClient;


use Library\Common\ConfigureLibrary;
use Library\RpcClient\RpcKernel\RpcLibrary;
use Library\RpcClient\RpcKernel\RpcResponse;

class RpcClientLibrary
{
    protected static $objRpcClientInstance = [];
    protected $objClientInstance;
    protected $arrRpcConfigure = [];

    protected function __construct()
    {
        $this->arrRpcConfigure = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['rpcConfigure'];
        $this->objClientInstance = $this->getClient();
    }

    protected function __clone(){}

    /**
     * @describe 单列调用
     * @param string|null $scene
     * @return RpcClientLibrary
     */
    public static function getInstance(?string $scene = null)
    {
        $strScene = $scene ?: posix_getpid();

        if (!isset(self::$objRpcClientInstance[$strScene])) {
            self::$objRpcClientInstance[$strScene] = new self();
        }

        return self::$objRpcClientInstance[$strScene];
    }

    /**
     * @param string $class 远程调用类
     * @param string $method 远程调用方法
     * @param array $param 远程调用参数
     * @return RpcResponse
     */
    public function sendToRpcService(string $class, string $method, array $param)
    {
        $arrData = [
            'class'  => $class,
            'method' => $method,
            'param_array' => $param,
            'clientIp' => '127.0.0.1',
        ];

        $arrRpcData = [
            'call'   => 'App\\Service::call',
            'params' => $arrData,
            'env'    => ['user' => $this->arrRpcConfigure['auth']['username'], 'password' => $this->arrRpcConfigure['auth']['password']],
        ];

        $objClient = $this->getClient();
        $objClient->send(RpcLibrary::encode($arrRpcData));
        $strResponse = $objClient->recv();
        $objRpcResponse = new RpcResponse($strResponse);
        sleep(1);
        return $objRpcResponse;
    }

    /**
     * @describe 重新连接
     * @return void
     */
    public function reconnection()
    {
        $this->objClientInstance->close();
        unset($this->objClientInstance);
        $this->objClientInstance = $this->getClient();
    }

    /**
     * @describe 获取Client
     * @return \swoole_client
     */
    protected function getClient()
    {
        $objClient = new \swoole_client(SWOOLE_SOCK_TCP);
        $objClient->set([
            'open_length_check'     => true,
            'package_max_length'    => 2097152,
            'package_length_type'   => 'N',
            'package_body_offset'   => RpcLibrary::HEADER_SIZE,
            'package_length_offset' => 0,
        ]);

        $arrServices = $this->arrRpcConfigure['servers'];
        foreach ($arrServices as $item) {
            if ($objClient->connect($item['host'], $item['port'])) {
                break;
            }
        }

        return $objClient;
    }
}