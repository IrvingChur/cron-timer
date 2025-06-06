<?php

namespace Model\Users;


use Model\BaseModel;

class UserLoginLogModel extends BaseModel
{
    protected $table = 'user_login_log';
    protected $guarded = [];

    public $timestamps = false;
}