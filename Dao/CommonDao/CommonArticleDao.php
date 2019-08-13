<?php

namespace Dao\CommonDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Common\CommonArticleModel;

class CommonArticleDao
{
    /**
     * @describe 获取开始与结束时间之间的文章
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection
     */
    public function getBetweenByCreateTime(string $startTime, string $endTime, ?array $field = ['*'], ?bool $normal = false)
    {
        return CommonArticleModel::select(...$field)
            ->when($normal, function ($query) {
                $query->where('jin', CommonArticleModel::ARTICLE_STATUS_NORMAL);
            })
            ->with([
                'user' => function($query) {
                    $query->select('id', 'nickname');
                }])
            ->whereIn('is_completed', [CommonArticleModel::ARTICLE_PUBLISH, CommonArticleModel::ARTICLE_RECOMMEND])
            ->whereBetWeen('created', [$startTime, $endTime])
            ->orderBy('created', 'desc')
            ->get();
    }
}