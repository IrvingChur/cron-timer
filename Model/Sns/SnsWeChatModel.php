<?php

namespace Model\Sns;


use Model\BaseModel;

class SnsWeChatModel extends BaseModel
{
    protected $table = 'sns_wechat';
    protected $guarded = [];

    public $timestamps = false;
}