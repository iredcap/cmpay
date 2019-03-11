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

use app\common\library\enum\OrderStatusEnum;
use app\common\logic\BalanceChange;
use app\common\model\Balance;
use app\common\model\Orders;
use app\common\library\exception\OrderException;
use think\Db;
use think\Log;
use think\queue\Job;

class AutoOrderClose
{
    /**
     * fire方法是消息队列默认调用的方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Job $job
     * @param $data
     * @throws \think\exception\DbException
     */
    public function fire(Job $job,$data){
        // 如有必要,可以根据业务需求和数据库中的最新数据,判断该任务是否仍有必要执行.
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }
        //处理队列
        $isJobDone = $this->doJob($data);

        if ($isJobDone) {
            //如果任务执行成功， 记得删除任务
            $job->delete();
            print("<info>The Order Job  ID " . $data['id'] ."  has been close and deleted."."</info>\n");
            Log::notice("The Order Job  ID " . $data['id'] ."  has been close and deleted");
        }else{
            // 也可以重新发布这个任务
            print("<info>The Order Job ID " . $data['id'] ." will be availabe again after 1 min."."</info>\n");
            $job->release(120); //$delay为延迟时间，表示该任务延迟2分钟后再执行

        }
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
     * @throws \think\exception\DbException
     */
    private function doJob($data) {
        //超时10分钟 10*60 300处理  否则继续存入队列  时间bug  待修复
        if (bcsub( time(), $data['create_time']) >= 600){

            //检查订单是否处理  防止多次扣除
            $order =  (new Orders())->getTradeOrder($data['trade_no']);

            if($order && $order['status'] == OrderStatusEnum::CLOSE || $order['status'] == OrderStatusEnum::PAID){
                print("<info>The Order Job  ID " . $data['id'] ."  has been paid or close."."</info>\n");
                Log::notice("ID " . $data['id'] ." has been paid or close");
                return true;
            }

            Db::startTrans();
            try {
                //关闭订单
                (new Orders())->changeOrderStatusValue([
                        'status' => OrderStatusEnum::CLOSE,
                        'update_time' => time()
                    ],
                    ['id' => $data['id']]);

                //提交更改
                Db::commit();
                return true;
            } catch (\Exception $e) {
                Db::rollback();
                Log::error('AutoOrderClose Error:[' . $e->getMessage() . ']');
                return false;
            }

        }
        return false;
    }
}