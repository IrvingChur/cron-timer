<?php

namespace Model\Users;


use Model\BaseModel;

class InviteTemporaryUserModel extends BaseModel
{
    protected $table = 'invite_temporary_user';
    protected $guarded = [];

    public $timestamps = false;
}