<?php

namespace Library\User;


use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;

class UserIdentifyLibrary
{
    /**
     * @describe 检测用户是否属于该角色
     * @param string $role 角色
     * @param int $userId 用户ID
     * @return boolean
     */
    public static function hasRole(string $role, int $userId)
    {
        return (Cache::getInstance()->sismember($role, $userId) > 0);
    }

    /**
     * @describe 校验用户是否特殊身份
     * @param int $userId 用户ID
     * @return boolean
     */
    public static function verifierSpecialUser(int $userId)
    {
        foreach (self::getAllRolesFlag() as $item) {
            if (self::hasRole($item, $userId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @describe 获取所有用户身份标识
     * @return array
     */
    public static function getAllRolesFlag()
    {
        $strEnv  = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $arrFlag = [
            'teacher', 'jigou', 'zuojia', 'qingting', 'reward', 'quality', 'essence'
        ];

        foreach ($arrFlag as &$item) {
            $item = $strEnv . $item;
        }
        unset($item);

        return $arrFlag;
    }
}