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


use app\common\library\enum\CodeEnum;
use app\common\library\exception\OrderException;
use think\Db;
use think\Log;

class Orders extends BaseLogic
{

    /**
     *
     * 获取订单列表
     *
     * @author 勇敢的小笨羊
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     */
    public function getOrderList($where = [], $field = true, $order = 'create_time desc', $paginate = 15)
    {
        $this->modelOrders->limit = !$paginate;
        return $this->modelOrders->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取订单信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool|string $field
     *
     * @return mixed
     */
    public function getOrderInfo($where = [], $field = true){
        return $this->modelOrders->getInfo($where, $field);
    }

    /**
     * 获取订单异步信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     *
     * @return mixed
     */
    public function getOrderNotify($where = [], $field = true){
        return $this->modelOrdersNotify->getInfo($where, $field);
    }

    /**
     * 获取单总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getOrdersCount($where = []){
        return $this->modelOrders->getCount($where);
    }

    /**
     * 订单统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return array
     */
    public function getOrdersAllStat($where = []){
        return [
            'fees' => $this->modelOrders->getInfo($where,"COALESCE(sum(amount),0) as total,COALESCE(sum(if(status=2,amount,0)),0) as paid,COALESCE(sum(user_in),0) as user,COALESCE(sum(agent_in),0) as agent,COALESCE(sum(platform_in),0) as platform")
        ];
    }

    /**
     * 获取控制台统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return array
     */
    public function getWelcomeStat($where = []){
        $order = 'create_time desc';
        return [
            'order' => $this->modelOrders->getInfo($where,"count(id) as total,count(if(status=2,true,null)) as success,count(if(status=1,true,null)) as wait,count(if(status=0,true,null)) as failed,COALESCE(sum(amount),0) as fees,COALESCE(sum(if(status=1,amount,0)),0) as unpaid,COALESCE(sum(if(status=2,amount,0)),0) as paid", $order, $paginate = false),
            'user'  => $this->modelUser->getInfo($where,"count(uid) as total,count(if(is_verify=0,true,null)) as failed", $order, $paginate = false),
            'cash' => $this->modelBalanceCash->getInfo($where,'count(id) as total,count(if(status=2,true,null)) as success,count(if(status=1,true,null)) as wait,COALESCE(sum(if(status=0,amount,0)),0) as failed', $order, $paginate = false),
            'fees' => $this->modelOrders->getInfo($where,"COALESCE(sum(amount),0) as total,COALESCE(sum(if(status=2,amount,0)),0) as paid")
        ];
    }

    /**
     * 年月订单以及交易额统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @return array|mixed
     */
    public function getOrdersMonthStat($where = []){
        $this->modelOrders->group = 'month';
        return $this->modelOrders->getList($where,"count(id) as total_orders,COALESCE(sum(`amount`),0) as total_amount,FROM_UNIXTIME(create_time,'%m') as month",false,false);
    }

    /**
     * 获取商户订单统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     */
    public function getOrderUserStat($where = [],$field = "uid,count(uid) as total_orders,COALESCE(sum(amount),0) as total_fee_all,COALESCE(sum(if(status=1,amount,0)),0) as total_fee_dis,COALESCE(sum(if(status=2,amount,0)),0) as total_fee_paid",$order = 'create_time desc', $paginate = 15){
        $this->modelOrders->group = 'uid';
        return $this->modelOrders->getList($where,$field, $order, $paginate = false);
    }

    /**
     * 获取渠道订单统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $order
     * @param int $paginate
     * @return mixed
     */
    public function getOrderChannelStat($where = [],$field = "a.cnl_id,count(a.cnl_id) as total_orders,COALESCE(sum(a.amount),0) as total_fee_all,COALESCE(sum(if(a.status = 1,a.amount,0)),0) as total_fee_dis,COALESCE(sum(if(a.status = 2,a.amount,0)),0) as total_fee_paid,b.id,b.name,b.remarks,b.daily,b.rate",$order = 'a.create_time desc', $paginate = 15){
        $this->modelOrders->group = 'a.cnl_id';
        $this->modelOrders->alias('a');

        $join = [
            ['pay_account b', ' b.id = a.cnl_id'],
        ];

        $this->modelOrders->join = $join;
        return $this->modelOrders->getList($where,$field, $order, $paginate = false);
    }

    /**
     * 获取某订单支付通道配置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order_no
     * @return mixed
     */
    public function getOrderPayConfig($order_no){

        return $this->logicPay->getAccountInfo([
            'id'    => $this->modelOrders->getValue(['trade_no'=>$order_no], 'cnl_id')
        ]
        );
    }

    /**
     * 推送队列
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order_id
     * @return array
     */
    public function pushOrderNotify($order_id = ''){
        //订单
        $order = $this->getOrderInfo(['id' => $order_id]);
        //加入队列
        $result = $this->logicQueue->pushJobDataToQueue('AutoOrderNotify' , $order , 'AutoOrderNotify');
        if ($result){
            $returnmsg = [ 'code' =>  CodeEnum::SUCCESS, 'msg'  => '推送队列成功'];
        }else{
            $returnmsg = [ 'code' =>  CodeEnum::SUCCESS, 'msg'  => '推送队列成功'];
        }
        action_log('推送','推送异步订单通知，单号：' . $order['out_trade_no']);
        return $returnmsg;
    }
    /**
     * 创建支付订单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $orderData
     * @return mixed
     * @throws OrderException
     */
    public function createPayOrder($orderData){
        //TODO 事务处理
        Db::startTrans();
        try{
            //这里就这样   不改了
            $order = new Orders();
            $order->uid         = $orderData['mchid']; //商户ID
            $order->subject     = $orderData['subject'];//支付项目
            $order->body        = $orderData['body'];//支付具体内容
            $order->trade_no    = create_order_no();//支付单号
            $order->out_trade_no= $orderData['out_trade_no'];//商户单号
            $order->amount      = $orderData['amount'];//支付金额
            $order->currency    = $orderData['currency'];//支付货币
            $order->channel     = $orderData['channel'];//支付渠道
            $order->client_ip   = $orderData['client_ip'];//订单创建IP
            $order->return_url  = $orderData['return_url'];//通知Url
            $order->notify_url  = $orderData['notify_url'];//通知Url
            $order->extra       = json_encode(!empty($orderData['extparam']) ?$orderData['extparam']:[]);//拓展参数
            $order->save();

            Db::commit();
            //  余额 = 可用余额（可提现金额） + 冻结余额（待结算金额） =》 未支付金额每日清算
            //   可用余额是从冻结余额转入的
            //写入待支付金额 creatBalanceChange('100001','100',$remarks = '记录资金变动测试','字段',$setDec = true);
            $this->logicBalanceChange->creatBalanceChange($order->uid,$order->amount,'单号'.$orderData['out_trade_no'].'预下单支付金额','disable');

            return $order;

        }catch (\Exception $e){
            //记录日志
            Log::error("Create Order Error:[{$e->getMessage()}]");
            Db::rollback();
            //抛出错误异常
            throw new OrderException([
                'msg'   =>  "Create Order Error, Please Try Again Later.",
                'errCode'=> '200001'
            ]);
        }
    }

    /**
     * 设置某个字段参数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param string $field
     * @param string $value
     */
    public function setOrderValue($where = [], $field = 'cnl_id', $value = ''){
        $this->modelOrders->setFieldValue($where, $field, $value);
    }
}