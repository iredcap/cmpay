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

namespace app\admin\controller;

use app\common\library\enum\CodeEnum;
use app\common\library\enum\OrderStatusEnum;

class Orders extends BaseAdmin
{

    /**
     * 订单列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index(){

        $this->assign($this->logicOrders->getOrdersAllStat());
        $this->assign('code', $this->logicPay->getCodeList());
        return $this->fetch();
    }

    /**
     * 交易列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getList(){

        //状态
        $where['status'] = ['eq', $this->request->param('status',OrderStatusEnum::PAID)];

        //组合搜索
        !empty($this->request->param('trade_no|out_trade_no')) && $where['trade_no|out_trade_no']
            = ['like', '%'.$this->request->param('trade_no').'%'];

        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        !empty($this->request->param('channel')) && $where['channel']
            = ['eq', $this->request->param('channel')];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicOrders->getOrderList($where,true, 'create_time desc',false);

        $count = $this->logicOrders->getOrdersCount($where);

        $this->result($data || !empty($data) ?
            [
                'code' => CodeEnum::SUCCESS,
                'msg'=> '',
                'count'=>$count,
                'data'=>$data
            ] : [
                'code' => CodeEnum::ERROR,
                'msg'=> '暂无数据',
                'count'=>$count,
                'data'=>$data
            ]
        );
    }

    /**
     * 获取详情
     * 1.基本
     * 2.回调
     * 3.商户
     * 4.结算
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function details(){
        $where['id'] = $this->request->param('id','0');

        //订单
        $order = $this->logicOrders->getOrderInfo($where);

        $notify = [];
        //当支付成功的时候才会看有没有回调成功
        if ($order['status'] == '2'){
            //回调
            $notify = $this->logicOrders->getOrderNotify(['order_id'=> $where['id']]);
        }

        $this->assign('order', $order);
        $this->assign('notify', $notify);

        return $this->fetch();
    }


    /**
     * 退款列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function refund(){
        $where = [];
        //组合搜索
        !empty($this->request->param('keywords')) && $where['trade_no|out_trade_no|uid|id']
            = ['like', '%'.$this->request->param('keywords').'%'];

        !empty($this->request->param('channel')) && $where['channel']
            = ['eq', $this->request->param('channel')];

        !empty($this->request->param('status')) && $where['status']
            = ['eq', $this->request->param('status')];

        $this->assign('list', $this->logicOrders->getOrderList($where));

        return $this->fetch();
    }


    /**
     * 商户订单统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function user(){
        return $this->fetch();
    }

    /**
     * 商户交易统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function userList(){
        $where = [];
        //组合搜索
        !empty($this->request->param('uid')) && $where['uid']
            = ['eq', $this->request->param('uid')];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicOrders->getOrderUserStat($where);

        //$this->result($data || !empty($data) ? [CodeEnum::SUCCESS,'',$data] : [CodeEnum::ERROR,'暂无数据','']);
        $count = count($data);

        $this->result($data || !empty($data) ?
            [
                'code' => CodeEnum::SUCCESS,
                'msg'=> '',
                'count'=>$count,
                'data'=>$data
            ] : [
                'code' => CodeEnum::ERROR,
                'msg'=> '暂无数据',
                'count'=>$count,
                'data'=>$data
            ]
        );
    }

    /**
     * 商户渠道统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function channel(){
        $this->assign('channel', $this->logicPay->getChannelList());
        return $this->fetch();
    }

    /**
     * 商户渠道统计
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function channelList(){
        $where = [];
        //组合搜索
        !empty($this->request->param('cnl_id')) && $where['a.cnl_id']
            = ['eq', $this->request->param('cnl_id')];

        //时间搜索  时间戳搜素
        $where['a.create_time'] = $this->parseRequestDate();

        $data = $this->logicOrders->getOrderChannelStat($where);

        $count = count($data);

        $this->result($data || !empty($data) ?
            [
                'code' => CodeEnum::SUCCESS,
                'msg'=> '',
                'count'=>$count,
                'data'=>$data
            ] : [
                'code' => CodeEnum::ERROR,
                'msg'=> '暂无数据',
                'count'=>$count,
                'data'=>$data
            ]
        );
    }

    /**
     * 这里还是写入队列
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     */
    public function subnotify(){
        $this->result($this->logicOrders->pushOrderNotify($this->request->param('order_id')));
    }
}
