<?php

namespace Model\Common;


use Model\Auth\AuthUserModel;
use Model\BaseModel;

class CommonArticleModel extends BaseModel
{
    // 标题正常状态
    const ARTICLE_STATUS_NORMAL = 0;
    // 标题封禁状态
    const ARTICLE_STATUS_BAN    = 1;
    // 文章发布状态
    const ARTICLE_PUBLISH   = 0;
    // 文章草稿状态
    const ARTICLE_DRAFT     = -1;
    // 文章推荐状态
    const ARTICLE_RECOMMEND = 1;

    protected $table = 'common_article';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @describe 文章所属用户对应关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AuthUserModel::class, 'user_id', 'id')
            ->withDefault([
                'id' => 0,
            ]);
    }

    /**
     * @describe 文章所属内容对应关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo(CommonArticleContentModel::class, 'id', 'object_id');
    }
}