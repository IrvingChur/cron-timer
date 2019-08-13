<?php

namespace Dao\QaDao;


use Model\Qa\QaQuestionModel;

class QaQuestionDao
{
    /**
     * @describe 获取大于这个时间的问题
     * @param string $time 时间
     * @return array
     */
    public function getQuestionByTime(array $field, string $time)
    {
        return QaQuestionModel::select(...$field)
            ->where('created', '>', $time)
            ->get();
    }
}