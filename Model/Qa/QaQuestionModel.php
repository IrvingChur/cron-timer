<?php

namespace Model\Qa;


use Model\BaseModel;
use Model\Common\CommonCustomeTagModel;
use Model\Common\CommonObjectTagModel;

class QaQuestionModel extends BaseModel
{
    // 问题状态
    const STATUS_PUBLISH = 0;   // 公开的
    const STATUS_BAN     = 1;   // 被禁止

    const QA_CREAM_QUESTION_TAG_ID = 3;
    const QA_HIGH_QUALITY_QUALITY_TAG_ID = 2; # 优质问题

    protected $table   = 'qa_question';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 与回答一对多关系
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer()
    {
        return $this->hasMany(QaAnswerModel::class, 'question_id', 'id');
    }

    /**
     * @describe 与问题标签表一对一关系
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function questionAttributeTag()
    {
        return $this->hasOne(QaQuestionAttributeTagModel::class, "question_id", "id");
    }

    /**
     * @describe 与问题标签一对一关系
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attributeTag()
    {
        return $this->hasOne(QaQuestionAttributeTagModel::class, "question_id", "id");
    }

    /**
     * @describe 与分馆所属一对一关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cantonal()
    {
        return $this->belongsTo(QaCantonalModel::class, "cantonal_id", "id");
    }

    /**
     * @describe 所属标签
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(CommonCustomeTagModel::class, "tag_id", "id");
    }



    /**
     * @describe 拥有二级标签
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function secondTag()
    {
        return $this->hasMany(CommonObjectTagModel::class, "object_id", "id")
            ->where("object_name", "qa_question");
    }

    public function getTitle(){
        $currTitle = $this->major_title;
        if(empty($currTitle)){
            $currTitle = $this->title;
        }//end if
        return $currTitle;
    }

    // 【360】获取对应的答案
    public function answers()
    {
        return $this->hasMany(QaAnswerModel::class, 'question_id', 'id')
            ->select(['id','user_id','nickname','question_id','content','votenum','replynum','teacher_id','teacher'])
            ->where('jin', 0)
            ->orderBy('votenum', 'DESC')
            ->orderBy('created', 'DESC');
    }
}