<?php

namespace Model\Common;


use Model\BaseModel;

class CommonTemporaryBillModel extends BaseModel
{
    // 未开通打赏
    const STATUS_WAIT = 0;
    // 已开通并迁移
    const STATUS_GONE = 1;

    protected $table = 'common_temporary_bill';
    protected $guarded = [];

    public $timestamps = false;
}