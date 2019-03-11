<?php
/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */

namespace app\common\service\worker;


use app\common\model\BalanceCash;
use think\Log;
use think\queue\Job;

class AutoBalanceCash
{
    /**
     * fire方法是消息队列默认调用的方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Job $job
     * @param $data
     *
     */
    public function fire(Job $job,$data){
        // 如有必要,可以根据业务需求和数据库中的最新数据,判断该任务是否仍有必要执行.
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }
        //查单
        $order = (new BalanceCash())->where(['cash_no' => $data['cash_no']]);
        //处理队列
        $isJobDone = $this->doJob($data);
        if ($isJobDone){
            $order->update([
                'status' => 2
            ]);
            //返回正确  处理数据
            print("<info>The Cash Job  ID " . $data['id'] ."  has been close and deleted."."</info>\n");
            Log::notice("The Job Job  ID " . $data['id'] ."  has been close and deleted");
        }
        // 仅执行一次
        $job->delete();
    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data array
     * @return bool
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){

        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return bool
     */
    private function doJob($data) {
        try{
            Log::notice(json_encode($data));

            //提现操作请自行处理
            return true;
        }catch (\Exception $e){
            Log::error('Auto Balance Cash Faid:'.$e->getMessage());
            return false;
        }
    }
}