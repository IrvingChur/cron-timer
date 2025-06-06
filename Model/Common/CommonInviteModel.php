<?php

namespace Model\Common;

use Model\BaseModel;

class CommonInviteModel extends BaseModel
{
    protected $table = 'common_invite';
    protected $guarded = [];

    public $timestamps = false;
}
