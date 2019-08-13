<?php

namespace Kernel\Logger;


use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\QueryExecuted;
use Library\Common\ConfigureLibrary;

class StructuredQueryLogger implements Dispatcher
{
    public function dispatch($event, $payload = [], $halt = false)
    {
        if (ConfigureLibrary::getConfigure('Configure\SystemConfigure')['sqlDebug']) {
            if ($event instanceof QueryExecuted) {
                $strStructuredQueryLanguage = $event->sql;
                if (!empty($event->bindings)) {
                    foreach ($event->bindings as $param) {
                        $strStructuredQueryLanguage = preg_replace('/\\?/', "'" . addslashes($param) . "'", $strStructuredQueryLanguage, 1);
                    }
                }
                $strStructuredQueryLanguage = ' ' . $strStructuredQueryLanguage;
                $this->record($strStructuredQueryLanguage);
            }
        }
    }

    public function record(string $SQL)
    {
        $strLogPath = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['sqlLoggerPath'] ?: APP_PATH . DIRECTORY_SEPARATOR . 'Logger' . DIRECTORY_SEPARATOR . 'sql-logs';
        $strPath    = $strLogPath . DIRECTORY_SEPARATOR . ConfigureLibrary::getConfigure('Configure\SystemConfigure')['appName'];
        if (!is_dir($strPath)) {
            mkdir($strPath, 0777, true);
        }

        $strFullFile = $strPath .  DIRECTORY_SEPARATOR . 'log-' . date('Ymd') . '.log';
        $sourFile    = fopen($strFullFile, 'a');

        fwrite($sourFile, Carbon::now()->toDateTimeString() . $SQL . PHP_EOL);
        fclose($sourFile);
    }

    public function listen($events, $listener)
    {
        // TODO: Implement listen() method.
    }

    public function hasListeners($eventName)
    {
        // TODO: Implement hasListeners() method.
    }

    public function subscribe($subscriber)
    {
        // TODO: Implement subscribe() method.
    }

    public function until($event, $payload = [])
    {
        // TODO: Implement until() method.
    }

    public function push($event, $payload = [])
    {
        // TODO: Implement push() method.
    }

    public function flush($event)
    {
        // TODO: Implement flush() method.
    }

    public function forget($event)
    {
        // TODO: Implement forget() method.
    }

    public function forgetPushed()
    {
        // TODO: Implement forgetPushed() method.
    }
}