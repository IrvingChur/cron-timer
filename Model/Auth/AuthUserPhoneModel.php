<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserPhoneModel extends BaseModel
{
    protected $table = 'auth_user_phone';
    protected $guarded = [];

    public $timestamps = false;

}