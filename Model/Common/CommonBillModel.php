<?php

namespace Model\Common;


use Model\BaseModel;

class CommonBillModel extends BaseModel
{
    const OBJECT_NAME_QA_LEVEL_REWARD        = 'qa.level.reward';
    const OBJECT_NAME_QA_FIRST_ANSWER_REWARD = 'qa.level.first.answer.reward';
    const OBJECT_NAME_QA_REWARD              = 'qa.reward';
    const OBJECT_NAME_QA_POST_REWARD         = 'qa.post.reward';


    protected $table = 'common_zhangdan';
    protected $guarded = [];

    public $timestamps = false;
}