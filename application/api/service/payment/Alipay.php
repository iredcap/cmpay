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
use app\common\library\exception\SignatureException;
use Log;

class Alipay extends ApiPayment
{
    /**
     * 支付宝扫码支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     * @throws SignatureException
     */
    public function ali_qr($order){
        //请求参数
        $requestConfigs = array(
            'out_trade_no'=> $order['trade_no'],
            'total_amount'=> sprintf("%.2f", $order['amount']), //支付宝交易范围  [0.01,100000000]
            'subject'=> $order['subject'],  //订单标题
            'timeout_express'=>'10m'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );

        $result = self::getGenerateAlipayOrder($requestConfigs, 'alipay.trade.precreate');

        return [
            'order_qr' => $result['qr_code']
        ];
    }

    public function ali_pc($order){
        //alipay.trade.page.pay  product_code
        //请求参数
        $requestConfigs = array(
            'out_trade_no'=> $order['trade_no'],
            'total_amount'=> sprintf("%.2f", $order['amount']), //支付宝交易范围  [0.01,100000000]
            'subject'=> $order['subject'],  //订单标题
            'product_code'=> 'FAST_INSTANT_TRADE_PAY',  //product_code
            'timeout_express'=>'10m'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );

        $result = self::getGenerateAlipayOrder($requestConfigs, 'alipay.trade.page.pay');
    }

    /**
     * 异步回调地址 /默认按类名称  【 https://pay.iredcap.cn/notify/alipay 】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return array
     * @throws OrderException
     * @throws SignatureException
     */
    public function notify(){
        return $this->verifyAliOrderNotify();
    }

    /**
     * 同步地址 【待测】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return mixed
     */
    public function callback(){
        //1.拿out_trade_no
        $out_trade_no = request()->param('out_trade_no');
        //2.查订单获取  商户return_url
        $order = self::getOrder($out_trade_no);
        //3.返回参数跳转
        return $order;
    }

    /******************************支付宝******************************************/

    /**
     * 支付宝统一
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $requestConfigs
     * @param string $trade_type
     *
     * @return mixed
     * @throws OrderException
     * @throws SignatureException
     */
    private function getGenerateAlipayOrder($requestConfigs, $trade_type = 'alipay.trade.pay'){

        $commonConfigs = array(
            //公共参数
            'app_id' => $this->config['app_id'],
            'method' => $trade_type,             //接口名称
            'format' => 'JSON',
            'charset'=> 'utf-8',
            'sign_type'=>'RSA2',
            'timestamp'=> date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => $this->config['notify_url'],
            'biz_content'=>json_encode($requestConfigs),
        );
        //签名
        $commonConfigs["sign"] = $this->generateAlipaySign($commonConfigs, $commonConfigs['sign_type']);
        //请求
        $response = $this->curlPost('https://openapi.alipay.com/gateway.do',$commonConfigs);
        Log::notice('Alipay API Response : '. json_encode($response));
        $response = json_decode($response,true);
        //读数据
        $result = $response['alipay_trade_precreate_response'];

        if (!isset($result['code']) || $result['code'] != '10000') {
            Log::error('Create Alipay API Error:'. $result['msg'].' : '.$result['sub_msg']);
            throw new OrderException([
                'msg'   => 'Create Alipay API Error:'. $result['msg'].' : '.$result['sub_msg'],
                'errCode'   => 200009
            ]);
        }
        //数据返回
        return $result;
    }

    /**
     * 回调验签
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return array
     * @throws OrderException
     * @throws SignatureException
     */
    public function verifyAliOrderNotify(){

        $response = convertUrlArray(file_get_contents('php://input')); //支付宝异步通知POST返回数据
        //转码
        $response = self::encoding($response,'utf-8', $response['charset'] ?? 'gb2312');
        //读订单对应的支付渠道配置
        $this->config = self::getOrderPayConfig($response['out_trade_no']);

        //验签
        $result = $this->verify($this->getSignContent($response, true), $response['sign'], $response['sign_type']);
        if (!$result) {
            Log::error('Verify Alipay Sign Error: 请检查支付宝配置是否正确');
            throw new OrderException([
                'msg'   => 'Verify Alipay Sign Error. [Check RSA Public Key Configuration]',
                'errCode'   => 200010
            ]);
        }
        echo 'success';
        return $response;
    }

    /**
     * 支付宝签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $params
     * @param $signType
     *
     * @return string
     * @throws SignatureException
     */
    protected function generateAlipaySign($params, $signType){

        return $this->sign($this->getSignContent($params), $signType);
    }

    /**
     * 签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @param string $signType
     *
     * @return string
     * @throws SignatureException
     */
    protected function sign($data, $signType = "RSA") {
        $priKey = $this->config['private_key'];

        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        try{
            if ("RSA2" == $signType) {
                openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
            } else {
                openssl_sign($data, $sign, $res);
            }
        }catch (\Exception $e){
            Log::error('Verify Alipay Sign Error: 支付宝私钥格式错误，请检查RSA私钥配置');
            throw new SignatureException([
                'msg'   => 'Verify Alipay Sign Error. [Alipay Private Key Format Error].',
                'errCode'   => 10009
            ]);
        }

        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 验证
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $data
     * @param $sign
     * @param string $signType
     *
     * @return bool
     * @throws SignatureException
     */
    protected function verify($data, $sign, $signType = 'RSA') {
        $pubKey= $this->config['public_key'];

        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        try{

            //调用openssl内置方法验签，返回bool值
            if ("RSA2" == $signType) {
                $result = (bool)openssl_verify($data, base64_decode($sign), $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
            } else {
                $result = (bool)openssl_verify($data, base64_decode($sign), $res);
            }
        }catch (\Exception $e){
            Log::error('Verify Alipay Sign Error: 支付宝公钥格式错误，请检查RSA公钥配置');
            throw new SignatureException([
                'msg'   => 'Verify Alipay Sign Error. [Alipay Public Key Format Error].',
                'errCode'   => 10009
            ]);
        }

        return $result;
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

    /**
     * 签名排序
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $params
     * @param $verify
     *
     * @return string
     */
    public function getSignContent($params, $verify =false) {

        $data = self::encoding($params, $params['charset'] ?? 'gb2312', 'utf-8');

        ksort($data);

        $stringToBeSigned = '';

        foreach ($data as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                if ($verify && $k != 'sign' && $k != 'sign_type') {
                    $stringToBeSigned .= $k . '=' . $v . '&';
                }
                if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
                    $stringToBeSigned .= $k . '=' . $v . '&';
                }
            }
        }

        return trim($stringToBeSigned, '&');
    }


    /**
     * 编码转换
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $array
     * @param $to_encoding
     * @param string $from_encoding
     *
     * @return array
     */
    public static function encoding($array, $to_encoding, $from_encoding = 'gb2312')
    {
        $encoded = [];
        foreach ($array as $key => $value) {
            $encoded[$key] = is_array($value) ? self::encoding($value, $to_encoding, $from_encoding) :
                mb_convert_encoding(urldecode($value), $to_encoding, $from_encoding);
        }
        return $encoded;
    }

}