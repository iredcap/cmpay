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

namespace app\api\service\response;


use app\common\library\HttpHeader;
use Log;

class BuildCharge extends ApiSend
{
    /**
     * 构建支付对象返回
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $chargeRespose
     *
     * @return mixed|void
     * @throws \app\common\library\exception\ParameterException
     */
    public function doBuild($chargeRespose)
    {
        //从请求上下文取出商户支付申请数据
        $payload = self::get('payload');

        //判断请求方式
        if ( md5(self::get(HttpHeader::X_CA_REST_URL)) ==  md5('/pay/orderquery')){
            $ApiResposeData= $chargeRespose;
            if (empty($chargeRespose)){
                $ApiResposeData = [];
            }
        }else{
            $ApiResposeData = [
                'channel'   =>  $payload['channel'],
                'out_trade_no'  =>  $payload['out_trade_no'],
                'client_ip'   =>  $payload['client_ip'],
                'amount'    =>  (string)$payload['amount'],
                'currency'    =>  $payload['currency'],
                'subject'    =>  $payload['subject'],
                'body'    =>  $payload['body'],
                'extparam' => !empty($payload['extparam']) ? $payload['extparam']:[],
                'credential' => []
            ];
            if (!empty($chargeRespose)){
                $ApiResposeData['credential'] = $chargeRespose;
            }

        }
        // 设置上下文支付包
        self::set('ApiResposeData',$ApiResposeData);
    }

}