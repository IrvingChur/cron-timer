<?php

namespace Dao\CommonDao;


use Model\Common\CommonNoticeModel;

class CommonNoticeDao
{
    /**
     * @describe 创建消息
     * @param int $fromUserId 来自用户ID
     * @param int $toUserId 发送给用户ID
     * @param string $actionName 动作名字
     * @param string $objectName 目标名字
     * @param int $objectId 目标ID
     * @param string $objectTitle 标题
     * @param string $content 内容
     * @return void
     */
    public function createNotice(int $fromUserId, int $toUserId, string $actionName, string $objectName, int $objectId, string $objTitle, string $content)
    {
        $objCommonNoticeModel = new CommonNoticeModel();
        $objCommonNoticeModel->from_user_id = $fromUserId;
        $objCommonNoticeModel->to_user_id   = $toUserId;
        $objCommonNoticeModel->action_name  = $actionName;
        $objCommonNoticeModel->object_name  = $objectName;
        $objCommonNoticeModel->object_id    = $objectId;
        $objCommonNoticeModel->object_title = $objTitle;
        $objCommonNoticeModel->content      = $content;
        $objCommonNoticeModel->save();
    }
}