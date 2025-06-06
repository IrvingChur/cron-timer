<?php


namespace Model\Qa;


use Model\BaseModel;

class QaCantonalUserModel extends BaseModel
{
    // 成员类型
    const CANTONAL_MASTER = 0;  // 分馆馆主
    const CANTONAL_MEMBER = 1;  // 分馆成员

    protected $table   = 'qa_cantonal_user';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 答疑馆分馆用户成员与答疑馆分馆动态一对多关系
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cantonalDynamic()
    {
        return $this->hasMany(QaCantonalDynamicModel::class, "user_id", "user_id");
    }
}