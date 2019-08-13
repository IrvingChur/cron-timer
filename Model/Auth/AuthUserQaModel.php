<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserQaModel extends BaseModel
{
    const TAG_ESSENCE_ROLE = 14;
    const TAG_QUALITY_ROLE = 13;

    protected $table = 'auth_user_qa';
    protected $guarded = [];

    public $timestamps = false;
}