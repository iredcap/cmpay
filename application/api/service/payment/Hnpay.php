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

namespace app\api\service\payment;


use app\api\service\ApiPayment;
use app\common\library\exception\OrderException;
use think\Log;

class Hnpay extends ApiPayment
{

    /**
     * 支付宝支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function ali_qr($order){

        return $this->requestApi('alipay', $order);
    }

    /**
     * 发起支付请求
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $type
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function requestApi($type = 'alipay', $order){

        $request = array(
            "version" => '1.0',
            "customerid" => trim($this->config['uid']),
            "total_fee"	=> $order['amount'],
            "paytype" => $type,
            "sdorderno"	=> $order['out_trade_no'],
            "notifyurl"	=> $this->config['notify_url'],
            "returnurl"	=> $this->config['return_url'],
        );

        $signStr = 'version=1.0&customerid=' . $this->config['uid'] . '&total_fee=' . $order['amount'] . '&sdorderno=' . $order['out_trade_no'] . '&notifyurl=' .
            $this->config['notify_url'] . '&returnurl='. $this->config['return_url'] .  '&' . $this->config['key'];
        $request['sign'] = md5($signStr);
//        $request['sign'] = $this->buildRequestSign($request, $this->config['key']);

        //请求
        $result = self::curlPost('http://www.yuechuanhg.cn/gateway', $request);
        Log::notice('Hnpay '. json_encode($result));
//        Log::notice('Hnpay '. $result['code']);
//        if ($result['code']  == '500' ){
//            throw new OrderException([
//                'msg'   => 'Create Hnpay API Error:'. $result['msg'].' : '.$result['code'],
//                'errCode'   => 200009
//            ]);
//        }
        return [
            //'order_qr'  => $result['payurl']
        ];
    }

    /**
     * 签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $param
     * @param $key
     *
     * @return string
     */
    private function buildRequestSign($param, $key){
        return md5(self::getSignContent($param) . $key);
    }

    /**
     * 数据排序
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     *
     * @return string
     */
    private function getSignContent($data){
        ksort($data);

        $stringToBeSigned = '';

        foreach ($data as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                if ($v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
                    $stringToBeSigned .= $k . '=' . $v . '&';
                }
            }
        }
        Log::notice('Hnpay API Sign Content:' . $stringToBeSigned);
        return $stringToBeSigned;
    }

    /**
     * 校验$value是否非空
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $value
     *
     * @return bool
     */
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
}