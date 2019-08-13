<?php

namespace Application\IntelligentOperation;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;
use Library\RpcClient\RpcClientLibrary;

class SendingMessage implements ApplicationInterface
{
    use SingleInstanceTrait;
    // 需要发送私信集合
    const NEED_SENDING_MESSAGE_LIST = '_need_sending_message_list';
    // 6小时发送标记
    const SIX_HOUR_FLAG = 1;
    // 12小时发送标记
    const TWENTY_FOUR_FLAG = 2;
    // 私信文案
    const MESSAGE_CONTENT = [
        self::SIX_HOUR_FLAG => '你还在吗？想知道现在你还好吗？很担心你。',
        self::TWENTY_FOUR_FLAG => '新的一天，只要相信自己，会有好事发生的。期待你告诉我们你的近况。',
    ];

    protected $objRedisInstance;

    public function execute(string $param)
    {
        $this->objRedisInstance = Cache::getInstance();
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $arrSendingList = $this->objRedisInstance->SMEMBERS($strEnv . self::NEED_SENDING_MESSAGE_LIST);
        if (empty($arrSendingList)) {
            return;
        }

        foreach ($arrSendingList as $item) {
            $objItem = json_decode(base64_decode($item));
            $this->sendingPrivateMessage($objItem, $item);
        }
    }

    /**
     * @describe 发送私信
     * @param \stdClass $item 发送名单
     * @param string $origin 原始值
     * @return void
     */
    protected function sendingPrivateMessage(\stdClass $item, string $origin)
    {
        if ($this->checkTime($item->created, $item->flag)) {
            return;
        }

        $this->sending($item, $origin);
    }

    /**
     * @describe 检测时间
     * @param int $unixTime 队列加入时间戳
     * @param int $flag 事件类型
     * @return boolean
     */
    protected function checkTime(int $unixTime, int $flag)
    {
        $intNowTimeUnix = Carbon::now()->unix();
        $intApartUnix   = $intNowTimeUnix - $unixTime;
        $intApartHour   = intval(($intApartUnix / 60 / 60));

        switch ($flag) {
            case self::SIX_HOUR_FLAG :
                if ($intApartHour < 6) {
                    return true;
                }
                break;
            case self::TWENTY_FOUR_FLAG :
                if ($intApartHour < 24) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * @describe 发送私信
     * @param \stdClass $item 私信发送对象
     * @param string $origin 原始值
     * @return void
     */
    protected function sending(\stdClass $item, string $origin)
    {
        $intUsesId  = DetectionQuestion::AUTO_ANSWER_USES_ID;
        $strContent = self::MESSAGE_CONTENT[$item->flag];

        $objRpcClient = RpcClientLibrary::getInstance();
        $objResponse  = $objRpcClient->sendToRpcService('User', 'sendMessage', [$intUsesId, $item->user_id, $strContent]);
        $arrResponse  = $objResponse->getArray();

        // 已完成清理集合
        if (!empty($arrResponse) && $arrResponse['data']['msg'] == 'ok') {
            $this->removeCacheSet($origin);
        }
    }

    /**
     * @describe 删除集合中对应的值
     * @param string $origin 原始值
     * @return void
     */
    protected function removeCacheSet(string $origin)
    {
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $this->objRedisInstance->SREM($strEnv . self::NEED_SENDING_MESSAGE_LIST, $origin);
    }
}