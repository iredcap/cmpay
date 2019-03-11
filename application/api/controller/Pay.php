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

namespace app\api\controller;

use app\api\service\ApiRespose;
use app\common\controller\BaseApi;
use app\common\library\exception\ForbiddenException;


/**
 * 所有的支付操作，都需要对输入执行参数校验，避免接口受到攻击。
　　● 验证输入参数中各字段的有效性验证，比如用户ID,商户ID,价格，返回地址等参数。
　　● 验证账户状态。交易主体、交易对手等账户的状态是处于可交易的状态。
　　● 验证订单：如果涉及到预单，还需要验证订单号的有效性，订单状态是未支付。为了避免用户缓存某个URL地址，还需要校验下单时间和支付时间是否超过预定的间隔。
　　● 验证签名。签名也是为了防止支付接口被伪造。 一般签名是使用分发给商户的key来对输入参数拼接成的字符串做MD5 Hash或者RSA加密，然后作为一个参数随其他参数一起提交到服务器端。
 *
 */
class Pay extends BaseApi
{
    //前置操作来验证  不适用tags了
    protected $beforeActionList = [
        'checkRequestParam'  =>  ['only'=>'unifiedorder,orderquery'],
    ];

    /**
     * 收银台跳转支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @throws ForbiddenException
     */
    public function cashier(){
        throw new ForbiddenException(['msg' => '等待开发...']);
    }

    /**
     * 统一扫码支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function unifiedorder(){
        //传入预支付订单信息 => 支付对象返回
        ApiRespose::send($this->logicPrePay->orderPay($this->request->post()));
    }

    /**
     * 统一查询接口
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function orderquery(){
        //传入预支付订单信息 => 支付对象返回
        ApiRespose::send($this->logicPrePay->orderQuery($this->request->post()));
    }

}