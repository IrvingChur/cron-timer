<?php

namespace Model\Auth;


use Model\BaseModel;
use Model\Users\UsersRelationModel;

class AuthUserModel extends BaseModel
{
    protected $table = 'auth_user';
    protected $guarded = [];

    public $timestamps = false;
}