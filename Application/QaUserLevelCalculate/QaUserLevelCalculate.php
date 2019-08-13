<?php

namespace Application\QaUserLevelCalculate;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\CommonDao\CommonNoticeDao;
use Dao\KernelDao\KernelDao;
use Dao\QaDao\QaAnswerAttributeTagDao;
use Dao\QaDao\QaAnswerDao;
use Dao\QaDao\QaLevelDao;
use Dao\QaDao\QaUserIllegalDao;
use Dao\QaDao\QaUserLevelDao;
use Dao\SnsDao\SnsWeChatDao;
use Dao\UsersDao\UsersRelationDao;
use GuzzleHttp\Client;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;
use Kernel\Exception\ExceptionHandler;
use Kernel\Logger\Logger;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;
use Model\Qa\QaAnswerModel;
use Model\Qa\QaLevelModel;
use Model\Qa\QaUserLevelModel;

class QaUserLevelCalculate implements ApplicationInterface
{
    use SingleInstanceTrait;

    // 模板推送地址
    const INVITATION_URL    = 'https://marketing.xinli001.cc/api/wevent/qa/notice';
    // 集合标识
    const POPUP_WINDOW_FLAG = '_qa_popup_hash';

    public function execute(string $param)
    {
        $isFirstRun     = json_decode($param, true)['first_run'] ?? false;
        $strNowDateTime = Carbon::now()->addHour()->toDateTimeString();
        $strOldDateTime = Carbon::now()->subDay(2)->toDateTimeString();
        $objUserList    = $this->getUserList($isFirstRun, $strOldDateTime, $strNowDateTime);

        // each handler
        $objUserList->each(function ($item, $key) {
            try {
                $this->statistics($item);
            } catch (\Throwable $throwable) {
                self::getInstance(ExceptionHandler::class)->throwableHandler($throwable);
            }
        });

        // 初次运行完毕后清空运行参数
        if ($isFirstRun) {
            self::getInstance(KernelDao::class)->clearTaskParameter(self::class);
        }
    }

    /**
     * @describe 统计用户信息
     * @param QaAnswerModel $item 用户信息
     * @return void
     */
    public function statistics(QaAnswerModel $item)
    {
        Manager::transaction(function () use($item) {
            // 事务执行
            $strNowTime    = Carbon::now()->toDateTimeString();
            $strOriginTime = '0000-00-00 00:00:00';
            $objUserLevel  = self::getInstance(QaUserLevelDao::class)->getUserLevel($item->user_id);
            if (!empty($objUserLevel)) {
                $strOriginTime = $objUserLevel->updated;
            }
            // 获取需求信息
            $intUserAnswerCount  = self::getInstance(QaAnswerDao::class)->userAnswerCount($item->user_id, $strOriginTime, $strNowTime);
            $arrUserSpecialCount = self::getInstance(QaAnswerAttributeTagDao::class)->getUserSpecialCount($item->user_id, $strOriginTime, $strNowTime);
            $intUserFansCount    = self::getInstance(UsersRelationDao::class)->getUserFansNumber($item->user_id, $strOriginTime, $strNowTime);
            $intUserIllegalCount = self::getInstance(QaUserIllegalDao::class)->getUserIllegalNumber($item->user_id, $strOriginTime, $strNowTime);
            // 等级更新
            $this->updateUserLevel(
                $item, $objUserLevel, $intUserAnswerCount, $arrUserSpecialCount, $intUserFansCount, $intUserIllegalCount, $strNowTime
            );
        });
    }

    /**
     * @describe 获取需要执行计算的用户列表
     * @param boolean $isFirstRun 是否第一次执行任务
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection [QaAnswerModel]
     */
    protected function getUserList(bool $isFirstRun, string $startTime, string $endTime)
    {
        // get field
        $arrField = ['user_id'];
        // get users
        $objAnswerUserList            = self::getInstance(QaAnswerDao::class)->getUniquenessAnswerUserList($arrField, $isFirstRun, $startTime, $endTime);
        if (!$isFirstRun) {
            $objSpecialAnswerUserList = self::getInstance(QaAnswerDao::class)->getSpecialAnswerUserList($arrField, $startTime, $endTime);
            $objNewFansUserList       = self::getInstance(UsersRelationDao::class)->getNewFansUserList($arrField, $startTime, $endTime);
            $objNewIllegalUserList    = self::getInstance(QaUserIllegalDao::class)->getNewIllegalUserList($arrField, $startTime, $endTime);
            // merge users
            foreach ([$objSpecialAnswerUserList, $objNewFansUserList, $objNewIllegalUserList] as $collection) {
                $collection->each(function ($item, $key) use ($objAnswerUserList) {
                    $objAnswerUserList->add($item);
                });
            }
            // unique
            $objAnswerUserList = $objAnswerUserList->unique('user_id');
        }

        // 延迟加载用户信息
        return $objAnswerUserList->load([
            'user' => function ($query) {
                $query->select('id', 'nickname');
            }
        ]);
    }

    /**
     * @describe 用户等级更新
     * @param QaAnswerModel $item 用户信息
     * @param QaUserLevelModel $userLevel 用户等级信息
     * @param int $userAnswerCount 用户回答数
     * @param array $userSpecialCount 用户特殊回答数
     * @param int $userFansCount 用户拥趸数
     * @param int $userIllegalCount 用户违规数
     * @param string $strNowTime 开始计算的时间
     * @return void
     */
    protected function updateUserLevel(QaAnswerModel $item, ?QaUserLevelModel $userLevel, int $userAnswerCount, array $userSpecialCount, int $userFansCount, int $userIllegalCount, string $strNowTime)
    {
        $objUserLevel                             = ($userLevel instanceof QaUserLevelModel) ? $userLevel : new QaUserLevelModel();
        $objUserLevel->user_id                    = $item->user_id;
        $objUserLevel->user_nickname              = $item->user->nickname;
        $objUserLevel->answer_number              = (int) $objUserLevel->answer_number + $userAnswerCount;
        $objUserLevel->quality_answer_number      = (int) $objUserLevel->quality_answer_number + $userSpecialCount['quality'];
        $objUserLevel->quintessence_answer_number = (int) $objUserLevel->quintessence_answer_number + $userSpecialCount['quintessence'];
        $objUserLevel->fans_number                = (int) $objUserLevel->fans_number + $userFansCount;
        $objUserLevel->illegal_number             = (int) $objUserLevel->illegal_number + $userIllegalCount;

        $intSpecialAnswerNumber = $objUserLevel->quality_answer_number + $objUserLevel->quintessence_answer_number;
        $objLevel = self::getInstance(QaLevelDao::class)->getCorrespondingLevel(
            $objUserLevel->answer_number, $intSpecialAnswerNumber, $objUserLevel->fans_number, $objUserLevel->illegal_number
        );

        // 如果升级则触发事件
        if (empty($userLevel) || ($userLevel->qa_level_id != $objLevel->id)) {
            $this->trigger($item, $objLevel);
        }

        // 非违规不降级
        if ($objUserLevel->illegal_number == 0 && $objLevel->id > $objUserLevel->qa_level_id) {
            $objUserLevel->qa_level_id  = $objLevel->id;
        } elseif ($objUserLevel->illegal_number != 0) {
            $objUserLevel->qa_level_id  = $objLevel->id;
        }

        $objUserLevel->updated = $strNowTime;
        $objUserLevel->save();
    }

    /**
     * @describe 升级触发事件
     * @param QaAnswerModel $item 用户信息
     * @param QaLevelModel $levelItem 等级信息
     * @return void
     */
    protected function trigger(QaAnswerModel $item, QaLevelModel $levelItem)
    {
        // 加载关联
        $levelItem->load(['popupWindowTemplate', 'systemInformTemplate']);

        // 触发模板消息
        if ($levelItem->id != QaLevelModel::LEVEL_TWO) {
            $this->transmitJava($item->user_id, $levelItem->id);
        }

        // 触发消息通知
        $this->notice($item, $levelItem);

        // 触发弹窗消息
        $this->popupWindow($item, $levelItem);
    }

    /**
     * @describe 触发系统消息
     * @param QaAnswerModel $item 用户信息
     * @param QaLevelModel $levelItem 等级信息
     * @return void
     */
    protected function notice(QaAnswerModel $item, QaLevelModel $levelItem)
    {
        // 未绑定消息模板时不发送
        if (empty($levelItem->systemInformTemplate)) {
            return;
        }

        self::getInstance(CommonNoticeDao::class)->createNotice(
            0, $item->user_id, 'system', 'qa', 0, $levelItem->systemInformTemplate->title, $levelItem->systemInformTemplate->content
        );
    }

    /**
     * @describe 弹窗通知
     * @param QaAnswerModel $item 用户信息
     * @param QaLevelModel $levelItem 等级信息
     * @return void
     */
    protected function popupWindow(QaAnswerModel $item, QaLevelModel $levelItem)
    {
        // 未绑定消息模板时不发送
        if (empty($levelItem->popupWindowTemplate)) {
            return;
        }

        $strEnv   = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $strKey   = $strEnv . self::POPUP_WINDOW_FLAG;
        $objCache = Cache::getInstance();

        // 存放进RedisHash
        $objCache->HMSET($strKey, $item->user_id, json_encode([
            'user_info'     => $item,
            'template_info' => $levelItem->popupWindowTemplate,
        ]));
    }

    /**
     * @describe 发送模板消息
     * @param int $userId 用户ID
     * @param int $flag 标记
     * @return void
     */
    protected function transmitJava(int $userId, int $flag)
    {
        // 测试环境不触发
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        if ($strEnv != 'pro') {
            return;
        }

        // 没有unionID不发送
        $strUserUnionId = self::getInstance(SnsWeChatDao::class)->getUserUnionId($userId);
        if (empty($strUserUnionId)) {
            return;
        }

        $arrParameters = [
            'unionid' => $strUserUnionId,
            'flag'    => $flag,
        ];

        $objHttpClient = new Client();
        try {
            $objResponse = $objHttpClient->request('POST', self::INVITATION_URL, ['x-www-form-urlencoded' => $arrParameters]);
            $objBody     = $objResponse->getBody();
            $strContent  = $objBody->getContents();
            $objBody->close();
            // 记录日志
            Logger::info('Transmit Java Info', $strContent);
        } catch (\Exception $exception) {
            // 捕获异常记录
            Logger::error("Transmit Java Exception", $exception->getMessage());
        }
    }
}