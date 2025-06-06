<?php

namespace Model\Users;


use Model\BaseModel;
use Model\Users\UserLoginLogModel;

class UserMessagePushSwitchModel extends BaseModel
{
    protected $table = 'user_message_push_switch';
    protected $guarded = [];

    public $timestamps = false;
    const OBJECT_NAME_CLOCK = 'clock';
}