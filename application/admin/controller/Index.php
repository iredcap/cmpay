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

namespace app\admin\controller;

use enum\LayEnum;
use think\helper\Time;

class Index extends BaseAdmin
{
    /**
     * 访问首页  -  加载框架
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 欢迎主页  -  展示数据
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function welcome()
    {
        return $this->fetch('',$this->logicOrders->getWelcomeStat());
    }

    /**
     * 订单月统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getOrderStat(){

        $res = $this->logicOrders->getOrdersMonthStat();

        $data = [
            'orders' => get_order_month_stat($res,'total_orders'),
            'fees' => get_order_month_stat($res,'total_amount'),
        ];
        $this->result(LayEnum::SUCCESS,'',$data);
    }

    /**
     * 本月最近订单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getOrdersList(){
        $where = [];
        //当月时间
        list($start, $end) = Time::month();

        $where['create_time'] = ['between time', [$start,$end]];

        $data = $this->logicOrders->getOrderList($where,true, 'create_time desc',false);

        $this->result(!$data->isEmpty() ?
            [
                'code' => LayEnum::SUCCESS,
                'msg'=> '',
                'data'=>$data
            ] : [
                'code' => LayEnum::ERROR,
                'msg'=> '暂无数据',
                'data'=>$data
            ]
        );
    }
}
