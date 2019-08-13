<?php

namespace Dao\QaDao;


use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Collection;
use Model\Qa\QaAnswerModel;

class QaAnswerDao
{
    /**
     * @describe 创建回答
     * @param int $userId 用户ID
     * @param string $nickname 用户昵称
     * @param int $questId 问题ID
     * @param string $content 回答内容
     * @param string|null $origin 来源
     * @param string|null $createTime 创建时间
     * @return void
     */
    public function createAnswer(int $userId, string $nickname, int $questId, string $content, ?string $origin = 'm', ?string $createTime = null)
    {
        $objAnswerModel = new QaAnswerModel();
        $objAnswerModel->user_id  = $userId;
        $objAnswerModel->nickname = $nickname;
        $objAnswerModel->question_id = $questId;
        $objAnswerModel->content  = $content;
        $objAnswerModel->laiyuan  = $origin;
        $objAnswerModel->created  = $createTime ?: Carbon::now()->toDateTimeString();
        $objAnswerModel->save();
    }

    /**
     * @describe 获取用户回答统计
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return integer
     */
    public function userAnswerCount(int $userId, string $startTime, string $endTime)
    {
        return QaAnswerModel::where('user_id', $userId)
            ->whereBetween('created', [$startTime, $endTime])
            ->count();
    }

    /**
     * @describe 获取用户信息
     * @param int $userId 用户ID
     * @param array $field 获取字段
     * @return QaAnswerModel
     */
    public function getUserInfo(int $userId, array $field = ['*'])
    {
        return QaAnswerModel::select(...$field)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * @describe 统计用户回复字数
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return integer
     */
    public function statisticsAnswerNumber(int $userId, string $startTime, string $endTime)
    {
        return QaAnswerModel::where('user_id', $userId)
            ->whereBetween('created', [$startTime, $endTime])
            ->sum(Manager::raw('char_length(content)'));
    }

    /**
     * @describe 获取用户首次回答时间
     * @param int $userId 用户ID
     * @return string
     */
    public function getUserFirstAnswerDate(int $userId)
    {
        $objAnswerModel = QaAnswerModel::select('created')
            ->where('user_id', $userId)
            ->orderBy('created', 'asc')
            ->first();

        return $objAnswerModel->created;
    }

    /**
     * @describe 获取用户首答数量
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @param bool $isSpecial 筛选星标
     * @return integer
     */
    public function getFirstAnswerNumber(int $userId, string $startTime, string $endTime, ?bool $isSpecial = false)
    {
        return QaAnswerModel::where('user_id', $userId)
            ->whereBetween('created', [$startTime, $endTime])
            ->when($isSpecial, function ($query) {
                $query->whereHas('answerAttributeTag', function ($query) {
                    $query->whereIn('answerer_operation_tag_id', [10, 11]);
                });
            })
            ->whereNotExists(function ($query) {
                $query->select(Manager::raw(1))
                    ->from('qa_answer as child_qa_answer')
                    ->whereRaw('child_qa_answer.question_id = qa_answer.question_id and child_qa_answer.created < qa_answer.created');
            })
            ->count();
    }

    /**
     * @describe 获取有特殊标签回答用户
     * @param array $field 获取字段
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection
     */
    public function getSpecialAnswerUserList(array $field, string $startTime, string $endTime)
    {
        $objQaAnswerList = QaAnswerModel::select(...$field)
            ->whereHas('answerAttributeTag', function ($query) use ($startTime, $endTime) {
                $query->whereBetween('created', [$startTime, $endTime]);
            })
            ->get();

        return $objQaAnswerList->unique();
    }

    /**
     * @describe 获取唯一回答用户
     * @param array $field 获取字段
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @return Collection
     */
    public function getUniquenessAnswerUserList(array $field, bool $isAll, ?string $startTime = '', ?string $endTime = '')
    {
        return QaAnswerModel::select(...$field)
            ->when(!$isAll, function ($query) use ($startTime, $endTime) {
                $query->whereBetween('created', [$startTime, $endTime]);
            })
            ->groupBy('user_id')
            ->get();
    }
}