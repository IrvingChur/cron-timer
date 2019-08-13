<?php

namespace Model\Qa;


use Model\BaseModel;

class QaUserLevelModel extends BaseModel
{
    protected $table = 'qa_user_level';
    protected $guarded = [];

    public $timestamps = false;

    // 所属等级反向一对一
    public function level()
    {
        return $this->belongsTo(QaLevelModel::class, 'qa_level_id', 'id');
    }

    // 回答一对多
    public function answer()
    {
        return $this->hasMany(QaAnswerModel::class, 'user_id', 'user_id');
    }
}