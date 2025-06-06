<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserIdentityModel extends BaseModel
{
    protected $table = 'auth_user_identity';
    protected $guarded = [];

    public $timestamps = false;
}