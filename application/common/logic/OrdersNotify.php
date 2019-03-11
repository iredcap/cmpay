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


use app\common\library\exception\OrderException;
use think\Db;
use think\Log;

class OrdersNotify extends BaseLogic
{

    /**
     *
     * 获取订单通知列表
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
        $this->modelOrdersNotify->limit = !$paginate;
        return $this->modelOrdersNotify->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取订单通知信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     *
     * @return mixed
     */
    public function getOrderInfo($where = [], $field = true){
        return $this->modelOrdersNotify->getInfo($where, $field);
    }

    /**
     * 获取订单通知总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getOrdersCount($where = []){
        return $this->modelOrdersNotify->getCount($where);
    }

    /**
     * 新增或者修改通知信息 【命令行里无module】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     */
    public function saveOrderNotify($order){

        //TODO 修改数据
        Db::startTrans();
        //数据提交
        try{

            $this->modelOrdersNotify->setInfo([ 'order_id'   => $order['id']]);

            Db::commit();

        }catch (\Exception $e) {
            Db::rollback();
            //记录日志
            Log::error("Creat Balance Change Error:[{$e->getMessage()}]");
        }
    }
}