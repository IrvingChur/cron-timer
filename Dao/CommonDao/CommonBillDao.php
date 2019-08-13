<?php

namespace Dao\CommonDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Common\CommonBillModel;
use Model\Qa\QaAnswerModel;

class CommonBillDao
{
    /**
     * @describe 创建账单
     * @param int $userId 用户ID
     * @param string $objectName 账单标识
     * @param int $objectId 账单对象ID
     * @param float $totalFee 总收入
     * @param float $serviceFee 服务费
     * @param float $actualFee 实际收入
     * @param string $orderGoodsId 订单ID
     * @param string $channel 频道
     * @return void
     */
    public function createBill(int $userId, string $objectName, int $objectId, float $totalFee, float $serviceFee, float $actualFee, string $orderGoodsId, string $channel)
    {
        $objBillModel = new CommonBillModel();
        $objBillModel->user_id     = $userId;
        $objBillModel->object_name = $objectName;
        $objBillModel->object_id   = $objectId;
        $objBillModel->total_fee   = $totalFee;
        $objBillModel->service_fee = $serviceFee;
        $objBillModel->actual_fee  = $actualFee;
        $objBillModel->dingdan_id  = $orderGoodsId;
        $objBillModel->channel     = $channel;
        $objBillModel->save();
    }

    /**
     * @describe 奖励统计
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @param array $objectNameList 奖励类型
     * @return float
     */
    public function rewardCount(int $userId, string $startTime, string $endTime, array $objectNameList)
    {
        return CommonBillModel::where('user_id', $userId)
            ->whereIn('object_name', $objectNameList)
            ->whereBetween('created', [$startTime, $endTime])
            ->sum('actual_fee');
    }

    /**
     * @describe 获取时间段内奖励用户
     * @param array $field 字段
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @param array $objectNameList 奖励类型
     * @return Collection [QaAnswerModel]
     */
    public function getSectionRewardUserList(array $field, string $startTime, string $endTime, array $objectNameList)
    {
        $arrUserIds = CommonBillModel::select('user_id')
            ->whereIn('object_name', $objectNameList)
            ->whereBetween('created', [$startTime, $endTime])
            ->pluck('user_id')
            ->toArray();

        if (empty($arrUserIds)) {
            return new Collection();
        }

        return QaAnswerModel::select(...$field)
            ->whereIn('user_id', $arrUserIds)
            ->groupBy('user_id')
            ->get();
    }
}