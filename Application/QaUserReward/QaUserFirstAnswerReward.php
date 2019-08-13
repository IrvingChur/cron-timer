<?php

namespace Application\QaUserReward;


use Application\ApplicationInterface;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\CommonDao\CommonBillDao;
use Dao\QaDao\QaAnswerDao;
use Dao\QaDao\QaRewardRolesDao;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;
use Kernel\Exception\ExceptionHandler;
use Model\Common\CommonBillModel;
use Model\Qa\QaAnswerModel;
use Carbon\Carbon;

class QaUserFirstAnswerReward implements ApplicationInterface
{
    use SingleInstanceTrait;

    const FIRST_ANSWER_REWARD = 0.2;

    public function execute(string $param)
    {
        // 上一个月的月尾
        $strEndMonthDate   = Carbon::now()->subMonth()->endofMOnth()->toDateTimeString();
        // 上一个月的月初
        $strFirstMonthDate = Carbon::now()->subMonth()->firstOfMonth()->toDateTimeString();
        // get user list
        $objUserList = $this->getUserList($strFirstMonthDate, $strEndMonthDate);
        // handler
        foreach ($objUserList as $item) {
            try {
                $this->statistics($item, $strFirstMonthDate, $strEndMonthDate);
            } catch (\Throwable $throwable) {
                self::getInstance(ExceptionHandler::class)->throwableHandler($throwable);
            }
        }
    }

    /**
     * @describe 获取用户列表
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection [QaAnswerModel]
     */
    protected function getUserList(string $startTime, string $endTime)
    {
        $arrField                 = ['user_id'];
        $objAnswerUserList        = self::getInstance(QaAnswerDao::class)->getUniquenessAnswerUserList($arrField, false, $startTime, $endTime);
        $objSpecialAnswerUserList = self::getInstance(QaAnswerDao::class)->getSpecialAnswerUserList($arrField, $startTime, $endTime);
        // merge
        $objSpecialAnswerUserList->each(function ($item, $key) use ($objAnswerUserList) {
            $objAnswerUserList->add($item);
        });
        // unique
        return $objAnswerUserList->unique('user_id');
    }

    /**
     * @describe 计算星标首答奖励
     * @param QaAnswerModel $item 用户信息
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return void
     */
    protected function statistics(QaAnswerModel $item, string $startTime, string $endTime)
    {
        // 事务执行
        Manager::transaction(function () use($item, $startTime, $endTime) {
            // 获取本月星标首答数量
            $intFirstAnswer = self::getInstance(QaAnswerDao::class)->getFirstAnswerNumber($item->user_id, $startTime, $endTime, true);
            // 计算用户获取奖励
            $floReward      = (float) $intFirstAnswer * self::FIRST_ANSWER_REWARD;
            // 发送奖励
            if ($floReward > 0) {
                self::getInstance(CommonBillDao::class)->createBill(
                    $item->user_id, CommonBillModel::OBJECT_NAME_QA_FIRST_ANSWER_REWARD, 0, $floReward, 0.00, $floReward, 0
                );
            }
        });
    }
}