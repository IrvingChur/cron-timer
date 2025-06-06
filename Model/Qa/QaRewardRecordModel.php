<?php

namespace Model\Qa;

use Model\BaseModel;

class QaRewardRecordModel extends BaseModel
{
    const QA_REWARD_STATUS_SUCCESS = 'success';
    const QA_REWARD_TYPE_QUESTION = 2; // 提问悬赏

    protected $table = 'qa_reward_record';
    protected $guarded = [];

    public $timestamps = false;
}