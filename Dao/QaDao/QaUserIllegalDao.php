<?php

namespace Dao\QaDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Qa\QaAnswerModel;
use Model\Qa\QaUserIllegalModel;

class QaUserIllegalDao
{
    /**
     * @describe 获取用户违规数
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return integer
     */
    public function getUserIllegalNumber(int $userId, string $startTime, string $endTime)
    {
        return QaUserIllegalModel::where('user_id', $userId)
            ->whereBetween('created', [$startTime, $endTime])
            ->count();
    }

    /**
     * @describe 获取时间段内新增违规用户
     * @param array $field 获取字段
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection [QaAnswerModel]
     */
    public function getNewIllegalUserList(array $field, string $startTime, string $endTime)
    {
        $arrUserIds = QaUserIllegalModel::select('user_id')
            ->whereBetween('created', [$startTime, $endTime])
            ->pluck('user_id')
            ->toArray();

        if (empty($arrUserIds)) {
            return new Collection();
        }

        return QaAnswerModel::select(...$field)
            ->whereIn('user_id', $arrUserIds)
            ->groupBy('user_id')
            ->get();
    }
}