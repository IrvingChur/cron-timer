<?php

namespace Model\Qa;


use Model\BaseModel;

class QaAnswerAttributeTagModel extends BaseModel
{
    protected $table = 'qa_answer_attribute_tag';
    protected $guarded = [];

    public $timestamps = false;
}