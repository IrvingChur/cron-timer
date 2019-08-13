<?php

namespace Model\Common;


use Model\BaseModel;

class CommonNoticeModel extends BaseModel
{
    protected $table = 'common_notice';
    protected $guarded = [];

    public $timestamps = false;
}