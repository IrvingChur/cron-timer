<?php

namespace Model\Users;


use Model\BaseModel;

class UsersRelationModel extends BaseModel
{
    protected $table = 'users_relation';
    protected $guarded = [];

    public $timestamps = false;
}