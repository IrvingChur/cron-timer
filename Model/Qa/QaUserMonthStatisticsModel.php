<?php

namespace Model\Qa;


use Model\BaseModel;

class QaUserMonthStatisticsModel extends BaseModel
{
    protected $table = 'qa_user_month_statistics';
    protected $guarded = [];

    public $timestamps = false;
}