<?php

namespace Dao\CommonDao;


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;
use Model\Common\CommonPraiseModel;

class CommonPraiseDao
{
    /**
     * @describe 获取邀请列表
     * @param string $morphLabel 多态标记
     * @param int $praiseNumber 至少获取点赞数
     * @return Collection [CommonPraiseModel]
     */
    public function getInviteList(string $morphLabel, int $praiseNumber)
    {
        return CommonPraiseModel::select(Manager::raw('count(1) as praiseNumber, object_user_id'))
            ->where('object_name', $morphLabel)
            ->where('object_user_id', '>', 0)
            ->groupBy('object_user_id')
            ->having('praiseNumber', '>', $praiseNumber)
            ->orderBy('praiseNumber', 'desc')
            ->get();
    }
}