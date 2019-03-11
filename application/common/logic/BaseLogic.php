<?php

/**
 * +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *  | Copyright (c) 2019 知行信息科技. All rights reserved.
 * +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

namespace app\common\logic;

use app\common\model\BaseModel;
use think\facade\Cache;
use think\facade\Log;
use think\Queue;

class BaseLogic extends BaseModel
{

    /**
     * 当日API访问次数限制
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $key
     * @param int $num
     *
     * @return bool|false|int
     */
    public function checkFrequent($key= '',$num = 5){
        //API 访问限制
        $name = !empty($key) ? $key : 'client-ip:' . request()->ip();
        $cache = Cache::store('redis');
        $value = $cache->get($name);
        //没有数据
        if (!$value) {
            // 写入ip
            $cache->set($name, 0, 36000);
        }
        //一天内 次数超过 $num 次 停止本次请求
        if ($value >= $num) {
            Log::error("Trigger restriction and flow control");
            return false;
        }
        //正常范围跟 自增一次
        return $cache->inc($name);
    }

    /**
     * 往一个队列Push数据
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
        $jobHandlerClassName  = "app\\common\\jobs\\".$jobClassName;
        // 推送
        $isPushed = Queue::push( $jobHandlerClassName , $jobData , $jobQueueName );
        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if( $isPushed !== false ){
            Log::notice(date('Y-m-d H:i:s') . " a new {$jobQueueName} Job {$isPushed} is Pushed to the MQ");
        }else{
            Log::error( 'Oops, something went wrong.');
        }
        return $isPushed;
    }
}