<?php

namespace Model\Auth;

use Model\BaseModel;

class AuthUserJinModel extends BaseModel
{
    protected $table = 'users_jin';
    protected $guarded = [];

    public $timestamps = false;
}
