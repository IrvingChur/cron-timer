<?php

namespace Model\Qa;


use Model\BaseModel;

class QaQuestionModel extends BaseModel
{
    protected $table = 'qa_question';
    protected $guarded = [];

    public $timestamps = false;

    // 与回答表一对多关系
    public function answer()
    {
        return $this->hasMany(QaAnswerModel::class, 'question_id', 'id');
    }
}