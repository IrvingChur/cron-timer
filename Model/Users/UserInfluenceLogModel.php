<?php

namespace Model\Users;


use Model\BaseModel;

class UserInfluenceLogModel extends BaseModel
{
    protected $table = 'user_influence_log';
    protected $guarded = [];

    public $timestamps = false;
}