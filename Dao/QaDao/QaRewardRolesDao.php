<?php

namespace Dao\QaDao;


use Model\Qa\QaRewardRolesModel;

class QaRewardRolesDao
{
    /**
     * @describe 用户是否开启打赏
     * @param int $userId 用户ID
     * @return boolean
     */
    public function isOpenReward(int $userId)
    {
        return QaRewardRolesModel::where('user_id', $userId)
            ->where('status', QaRewardRolesModel::REWARD_STATUS_NORMAL)
            ->exists();
    }
}