<?php

namespace Dao\SnsDao;


use Model\Sns\SnsWeChatModel;

class SnsWeChatDao
{
    /**
     * @describe 获取用户unionID
     * @param int $userId 用户ID
     * @return string
     */
    public function getUserUnionId(int $userId)
    {
        return SnsWeChatModel::select('unionid')
            ->where('user_id', $userId)
            ->pluck('unionid');
    }
}