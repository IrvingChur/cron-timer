<?php


namespace Model\Qa;


use Model\BaseModel;

class QaCantonalRewardRecordModel extends BaseModel
{
    // 是否已领取
    const ALREADY_NO   = 0;  // 未领取
    const ALREADY_YES  = 1;  // 已领取

    // 奖励类型
    const REWARD_TYPE_NUMBER = 0;   // 个人奖励
    const REWARD_TYPE_MASTER = 1;   // 分馆奖励

    protected $table   = 'qa_cantonal_reward_record';
    protected $guarded = [];

    public $timestamps = false;
}