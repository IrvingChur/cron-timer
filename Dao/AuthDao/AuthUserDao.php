<?php

namespace Dao\AuthDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Auth\AuthUserModel;

class AuthUserDao
{
    /**
     * @describe 根据用户ID获取用户列表
     * @param array $field 获取字段
     * @param array $ids 用户ID列表
     * @return Collection
     */
    public function getUsersByIds(array $field, array $ids)
    {
        return AuthUserModel::select(...$field)
            ->whereIn('id', $ids)
            ->get();
    }
}