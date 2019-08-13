<?php

namespace Model\Qa;


use Model\BaseModel;

class QaLevelTemplateModel extends BaseModel
{
    protected $table = 'qa_level_template';
    protected $guarded = [];

    public $timestamps = false;
}