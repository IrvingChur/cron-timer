<?php

namespace Model\Common;

use Model\BaseModel;

class CommonDingdanModel extends BaseModel
{
    const PAY_STATUS_NOTPAY = 'notpay';
    const PAY_STATUS_SUCCESS = 'success';
    const PAY_STATUS_WAITING = 'waiting';
    const PAY_STATUS_REFUND = 'refund';
    const PAY_STATUS_REPAY = 'repay';
    const PAY_STATUS_CANCEL = 'cancel';

    const OBJECT_NAME_ZX_YUYUE = 'zixun.yuyue';
    const OBJECT_NAME_CESHI_CESHI = 'ceshi.ceshi';
    const OBJECT_NAME_ZX_QINGTING = 'zixun.qingsu';
    const OBJECT_NAME_COLLEGE_LESSON = 'college_lesson';
    const OBJECT_NAME_YIDA_QA = 'yida_qa';
    const OBJECT_NAME_QA_REWARD = 'qa.reward';
    const OBJECT_NAME_QA_POST_REWARD = 'qa.post.reward';
    const OBJECT_NAME_ARTICLE = 'article.reward';
    const OBJECT_NAME_USER_REWARD = 'user.reward';

    const DINGDAN_PAY_NAME         = 'yixinli';
    const DINGDAN_PAY_NAME_COLLEGE = 'college';
    const DINGDAN_PAY_NAME_CESHI   = 'yixinlicp';

    //订单来源
    const PAY_FROM_YIXINLI_APP = 'yixinli_app';
    const PAY_FROM_YIXINLI_MOBI = 'yixinli_mobi';
    const PAY_FROM_YIXINLI_WWW = 'yixinli_pc';

    protected $table = 'common_dingdan';
    protected $guarded = [];

    public $timestamps = false;
}
