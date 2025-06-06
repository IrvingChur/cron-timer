<?php

namespace Model\Qa;


use Model\BaseModel;

class QaLevelModel extends BaseModel
{
    const LEVEL_ELEMENTARY = 1;
    const LEVEL_TWO        = 2;
    const LEVEL_CRITICAL   = 3;

    protected $table = 'qa_level';
    protected $guarded = [];

    public $timestamps = false;

    // 一对一弹窗模板信息
    public function popupWindowTemplate()
    {
        return $this->hasOne(QaLevelTemplateModel::class, 'id', 'popup_window');
    }

    // 一对一系统模板信息
    public function systemInformTemplate()
    {
        return $this->hasOne(QaLevelTemplateModel::class, 'id', 'system_inform');
    }
}