<?php

namespace Application\ArticleMonitoring;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Library\Common\WarningLibrary;

class ArticlePublishNumberMonitoring implements ApplicationInterface
{
    use SingleInstanceTrait;

    public function execute(string $param)
    {
        // 获取一小时内发帖数
        $strEndTime     = Carbon::now()->toDateTimeString();
        $strStartTime   = Carbon::now()->modify('-1 hour')->toDateTimeString();
        $objPublishList = self::getInstance('Dao\CommonDao\CommonArticleDao')->getBetweenByCreateTime($strStartTime, $strEndTime);

        if ($objPublishList->count() > 10) {
            // 拼装信息
            $strMessage = "{$strStartTime} - {$strEndTime} 时间段内，所有用户发布文章的总数量：" . $objPublishList->count() . ' ';
            $intGetNumber = 5;
            foreach ($objPublishList as $item) {
                if ($intGetNumber <= 0) {
                    break;
                }
                // 拼接文案
                $strMessage .= "{$item->user->nickname}-{$item->title} ";
                // 剩余数自减
                $intGetNumber--;
            }

            WarningLibrary::transmitWarning('用户发布文章监控', $strMessage);
        }
    }
}