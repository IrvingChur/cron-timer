<?php

namespace Model\Qa;


use Model\BaseModel;

class QaUserStatisticsModel extends BaseModel
{
    protected $table = 'qa_user_statistics';
    protected $guarded = [];

    public $timestamps = false;
}