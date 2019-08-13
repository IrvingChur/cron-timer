<?php

namespace Model\Common;


use Model\BaseModel;

class CommonPraiseModel extends BaseModel
{
    const OBJECT_NAME_ARTICLE         = 'article';
    const OBJECT_NAME_ARTICLE_COMMENT = 'article.comment';
    const OBJECT_NAME_ZIXUN_XIANG     = 'zixun.xiang';
    const OBJECT_NAME_ZIXUN_KAI       = 'zixun.kaitong';
    const OBJECT_NAME_USER            = 'user';
    const OBJECT_NAME_COMMENT         = 'comment';
    const OBJECT_NAME_ANSWER          = 'answer';
    const OBJECT_NAME_JIEHUO          = 'jiehuo';
    const OBJECT_NAME_FM              = 'fm';
    const OBJECT_NAME_FM_COMMENT      = 'fm.comment';
    const OBJECT_NAME_COLLEGE         = 'college';

    protected $table = 'common_zan';
    protected $guarded = [];

    public $timestamps = false;
}