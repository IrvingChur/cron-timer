<?php

namespace Library\Common;


class UrlLibrary
{
    const URL_TYPE_M      = 'm';
    const URL_TYPE_WWW    = 'www';
    const URL_TYPE_ADMIN  = 'admin';
    const URL_TYPE_NOTIFY = 'notify';
    const URL_TYPE_STATIC = 'static';

    /**
     * @describe 生成url
     * @param string $url 地址
     * @param array $parameter 参数
     * @param string $type 类型
     * @return string
     */
    public static function createUrl(string $url, array $parameter, string $type)
    {
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];

        if ($strEnv == 'pro') {
            $strEnv = '';
        } else {
            $strEnv = '-' . $strEnv;
        }

        return 'https://' . $type . $strEnv . '.xinli001' . $url . '?' . http_build_query($parameter);
    }
}