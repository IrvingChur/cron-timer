<?php

namespace Model\Common;


use Model\BaseModel;

class CommonCustomeTagShotModel extends BaseModel
{
    const VALID   = 1;
    const INVALID = 2;
    const ZIXUN   = 6;

    const COLLEGE_NORMAL_TAG = 4;
    const COLLEGE_PRO_TAG    = 2126;

    const STATUS_NORMAL = 1;
    const STATUS_BAN    = 2;

    protected $table = 'common_custome_tag_shot';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 子级标签
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}