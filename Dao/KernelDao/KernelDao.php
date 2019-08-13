<?php

namespace Dao\KernelDao;


use Carbon\Carbon;
use Cron\CronExpression;
use Model\Kernel\KernelModel;

class KernelDao
{
    protected $isFirst = false;

    /**
     * @describe 初始化任务
     * @param KernelModel $task 任务模型
     * @return void
     */
    public function initializeTask(KernelModel $task)
    {
        $objCronExpression     = CronExpression::factory(trim($task->execute_time));
        $strNextStartTime      = $objCronExpression->getNextRunDate()->format('Y-m-d H:i:s');
        $task->next_start_time = $strNextStartTime;

        if (empty($task->first_start_time)) {
            // 任务初始化
            $task->first_start_time = Carbon::now()->toDateTimeString();
            $task->save();
            $this->isFirst          = true;
            return;
        }

        $task->status          = KernelModel::STATUS_RUNNING;
        $task->last_start_time = Carbon::now()->toDateTimeString();
        $task->save();
    }

    /**
     * @describe 任务完成
     * @param KernelModel $task 任务模型
     * @return void
     */
    public function complete(KernelModel $task)
    {
        $task->status = KernelModel::STATUS_WAIT;
        $task->save();
    }

    /**
     * @describe 获取需要执行的任务
     * @return \Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getTasks()
    {
        return KernelModel::onWriteConnection()
            ->where('status', KernelModel::STATUS_WAIT)
            ->where(function ($query) {
                $query->where('next_start_time', '<=', Carbon::now()->toDateTimeString())
                    ->orWhereNull('first_start_time')
                    ->orWhereNull('next_start_time');
            })
            ->get();
    }

    /**
     * @describe 判断任务是否初次执行
     * @return bool
     */
    public function isFirstExecuteTask()
    {
        return ($this->isFirst);
    }

    /**
     * @describe 获取正在运行任务并加读锁
     * @return \Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getRunningTasks()
    {
        return KernelModel::where('status', KernelModel::STATUS_RUNNING)
            ->sharedLock()
            ->get();
    }

    /**
     * @describe 清空执行参数
     * @param string $executeClass 执行类名
     * @return boolean
     */
    public function clearTaskParameter(string $executeClass)
    {
        return KernelModel::where('execute_class', $executeClass)
            ->update(['execute_param' => '']);
    }
}