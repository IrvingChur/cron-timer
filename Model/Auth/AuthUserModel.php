<?php

namespace Model\Auth;


use Model\BaseModel;
use Model\Users\UsersRelationModel;

class AuthUserModel extends BaseModel
{
    protected $table = 'auth_user';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 用户咨询师扩展
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne(AuthUserTeacherModel::class, 'user_id', 'id');
    }

    /**
     * @describe 用户倾听者扩展
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function listen()
    {
        return $this->hasOne(AuthUserListenModel::class, 'user_id', 'id');
    }

    /**
     * @describe 用户作家扩展
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function write()
    {
        return $this->hasOne(AuthUserWriteModel::class, 'user_id', 'id');
    }

    /**
     * @describe 用户答疑馆身份
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qaIdentity()
    {
        return $this->hasMany(AuthUserQaModel::class, 'user_id', 'id');
    }

    /**
     * @describe 用户信息统计
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statistics()
    {
        return $this->hasOne(AuthUserStatisticsModel::class, 'user_id', 'id');
    }

    /**
     * @describe 用户详细资料
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function base()
    {
        return $this->hasOne(AuthUserBaseModel::class, 'user_id', 'id');
    }
}