<?php

namespace Application\QaUserReward;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\CommonDao\CommonBillDao;
use Dao\CommonDao\CommonTemporaryBillDao;
use Dao\QaDao\QaRewardRolesDao;
use Dao\QaDao\QaUserLevelDao;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;
use Kernel\Exception\ExceptionHandler;
use Model\Common\CommonBillModel;
use Model\Qa\QaUserLevelModel;
use Dao\QaDao\QaAnswerAttributeTagDao;

class QaUserLevelReward implements ApplicationInterface
{
    use SingleInstanceTrait;

    public function execute(string $param)
    {
        // 上一个月的月尾
        $strEndMonthDate   = Carbon::now()->subMonth()->endofMOnth()->toDateTimeString();
        // 上一个月的月初
        $strFirstMonthDate = Carbon::now()->subMonth()->firstOfMonth()->toDateTimeString();
        // get user list
        $objUserList       = $objUserList = self::getInstance(QaUserLevelDao::class)->getSectionUserList($strFirstMonthDate, $strEndMonthDate);
        // each handler
        $objUserList->each(function ($item, $key) use ($strFirstMonthDate, $strEndMonthDate) {
            try {
                $this->statistics($item, $strFirstMonthDate, $strEndMonthDate);
            } catch (\Throwable $throwable) {
                self::getInstance(ExceptionHandler::class)->throwableHandler($throwable);
            }
        });
    }

    /**
     * @describe 计算用户每月奖励
     * @param QaUserLevelModel $item 用户等级信息
     * @return void
     */
    protected function statistics(QaUserLevelModel $item, string $startTime, string $endTime)
    {
        $strJsonRule = $item->level->reward_parameters;
        $objRule     = json_decode($strJsonRule);
        if (empty($objRule)) {
            return;
        }

        // 事务执行
        Manager::transaction(function () use($item, $startTime, $endTime, $objRule) {
            // 用户精华回答
            $intQuintessence = self::getInstance(QaAnswerAttributeTagDao::class)->getUserSpecialCount($item->user_id, $startTime, $endTime)['quintessence'];
            // 发送奖励
            $this->reward($item, $objRule, $intQuintessence);
        });
    }

    /**
     * @describe 发送奖励
     * @param QaUserLevelModel $item 用户信息
     * @param \stdClass $rule 规则
     * @param int $quintessenceNumber 精华数
     * @return void
     */
    protected function reward(QaUserLevelModel $item, \stdClass $rule, int $quintessenceNumber)
    {
        $floDefaultReward = $rule->reward;
        $floBeyondReward  = 0.00;
        if ($quintessenceNumber < $rule->at_least) {
            // 不达标
            return;
        } elseif ($quintessenceNumber > $rule->at_least) {
            // 计算超出值
            $intGap          = $quintessenceNumber - $rule->at_least;
            $floBeyondReward = (float) $intGap * $rule->beyond;
        }

        $floReward = $floBeyondReward + $floDefaultReward;
        $floReward = ($floReward < $rule->upper_limit) ?: $rule->upper_limit;

        // 是否已经开通打赏
        $isOpenReward = self::getInstance(QaRewardRolesDao::class)->isOpenReward($item->user_id);
        if ($isOpenReward) {
            self::getInstance(CommonBillDao::class)->createBill(
                $item->user_id, CommonBillModel::OBJECT_NAME_QA_LEVEL_REWARD, 0, $floReward, 0.00, $floReward, 0, 'qa'
            );
        } else {
            // 未开通打赏临时记录
            self::getInstance(CommonTemporaryBillDao::class)->createTemporaryBill(
                $item->user_id, CommonBillModel::OBJECT_NAME_QA_LEVEL_REWARD, 0, $floReward, 0.00, $floReward, 'qa', '', 0
            );
        }
    }
}