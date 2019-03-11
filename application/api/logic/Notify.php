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


namespace app\api\logic;

use think\Db;
use think\Exception;
use think\Log;
use app\common\library\enum\OrderStatusEnum;

class Notify extends BaseApi
{

    /**
     * 支付回调助手
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @return bool
     *
     */
    public function handle($data){
        Db::startTrans();
        try{
            //获取支付订单号
            $trade_no = $data['out_trade_no'];
            //查找订单
            $order = $this->modelOrders->where(['trade_no'=>$trade_no])->lock(true)->find();
            //超时判断 （超时10分钟当作失败订单）  判断状态
            if ($order->status == 1 && bcsub(time(), $order->create_time) <= 600) {
                Log::notice('更新订单状态');
                //更新订单状态
                $this->updateOrderInfo($order, true);
                Log::notice('异步消息商户');
                //异步消息商户
                $this->logicOrdersNotify->saveOrderNotify($order);
                Log::notice('提交队列');
                $this->logicQueue->pushJobDataToQueue('AutoOrderNotify' , $order , 'AutoOrderNotify');
                //提交更改
                Db::commit();

                return true;
            }
            Log::error('单号' . $trade_no . '超时处理');
            return false;
        } catch (Exception $ex) {
            Db::rollback();
            Log::error('错误'.$ex->getMessage());
        }

        return true;
    }

    /**
     * 更新支付订单数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     * @param $success
     */
    private function updateOrderInfo($order, $success)
    {

        /*************订单操作************/
        //1.查找用户对应渠道费率
        $profit = $this->logicUser->getUserProfitInfo(['uid' => $order->uid, 'cnl_id' => $order->cnl_id]);
        $account = $this->logicPay->getAccountInfo(['id' => $order->cnl_id]);
        if(empty($profit)) $profit = $account;
        //2.数据计算
        //实付金额 - 扣除渠道费率后
        $income =  bcsub($order->amount , bcmul($order->amount,$account['rate'],3),  3);
        $agent_in = "0.000";
        //商户收入
        $user_in =bcmul($income, $profit['urate'], 3);
        //是否有代理
        if ($order->puid != 0){
            //1.获取代理的费率
            $agent_profit = $this->logicUser->getUserProfitInfo(['uid' => $order->puid, 'cnl_id' => $order->cnl_id]);
            //2.代理收入
            $agent_in = bcsub($income, bcmul($income, $agent_profit['urate'], 3),3);
            //3.商户收入
            $user_in = bcmul($income, bcmul($agent_profit['urate'], $profit['urate'], 3),3);
            /*************写入商户代理资金******************/
            //支付成功  写入结算金额
            $this->logicBalanceChange->creatBalanceChange($order->puid,$agent_in,'商户单号'. $order->out_trade_no . '支付成功，代理分润金额转入','enable',false);
            /**************写入商户代理资金结束*****************/
        }
        /*************写入商户资金******************/
        //支付成功  扣除待支付金额 (这个操作就只有两个地方   自动关闭订单和这里)
        $this->logicBalanceChange->creatBalanceChange($order->uid,$order->amount,'单号'. $order->out_trade_no . '支付成功，收入至待结算金额','disable',true);
        //支付成功  写入结算金额
        $this->logicBalanceChange->creatBalanceChange($order->uid,$user_in,'单号'. $order->out_trade_no . '支付成功，待支付金额转入','enable',false);
        /**************写入商户资金结束*****************/
        //平台收入
        $platform_in = bcsub($income, bcadd($user_in,$agent_in,3),3);
        //3.数据存储
        $this->modelOrders->changeOrderStatusValue([
            'income'    => $income,
            'user_in'    => $user_in,
            'agent_in'    => $agent_in,
            'platform_in'    => $platform_in,
            'status'  => $success ? OrderStatusEnum::PAID : OrderStatusEnum::UNPAID
        ], [
            'id'=>$order->id
        ]);
    }
}
