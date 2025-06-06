<?php

namespace Library\Common;


use GuzzleHttp\Client;

class TransmitDingTalkLibrary
{
    protected $strLocation;
    protected $strType;
    protected $arrParams;

    /**
     * @describe 设置发送到哪个群组机械人
     * @param string $location 群组机械人
     * @return $this
     */
    public function setLocation(string $location)
    {
        $this->strLocation = $location;
        return $this;
    }

    /**
     * @describe 设置发送类型
     * @param string $type 类型
     * @return $this
     */
    public function setType(string $type)
    {
        $this->strType = $type;
        return $this;
    }

    /**
     * @describe 设置发送参数
     * @param array $params 参数
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->arrParams = $params;
        return $this;
    }

    /**
     * @describe 发送
     * @return boolean
     */
    public function transmit()
    {
        $strLocation = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['dingTalkMessageLocation'];
        $objClient   = new Client();
        $strTransmit = json_encode([
            'sendto' => $this->strLocation,
            'type'   => $this->strType,
            'params' => $this->arrParams
        ]);
        $objClient->post($strLocation, ['body' => $strTransmit]);
    }
}