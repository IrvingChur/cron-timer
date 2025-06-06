<?php

namespace Kernel\Logger;

use Carbon\Carbon;
use Library\Common\ConfigureLibrary;

class Logger
{
    const LEVEL_DEBUG = 1;  // 调试:调试消息
    const LEVEL_INFO  = 2;   // 信息:信息消息
    const LEVEL_WARN  = 3;   // 警告:警告条件
    const LEVEL_ERROR = 4;  // 错误:错误条件
    const LEVEL_EMERG = 5;  // 紧急:系统无法使用

    public static function debug($title, $message, $module = null) {
        //return self::log2sls($module, self::LEVEL_DEBUG, $method, $message, self::LEVEL_DEBUG);
        return self::_log(self::LEVEL_DEBUG, $title, $message);
    }

    public static function info($title, $message, $module = null) {
        // return self::log2sls($module, self::LEVEL_INFO, $method, $message, self::LEVEL_INFO);
        return self::_log(self::LEVEL_INFO, $title, $message);
    }

    public static function warn($title, $message, $module = null) {
        //return self::log2sls($module, self::LEVEL_WARN, $method, $message, self::LEVEL_WARN);
        return self::_log(self::LEVEL_WARN, $title, $message);
    }

    public static function error($title, $message, $module = null, $serverHost = '') {
        //return self::log2sls($module, self::LEVEL_ERROR, $method, $message, self::LEVEL_ERROR);
        return self::_log(self::LEVEL_ERROR, $title, $message, $serverHost);
    }

    public static function emerg($title, $message, $module = null) {
        //return self::log2sls($module, self::LEVEL_EMERG, $method, $message, self::LEVEL_EMERG);
        return self::_log(self::LEVEL_EMERG, $title, $message);
    }

    /**
     * 日志记录
     * @param string $level	日志级别
     * @param string $title  标题
     * @param mixed  $logArr  日志内容（数组）
     * @param string $serverHost  服务器主机
     * @return boolean
     */
    private static function _log($level, $title, $logArr, $serverHost = '') {
        $arrLogLevel = array('1'=>'debug', '2'=>'info', '3'=>'warn', '4'=>'error', '5'=>'emerg');
        $arrLogLevelFlip = array_flip($arrLogLevel);
        $intLogLevel =  $arrLogLevelFlip[ConfigureLibrary::getConfigure('Configure\SystemConfigure')['loggerLevel']];

        if($intLogLevel > $level) {
            return false;
        }

        $strLogPath = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['loggerPath'] ?: APP_PATH . DIRECTORY_SEPARATOR . 'Logger' . DIRECTORY_SEPARATOR . 'running-logs';
        $strPath = $strLogPath . DIRECTORY_SEPARATOR . ConfigureLibrary::getConfigure('Configure\SystemConfigure')['appName'];

        if (!is_dir($strPath)) {
            mkdir($strPath, 0777, true);
        }

        $strFullFile = $strPath .  DIRECTORY_SEPARATOR . 'log-' . date('Ymd') . '.log';

        $arrParams = [
            'time' => Carbon::now()->toDateTimeString(),
            'level' => $arrLogLevel[$level],
            'app' => ConfigureLibrary::getConfigure('Configure\SystemConfigure')['appName'],
            'ip' => '',
            'title' => $title,
            'message' => $logArr,
            'server_host' => $serverHost,
        ];

        $sourFile = fopen($strFullFile, 'a');
        fwrite($sourFile, json_encode($arrParams) . PHP_EOL);
        fclose($sourFile);

        return true;
    }
}