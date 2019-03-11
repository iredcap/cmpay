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

namespace app\common\logic;

use think\Queue as Service;
use think\Log;

/**
 * 队列处理
 * 暂时这么处理
 *  后续再优化
 *  不然现在重复的太多了
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Queue extends BaseLogic
{

    /**
     * 往一个队列Push数据(测试)
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $jobClassName
     * @param $jobData
     * @param $jobQueueName
     *
     * @return mixed
     */
    public function pushJobDataToQueue($jobClassName , $jobData , $jobQueueName){
        // 消费者实现类
        $jobHandlerClassName  = "app\\common\\service\\worker\\".$jobClassName;
        // 推送
        $isPushed = Service::push( $jobHandlerClassName , $jobData , $jobQueueName );
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if( $isPushed !== false ){
            Log::notice(date('Y-m-d H:i:s') . " a new {$jobQueueName} Job {$isPushed} is Pushed to the MQ");
        }else{
            Log::error( 'Oops, something went wrong.');
        }
        return $isPushed;
    }

}