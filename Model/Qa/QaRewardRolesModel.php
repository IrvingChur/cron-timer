<?php

namespace Model\Qa;


use Model\BaseModel;

class QaRewardRolesModel extends BaseModel
{
    const REWARD_STATUS_NORMAL = 1;
    const REWARD_STATUS_CLOSE  = 0;

    protected $table = 'qa_reward_roles';
    protected $guarded = [];

    public $timestamps = false;
}