<?php

namespace Model\Qa;


use Model\BaseModel;

class QaThesaurusStatisticsModel extends BaseModel
{
    // 问题状态

    protected $table   = 'qa_thesaurus_statistics';
    protected $guarded = [];

    public $timestamps = false;

}