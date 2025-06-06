<?php

namespace Kernel\Register\ConfigureRegister;


use Configure\ConfigureInterface;
use Kernel\Register\RegisterInterface;
use Library\Common\DirectoryLibrary;

class ConfigureRegister implements RegisterInterface
{
    public static $arrConfigures = [];

    public function register()
    {
        $strRegisterPath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'Configure';
        $arrConfigureFiles = DirectoryLibrary::foreachDirectory($strRegisterPath);

        foreach ($arrConfigureFiles as $item) {
            $arrFileExplode = explode(DIRECTORY_SEPARATOR, $item);
            $arrPathExplode = explode(DIRECTORY_SEPARATOR, dirname(__DIR__));
            $arrClass = array_diff($arrFileExplode, $arrPathExplode);
            $strRegisterClass = implode('\\', $arrClass);
            $strRegisterClass = substr($strRegisterClass, 0, strlen($strRegisterClass) - 4);

            if (class_exists($strRegisterClass)) {
                $objRegisterClassInstance = new $strRegisterClass();
                if ($objRegisterClassInstance instanceof ConfigureInterface) {
                    ConfigureRegister::$arrConfigures[$strRegisterClass] = call_user_func([$objRegisterClassInstance, 'ResultConfigure']);
                }
            }
        }
    }
}