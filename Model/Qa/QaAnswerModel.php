<?php

namespace Model\Qa;


use Model\Auth\AuthUserModel;
use Model\BaseModel;
use Model\Common\CommonPraiseModel;

class QaAnswerModel extends BaseModel
{
    const ANSWER_STATUS_NORMAL = 0;
    const ANSWER_STATUS_BAN    = 1;

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
                $user->avatar   = '';
            });
    }

    /**
     * @describe 拥有多个点赞
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function praise()
    {
        return $this->hasMany(CommonPraiseModel::class, 'object_id', 'id')
            ->where('object_name', 'answer');
    }

    /**
     * 违规问题或回答
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function illegal()
    {
        return $this->hasOne(QaUserIllegalModel::class, 'object_id', 'id');
    }
}