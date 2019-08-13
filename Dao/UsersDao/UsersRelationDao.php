<?php

namespace Dao\UsersDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Qa\QaAnswerModel;
use Model\Users\UsersRelationModel;

class UsersRelationDao
{
    /**
     * @describe 获取用户拥趸数
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return int
     */
    public function getUserFansNumber(int $userId, string $startTime, string $endTime)
    {
        return UsersRelationModel::where('other_id', $userId)
            ->whereBetween('created', [$startTime, $endTime])
            ->count();
    }

    /**
     * @describe 获取指定时间内有新增fans的用户
     * @param array $field 获取字段
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection [QaAnswerModel]
     */
    public function getNewFansUserList(array $field, string $startTime, string $endTime)
    {
        $arrUserIds = UsersRelationModel::select('other_id')
            ->whereBetween('created', [$startTime, $endTime])
            ->pluck('other_id')
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