<?php

namespace Dao\QaDao;


use Model\Qa\QaUserStatisticsModel;

class QaUserStatisticsDao
{
    /**
     * @describe 获取用户详情
     * @param int $userId 用户ID
     * @return QaUserStatisticsModel
     */
    public function getUserInfo(int $userId)
    {
        return QaUserStatisticsModel::where('user_id', $userId)
            ->first();
    }
}