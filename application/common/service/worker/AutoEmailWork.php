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


use app\common\library\Activation;
use app\common\library\Mail;
use think\Log;
use think\queue\Job;

class AutoEmailWork

{
    /**
     * fire方法是消息队列默认调用的方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Job $job
     * @param array $data
     */
    public function fire(Job $job,$data){
        // 如有必要,可以根据业务需求和数据库中的最新数据,判断该任务是否仍有必要执行.
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }
        $job->delete();
        //处理队列
        $isJobDone = $this->doJob($data);

        if ($isJobDone) {
            //如果任务执行成功， 记得删除任务
            $job->delete();
            print("<info>The Order Job has been done and deleted"."</info>\n");
        }else{
            //通过这个方法可以检查这个任务已经重试了几次了
            if ($job->attempts() >= 3) {
                print("<warn>The Order Job has been deleted and retried more than {$job->attempts()} times!"."</warn>\n");
                $job->delete();
            }else{
                // 也可以重新发布这个任务
                print("<info>The Order Job will be availabe again after 1 min."."</info>\n");
                $job->release(60); //$delay为延迟时间，表示该任务延迟1分钟后再执行

            }

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
     * @param array $data
     * @return bool
     */
    private function doJob($data) {
        Log::notice('Queue Data: [ '.  json_encode($data).'].');
        // 1.判断场景   ----后续优化  暂时先这样
        switch ($data['scene']){
            //  注册邮件处理
            case 'register':
                (new Activation())->sendActiveCode(arr2obj($data));
                break;
            // 验证码
            case 'regcallback':
                (new Activation())->sendRegCallback(arr2obj($data));
                break;
            // 验证码
            case 'verify':
                Log::notice('Queue Fail: [ Verify Disable].');
                break;
            default:
                Log::notice('Queue Fail: [ AutoEmail No Scene].');
                break;
        }
        return true;
    }
}