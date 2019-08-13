<?php

namespace CustomTrait\Common;


/**
 * @describe 单例获取
 * Trait SingleInstanceTrait
 * @package CustomTrait\Common
 */
trait SingleInstanceTrait {

    private static $instance = [];

    /**
     * @describe 获取实例
     * @param string $class 类名
     * @param string $scene 场景
     * @return object
     */
    public static function getInstance(string $class, ?string $scene = 'default')
    {
        if (!isset(self::$instance[$scene][$class]) || !(self::$instance[$scene][$class] instanceof $class)) {
            self::$instance[$scene][$class] = new $class();
        }

        $objInstance = self::$instance[$scene][$class];

        return $objInstance;
    }

}