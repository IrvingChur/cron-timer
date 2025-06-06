<?php


namespace Model\Common;


use Model\BaseModel;

class CommonCustomeTagModel extends BaseModel
{
    protected $table = 'common_custome_tag';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 自连一对多
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, "pid", "id");
    }
}