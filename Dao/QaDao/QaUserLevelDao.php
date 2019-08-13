<?php

namespace Dao\QaDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Qa\QaUserLevelModel;

class QaUserLevelDao
{
    /**
     * @describe 获取用户等级
     * @param int $userId 用户ID
     * @return QaUserLevelModel
     */
    public function getUserLevel(int $userId)
    {
        return QaUserLevelModel::where('user_id', $userId)
            ->first();
    }

    /**
     * @describe 获取区间合资格用户列表
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection [QaUserLevelModel]
     */
    public function getSectionUserList(string $startTime, string $endTime)
    {
        $arrUserList = (new QaAnswerDao())->getSpecialAnswerUserList(['user_id'], $startTime, $endTime)->toArray();

        return QaUserLevelModel::whereHas('level', function ($query) {
            $query->where('reward_parameters', '!=', '');
        })
            ->whereIn('user_id', $arrUserList)
            ->get();
    }
}