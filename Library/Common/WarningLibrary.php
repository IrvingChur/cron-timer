<?php

namespace Library\Common;


use Carbon\Carbon;
use GuzzleHttp\Client;

class WarningLibrary
{
    public static $arrRecord = [];

    public static function transmitWarning(string $title, string $message)
    {
        if (self::limited($title, $message)) {
            return;
        }

        $strLocation = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['errorWarningLocation'];
        $arrWarningMessage = [
            'project' => 'cron-timer',
            'title' => $title,
            'msg' => $message,
        ];
        $strJsonWarningMessage = json_encode($arrWarningMessage);

        $objHttpClient = new Client();
        $objHttpClient->post($strLocation, ['body' => $strJsonWarningMessage]);
    }

    public static function limited(string $title, string $message)
    {
        $strMarkKey = base64_encode($title . $message);
        if (!isset(self::$arrRecord[$strMarkKey])) {
            $objMark = new \stdClass();
            $objMark->created = Carbon::now()->unix();
            $objMark->degree = 1;
            self::$arrRecord[$strMarkKey] = $objMark;
            return false;
        }

        $objMark = self::$arrRecord[$strMarkKey];
        $intInterval = Carbon::now()->unix() - $objMark->created;
        if ($intInterval >= (5 * 60)) {
            unset(self::$arrRecord[$strMarkKey]);
            return false;
        } elseif ($objMark->degree >= 10) {
            return true;
        } else {
            $objMark->degree++;
            self::$arrRecord[$strMarkKey] = $objMark;
            return false;
        }
    }
}