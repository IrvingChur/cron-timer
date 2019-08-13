<?php

namespace Application\QaUserStatistics;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\CommonDao\CommonBillDao;
use Dao\CommonDao\CommonTemporaryBillDao;
use Dao\KernelDao\KernelDao;
use Dao\QaDao\QaAnswerDao;
use Dao\QaDao\QaUserStatisticsDao;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;
use Kernel\Exception\ExceptionHandler;
use Model\Common\CommonBillModel;
use Model\Qa\QaAnswerModel;
use Model\Qa\QaUserStatisticsModel;

class QaUserStatistics implements ApplicationInterface
{
    use SingleInstanceTrait;

    public function execute(string $param)
    {
        // 获取更多内存
        ini_set('memory_limit','1024M');

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
     * @describe 获取需要统计的用户列表
     * @param bool $isFirstRun 是否第一次执行
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
            $objRewardUserList        = self::getInstance(CommonBillDao::class)->getSectionRewardUserList($arrField, $startTime, $endTime, [CommonBillModel::OBJECT_NAME_QA_FIRST_ANSWER_REWARD, CommonBillModel::OBJECT_NAME_QA_LEVEL_REWARD, CommonBillModel::OBJECT_NAME_QA_POST_REWARD, CommonBillModel::OBJECT_NAME_QA_REWARD]);
            // merge users
            foreach ([$objSpecialAnswerUserList, $objRewardUserList] as $collection) {
                $collection->each(function ($item, $key) use ($objAnswerUserList) {
                    $objAnswerUserList->add($item);
                });
            }
            // unique
            $objAnswerUserList = $objAnswerUserList->unique('user_id');
        }

        return $objAnswerUserList;
    }

    /**
     * @describe 统计问答用户信息
     * @param QaAnswerModel $item 用户信息
     * @return void
     */
    protected function statistics(QaAnswerModel $item)
    {
        Manager::transaction(function () use($item) {
            $strNowTime    = Carbon::now()->toDateTimeString();
            $strOriginTime = '0000-00-00 00:00:00';
            $objQaUserInfo = self::getInstance(QaUserStatisticsDao::class)->getUserInfo($item->user_id);

            if (!empty($objQaUserInfo)) {
                $strOriginTime = $objQaUserInfo->updated;
            }

            // 用户回答字数
            $intWordsNumber = self::getInstance(QaAnswerDao::class)->statisticsAnswerNumber($item->user_id, $strOriginTime, $strNowTime);
            // 初次回复时间
            $strFirstAnswer = ($objQaUserInfo->first_answer_date) ?: self::getInstance(QaAnswerDao::class)->getUserFirstAnswerDate($item->user_id);
            // 总收益统计
            $floRewardCount = self::getInstance(CommonBillDao::class)->rewardCount($item->user_id, $strOriginTime, $strNowTime, [CommonBillModel::OBJECT_NAME_QA_REWARD, CommonBillModel::OBJECT_NAME_QA_POST_REWARD, CommonBillModel::OBJECT_NAME_QA_LEVEL_REWARD, CommonBillModel::OBJECT_NAME_QA_FIRST_ANSWER_REWARD]);
            // 星标首答数
            $intFirstAnswer = self::getInstance(QaAnswerDao::class)->getFirstAnswerNumber($item->user_id, $strOriginTime, $strNowTime, true);
            // 临时收益统计
            $floTempReward  = self::getInstance(CommonTemporaryBillDao::class)->rewardCount($item->user_id, $strOriginTime, $strNowTime, [CommonBillModel::OBJECT_NAME_QA_REWARD, CommonBillModel::OBJECT_NAME_QA_POST_REWARD, CommonBillModel::OBJECT_NAME_QA_LEVEL_REWARD, CommonBillModel::OBJECT_NAME_QA_FIRST_ANSWER_REWARD]);

            $objQaUserInfo = ($objQaUserInfo instanceof QaUserStatisticsModel) ? $objQaUserInfo : new QaUserStatisticsModel();
            $objQaUserInfo->user_id              = $item->user_id;
            $objQaUserInfo->first_answer_date    = $strFirstAnswer;
            $objQaUserInfo->first_special_answer = (int) $objQaUserInfo->first_special_answer + $intFirstAnswer;
            $objQaUserInfo->words_number         = (int) $objQaUserInfo->words_number + $intWordsNumber;
            $objQaUserInfo->earnings             = (int) (((float) $floRewardCount * 100) + (int) $objQaUserInfo->earnings);
            $objQaUserInfo->earnings_temporary   = (int) (((float) $floTempReward * 100) + (int) $objQaUserInfo->earnings_temporary);
            $objQaUserInfo->updated              = $strNowTime;
            $objQaUserInfo->save();
        });
    }
}