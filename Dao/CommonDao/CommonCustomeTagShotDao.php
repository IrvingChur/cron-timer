<?php

namespace Dao\CommonDao;


use Model\Common\CommonCustomeTagShotModel;

class CommonCustomeTagShotDao
{
    /**
     * @describe 获取运营标签快照
     * @param int $customeTagId 标签ID
     * @return CommonCustomeTagShotModel
     */
    public function getCustomeTag(array $field, int $customeTagId)
    {
        return CommonCustomeTagShotModel::select($field)
            ->where('custome_tag_id', $customeTagId)
            ->where('status', CommonCustomeTagShotModel::STATUS_NORMAL)
            ->first();
    }
}