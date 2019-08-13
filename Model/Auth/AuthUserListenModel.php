<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserListenModel extends BaseModel
{
    protected $table = 'auth_user_qingting';
    protected $guarded = [];

    public $timestamps = false;
}