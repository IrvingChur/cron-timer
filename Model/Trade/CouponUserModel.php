<?php

namespace Model\Trade;


use Model\BaseModel;

class CouponUserModel extends BaseModel
{
    protected $table = 'coupon_user';
    protected $guarded = [];
    protected $connection = 'trade';
    public $timestamps = false;


}