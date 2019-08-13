<?php

namespace Model\Qa;


use Model\Auth\AuthUserModel;
use Model\BaseModel;

class QaAnswerModel extends BaseModel
{
    protected $table = 'qa_answer';
    protected $guarded = [];

    public $timestamps = false;

    // 与问题表一对一从属关系
    public function question()
    {
        return $this->belongsTo(QaQuestionModel::class, 'question_id', 'id');
    }

    // 与回答附加属性表一对一关系
    public function answerAttributeTag()
    {
        return $this->hasOne(QaAnswerAttributeTagModel::class, 'answer_id', 'id');
    }

    // 与用户表一对一从属关系
    public function user()
    {
        return $this->belongsTo(AuthUserModel::class, 'user_id', 'id')
            ->withDefault(function ($user) {
                $user->nickname = '';
            });
    }
}