<?php

namespace Dao\QaDao;


use Illuminate\Database\Capsule\Manager;

class QaAnswerAttributeTagDao
{
    /**
     * @describe 获取用户优质与精华回答统计
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return array
     */
    public function getUserSpecialCount(int $userId, string $startTime, string $endTime)
    {
        $arrQueryResponse = Manager::table('qa_answer_attribute_tag')
            ->select('answerer_operation_tag_id', Manager::raw('count(1) as total'))
            ->whereIn('answer_id', function ($query) use ($userId) {
                $query->select('id')
                    ->from('qa_answer')
                    ->where('qa_answer.user_id', $userId);
            })
            ->whereBetween('created', [$startTime, $endTime])
            ->whereIn('answerer_operation_tag_id', [10, 11])
            ->groupBy('answerer_operation_tag_id')
            ->get();

        // 返回精华回答和优质回答整合
        $intQuality      = 0; // 优质回答数
        $intQuintessence = 0; // 精华回答数

        foreach ($arrQueryResponse as $item) {
            if ($item->answerer_operation_tag_id == 10) {
                $intQuality      = $item->total;
            } else {
                $intQuintessence = $item->total;
            }
        }

        return ['quality' => $intQuality, 'quintessence' => $intQuintessence];
    }
}