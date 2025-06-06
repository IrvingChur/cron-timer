<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserWriteModel extends BaseModel
{
    protected $table = 'auth_user_zuojia';
    protected $guarded = [];

    public $timestamps = false;
}