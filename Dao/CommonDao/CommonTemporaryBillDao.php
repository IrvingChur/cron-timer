<?php

namespace Dao\CommonDao;


use Model\Common\CommonTemporaryBillModel;

class CommonTemporaryBillDao
{
    /**
     * @describe 创建临时账单
     * @param int $userId 用户ID
     * @param string $objectName 账单类型
     * @param int $objectId 目标ID
     * @param float $totalFee 总收入
     * @param float $serviceFee 服务费
     * @param float $actualFee 实际收入
     * @param string $channel 账单所属业务
     * @param string|null $extra 扩展
     * @param int|null $status 状态
     * @return void
     */
    public function createTemporaryBill(int $userId, string $objectName, int $objectId, float $totalFee, float $serviceFee, float $actualFee, string $channel, ?string $extra = '', ?int $status = 0)
    {
        $objTemporaryBill = new CommonTemporaryBillModel();
        $objTemporaryBill->user_id     = $userId;
        $objTemporaryBill->object_name = $objectName;
        $objTemporaryBill->object_id   = $objectId;
        $objTemporaryBill->total_fee   = $totalFee;
        $objTemporaryBill->service_Fee = $serviceFee;
        $objTemporaryBill->actual_fee  = $actualFee;
        $objTemporaryBill->channel     = $channel;
        $objTemporaryBill->extra       = $extra;
        $objTemporaryBill->status      = $status;
        $objTemporaryBill->save();
    }

    /**
     * @describe 收益统计
     * @param int $userId 用户ID
     * @param string $startTime 开始时间
     * @param string $endTime 结束时间
     * @param array $objectNameList 奖励类型
     * @return float
     */
    public function rewardCount(int $userId, string $startTime, string $endTime, array $objectNameList)
    {
        return CommonTemporaryBillModel::where('user_id', $userId)
            ->whereIn('object_name', $objectNameList)
            ->whereBetween('created', [$startTime, $endTime])
            ->sum('actual_fee');
    }
}