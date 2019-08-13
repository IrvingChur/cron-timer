<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthUserTeacherModel extends BaseModel
{
    protected $table = 'auth_user_teacher';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 专家擅长领域和疗法
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function therapy()
    {
        return $this->hasOne(AuthTeacherTherapyModel::class, 'user_id', 'user_id');
    }

    /**
     * @describe 专家从业资质
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function qualification()
    {
        return $this->hasOne(AuthTeacherQualificationModel::class, 'user_id', 'user_id');
    }
}