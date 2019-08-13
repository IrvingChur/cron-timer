<?php

namespace Application\ArticleMonitoring;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;
use Library\Common\TransmitDingTalkLibrary;
use Library\User\UserIdentifyLibrary;

class ArticlePublishDetailsMonitoring implements ApplicationInterface
{
    use SingleInstanceTrait;

    const ARTICLE_MONITORING_LAST_RUN_TIME = '_article_monitoring_last_run_time';

    public function execute(string $param)
    {
        $strLastRunTime = $this->getLastRunTime();
        $strEndTime     = Carbon::now()->toDateTimeString();
        if (empty($strLastRunTime)) {
            return;
        }

        $objPublishList = self::getInstance('Dao\CommonDao\CommonArticleDao')->getBetweenByCreateTime($strLastRunTime, $strEndTime, ['*'], true);
        $objPublishList = $objPublishList->filter(function ($item, $key) {
            return (!UserIdentifyLibrary::verifierSpecialUser($item->user->id) && !$this->verifierWhiteList($item->user->id));
        });

        $objTransmitDingTalkLibrary = new TransmitDingTalkLibrary();
        $objTransmitDingTalkLibrary->setLocation('articleMonitor')->setType('text');
        $strMessage   = "{$strLastRunTime} - {$strEndTime} 时间段内，普通用户发布文章的总数量：" . $objPublishList->count() . ' ';
        $intNumber    = 10;
        $arrWhiteList = explode(',', ConfigureLibrary::getConfigure('Configure\UserWhileListConfigure')['articleUserWhileList']);
        foreach ($objPublishList as $item) {
            // 计数 + 筛选白名单用户
            if ($intNumber <= 0) {
                break;
            } elseif (in_array($item->user->id, $arrWhiteList)) {
                continue;
            }
            // 拼接文案
            $strMessage .= "{$item->user->nickname} - {$item->title} ";
            // 计算自减
            $intNumber--;
        }

        $objTransmitDingTalkLibrary->setParams(['msg' => $strMessage]);
        $objTransmitDingTalkLibrary->transmit();

        $this->setLastRunTime();
    }

    /**
     * @describe 验证用户是否白名单
     * @param int $userId 用户ID
     * @return boolean
     */
    protected function verifierWhiteList(int $userId)
    {
        $arrWhiteList = explode(',', ConfigureLibrary::getConfigure('Configure\UserWhileListConfigure')['articleUserWhileList']);
        return (in_array($userId, $arrWhiteList));
    }

    /**
     * @describe 更新最后一次运行时间
     * @return void
     */
    protected function setLastRunTime()
    {
        $strEnv         = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $strCacheKey    = $strEnv . self::ARTICLE_MONITORING_LAST_RUN_TIME;
        Cache::getInstance()->set($strCacheKey, Carbon::now()->toDateTimeString());
    }

    /**
     * @describe 获取最后一次运行时间
     * @return string
     */
    protected function getLastRunTime()
    {
        $strEnv         = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $strCacheKey    = $strEnv . self::ARTICLE_MONITORING_LAST_RUN_TIME;
        $intLastRunTime = Cache::getInstance()->get($strCacheKey);

        // 如果最后运行时间为空立刻更新
        if (empty($intLastRunTime)) {
            Cache::getInstance()->set($strCacheKey, Carbon::now()->toDateTimeString());
            return '';
        }

        return $intLastRunTime;
    }
}