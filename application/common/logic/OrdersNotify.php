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


use think\Db;
use think\facade\Log;

class OrdersNotify extends BaseLogic
{

    /**
     * 获取订单通知列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int $paginate
     *
     * @return false|\PDOStatement|string|\think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrdersNotifyList($where = [], $field = true, $order = 'create_time desc', $paginate = 15)
    {
        $this->limit = !$paginate;
        return $this->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取订单异步信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $where
     * @param bool $field
     *
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrdersNotifyInfo($where = [], $field = true){
        return $this->getInfo($where, $field);
    }

    /**
     * 获取订单通知总数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $where
     * @return mixed
     */
    public function getOrdersNotifyCount($where = []){
        return $this->getCount($where);
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

            $this->setInfo([ 'order_id'   => $order['id']]);

            Db::commit();

        }catch (\Exception $e) {
            Db::rollback();
            //记录日志
            Log::error("Creat Balance Change Error:[{$e->getMessage()}]");
        }
    }
}