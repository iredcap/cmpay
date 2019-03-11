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

namespace IredCap\Pay\Tests;
use IredCap\Pay\Http\HttpRequest;

define('CRET_PATH', __DIR__ . "/cret/");

class Pay
{
    /**
     * Payment constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        //1.设置配置参数
        HttpRequest::setBaseUrl('https://api.pay.iredcap.cn/');         // 设置 API请求根
        HttpRequest::setMchId($config['mch_id']);         // 设置 MCH ID
        HttpRequest::setSecretKey($config['mch_key']);  // 设置 MCH KEY
        HttpRequest::setNotifyUrl($config['notify_url']); // 设置 NOTIFY URL
        HttpRequest::setReturnUrl($config['return_url']); // 设置 RETURN URL
        HttpRequest::setPrivateKeyPath(CRET_PATH . '772ae1d32322f49508307b2f31a0107f/rsa_private_key.pem'); // 设置私钥
        HttpRequest::setPublicKeyPath(CRET_PATH . '772ae1d32322f49508307b2f31a0107f/rsa_public_key.pem'); // 设置公钥
        HttpRequest::setPayPublicKeyPath(CRET_PATH . 'rsa_public_key.pem'); // 设置平台公钥
    }

    /**
     * 统一下单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $param 订单参数
     *
     * @return mixed|string
     * @throws \IredCap\Pay\Exception\InvalidParameterException
     * @throws \IredCap\Pay\Exception\InvalidRequestException
     */
    public function pay($param)
    {

        //2.支付主体 构建请求参数
        $payload = [
            "out_trade_no" => $param['order_no'],
            "subject" => $param['body'],
            "body" => $param['body'],
            "amount" => $param['sum'],
            "currency" => 'CNY',
            "channel" => strtolower($param['type']), //支付方式小写wx.scan
            "extparam" => [
                "nocestr" => $this->getRandChar()  //支付附加参数 可置空
            ],

        ];
        //提交支付
        $order = \Iredcap\Pay\Charge::create($payload);
        return $order;
    }

    /**
     * 查单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $param 查单参数
     *
     * @return mixed|string
     * @throws \IredCap\Pay\Exception\InvalidParameterException
     * @throws \IredCap\Pay\Exception\InvalidRequestException
     */
    public function query($param)
    {
        //2.查询主体 构建请求参数
        $payload = [
            "out_trade_no" => '201825111239023472', //商户订单号
            "channel" => strtolower($param['type']), //支付方式 小写
        ];
        //提交支付
        $order = \Iredcap\Pay\Charge::query($payload);
        return $order;
    }

    /**
     * 异步通知验证
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return bool
     * @throws \IredCap\Pay\Exception\InvalidResponseException
     */
    public function callback()
    {
        //提交支付
        return \Iredcap\Pay\Charge::verify();
    }


    /**
     * 随机参数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $length
     *
     * @return null|string
     */
    private function getRandChar($length = 32)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }
}