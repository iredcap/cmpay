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

namespace app\common\service\command;


use app\common\logic\Queue;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Log;

/**
 * 推送等待订单至队列   5分钟一次
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class PushOrderWait extends Command
{

    /**
     * 配置定时器的信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    protected function configure()
    {
        $this->setName('PushOrderWait')->setDescription('Push Order Wait   1/5min');
    }

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     */
    protected function execute(Input $input, Output $output)
    {
        // 输出到日志文件
        $output->writeln("Push Order Wait start");
        // 定时器需要执行的内容

        try{
            $resArr =Db::table('cm_orders')
                ->where(['status'   => 1 ])->whereTime('create_time','d')
                ->select();
            foreach ($resArr as $v){
                $output->writeln("Push Order Wait end.... Data:".json_encode($v['id']));
                //写入订单超时队列
                Log::notice('拉取订单加入超时队列');
                (new Queue())->pushJobDataToQueue('AutoOrderClose' , $v , 'AutoOrderClose');
            }

        }catch (\Exception $e){
            Log::error("Push Order Fail:[".$e->getMessage()."]");
        }

        // .....
        $output->writeln("Push Order Wait end....");
    }
}