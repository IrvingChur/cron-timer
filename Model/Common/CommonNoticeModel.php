<?php

namespace Model\Common;


use Model\BaseModel;

class CommonNoticeModel extends BaseModel
{
    protected $table = 'common_notice';
    protected $guarded = [];

    public $timestamps = false;

    public static $typeMapText = [
        'comment' => '新增评论',
        'follow'  => '新增关注',
        'zan'     => '新增点赞',
    ];
}