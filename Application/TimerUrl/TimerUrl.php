<?php

namespace Application\TimerUrl;


use Application\ApplicationInterface;
use CustomTrait\Common\SingleInstanceTrait;
use GuzzleHttp\Client;
use Kernel\Logger\Logger;

class TimerUrl implements ApplicationInterface
{
    use SingleInstanceTrait;

    public function execute(string $param)
    {
        $strUrl = $param;

        $objHttpClient = new Client();
        try {
            ($objHttpClient->post($strUrl))->getBody()->close();
        } catch (\Exception $exception) {
            // 捕获异常记录
            Logger::error("Timer Url Exception", $exception->getMessage());
        }
    }
}