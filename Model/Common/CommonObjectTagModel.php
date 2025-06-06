<?php

namespace Model\Common;


use Model\Auth\AuthUserModel;
use Model\BaseModel;

class CommonObjectTagModel extends BaseModel
{
    protected $table = 'common_object_tag';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 标签详情
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tag()
    {
        return $this->hasOne(CommonCustomeTagModel::class, "id", "tag_id");
    }
}