<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserImeiModel extends BaseModel
{
    protected $table = 'auth_user_imei';
    protected $guarded = [];

    public $timestamps = false;
}