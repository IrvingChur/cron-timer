<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserBaseModel extends BaseModel
{
    protected $table = 'auth_user_base';
    protected $guarded = [];

    public $timestamps = false;
}