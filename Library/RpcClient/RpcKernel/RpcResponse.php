<?php

namespace Library\RpcClient\RpcKernel;


class RpcResponse
{
    const GET_ARRAY_TYPE  = 1;
    const GET_OBJECT_TYPE = 2;

    private $strOrigin;
    private $intGetType;
    private $strDecodeContent;

    public function __construct($originContent)
    {
        $this->strOrigin = $originContent;
        $this->strDecodeContent = RpcLibrary::decode($originContent);
    }

    /**
     * @describe 获取数组形式返回
     * @return array
     */
    public function getArray()
    {
        $this->intGetType = self::GET_ARRAY_TYPE;
        return $this->getResponse();
    }

    /**
     * @describe 获取对象形式返回
     * @return object
     */
    public function getObject()
    {
        $this->intGetType = self::GET_OBJECT_TYPE;
        return $this->getResponse();
    }

    /**
     * @describe 获取返回
     * @return mixed
     */
    private function getResponse()
    {
        switch ($this->intGetType) {
            case self::GET_ARRAY_TYPE :
                return unserialize($this->strDecodeContent);
                break;
            case self::GET_OBJECT_TYPE :
                return (object) unserialize($this->strDecodeContent);
                break;
        }
    }
}