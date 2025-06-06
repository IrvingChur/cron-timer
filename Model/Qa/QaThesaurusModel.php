<?php

namespace Model\Qa;


use Model\BaseModel;

class QaThesaurusModel extends BaseModel
{
    // 问题状态

    protected $table   = 'qa_thesaurus';
    protected $guarded = [];

    public $timestamps = false;

}