<?php

namespace Model\Qa;


use Model\BaseModel;

class QaUserIllegalModel extends BaseModel
{
    protected $table = 'qa_user_illegal';
    protected $guarded = [];

    public $timestamps = false;
}