<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserQaModel extends BaseModel
{
    // 精华回答者
    const TAG_ESSENCE_ROLE = 14;
    // 优质回答者
    const TAG_QUALITY_ROLE = 13;
    // 攻击性标签
    const TAG_ATTACK_LABEL = 15;

    protected $table = 'auth_user_qa';
    protected $guarded = [];

    public $timestamps = false;
}