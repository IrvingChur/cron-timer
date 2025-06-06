<?php
namespace Model\Common;

use Model\BaseModel;

class CommonZhangdanModel extends BaseModel
{
    const CHANNEL_YIXINLI = 'yixinli';
    const CHANNEL_YIDA = 'yida';
    const CHANNEL_QA = 'qa';
    const CHANNEL_ARTICLE = 'article';

    const STATUS_INCOME_PENDING = 0; // 未结算
    const STATUS_INCOME_SUCCESS = 1; // 已结算
    const STATUS_WITHDRAW_PENDING = 2; // 提现中
    const STATUS_WITHDRAW_SUCCESS = 3; // 提现完成
    const STATUS_CANCEL = 10; // 账单取消

    const OBJECT_NAME_ZX_YUYUE = 'zixun.yuyue';
    const OBJECT_NAME_ZX_QINGTING = 'zixun.qingsu';
    const OBJECT_NAME_ZX_DAREN = 'zixun.daren';
    const OBJECT_NAME_ZX_DOCTOR = 'zixun.doctor';
    const OBJECT_NAME_QA_REWARD = 'qa.reward';
    const OBJECT_NAME_QA_POST_REWARD = 'qa.post.reward';
    const OBJECT_NAME_ARTICLE_REWARD = 'article.reward';
    const OBJECT_NAME_USER_REWARD = 'user.reward';
    const OBJECT_NAME_QA_LEVEL_REWARD = 'qa.level.reward';
    const OBJECT_NAME_QA_FIRST_ANSWER_REWARD = 'qa.level.first.answer.reward';

    protected $table = 'common_zhangdan';
    protected $guarded = [];

    public $timestamps = false;
}