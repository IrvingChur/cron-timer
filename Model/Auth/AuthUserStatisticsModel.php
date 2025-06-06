<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserStatisticsModel extends BaseModel
{
    protected $table = 'auth_user_jishu';
    protected $guarded = [];

    public $timestamps = false;
}