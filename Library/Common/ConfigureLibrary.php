<?php

namespace Library\Common;


use Kernel\Register\ConfigureRegister\ConfigureRegister;

class ConfigureLibrary
{
    /**
     * @describe 获取配置
     * @param null|string $className
     * @return array
     */
    public static function getConfigure(?string $className = '')
    {
        $arrConfigures = ConfigureRegister::$arrConfigures;
        if (!empty($className) && !empty($arrConfigures[$className])) {
            return $arrConfigures[$className];
        }

        return $arrConfigures;
    }
}