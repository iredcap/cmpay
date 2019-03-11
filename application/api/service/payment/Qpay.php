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

class Qpay extends ApiPayment
{

    /**
     * QQ扫码支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function qq_native($order){
        //获取预下单
        $unifiedOrder = self::getQpayUnifiedOrder($order);
        //数据返回
        return [
            'prepay_id' => $unifiedOrder['prepay_id'],
            'order_qr' => $unifiedOrder['code_url']
        ];
    }

    /**
     * QQ公众号支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function qq_jsapi($order){
        //获取预下单
        $unifiedOrder = self::getQpayUnifiedOrder($order, 'JSAPI');
        //构建QQ支付
        $jsBizPackage = array(
            "appId" => $this->config['app_id'],
            "timeStamp" => (string)time(),        //这里是字符串的时间戳
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
        );
        $jsBizPackage['paySign'] = self::getQpaySign($jsBizPackage, $this->config['mch_key']);

        //数据返回
        return $jsBizPackage;
    }

    /**
     * QQ APP支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function qq_app($order, $notify = false){
        //获取预下单
        $unifiedOrder = self::getQpayUnifiedOrder($order, 'JSAPI');
        //构建QQ支付
        $jsBizPackage = array(
            "appid" => $this->config['app_id'],  //应用号
            "partnerid" => $this->config['mch_id'], //商户号
            "prepayid" => $unifiedOrder->prepay_id,
            "package" => "Sign=WXPay",
            "timeStamp" => (string)time(),        //这里是字符串的时间戳
            "nonceStr" => self::createNonceStr()
        );
        $jsBizPackage['sign'] = self::getQpaySign($jsBizPackage, $this->config['mch_key']);

        //数据返回
        return $jsBizPackage;
    }

    /**
     * 异步回调地址 /默认按类名称  【 https://pay.iredcap.cn/notify/wxpay 】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return array
     * @throws OrderException
     */
    public function notify(){
        return $this->verifyQpayOrderNotify();
    }

    /******************QQ***********************************/


    /**
     * QQ预下单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     * @param string $trade_type
     *
     * @return mixed
     * @throws OrderException
     */
    private function getQpayUnifiedOrder($order, $trade_type = 'NATIVE'){
        //请求参数
        $unified = array(
            'appid' => $this->config['app_id'],
            'attach' => 'cmpay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $order['subject'],
            'mch_id' =>  $this->config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $this->config['notify_url'],
            'out_trade_no' => $order['trade_no'],
            'spbill_create_ip' => request()->ip(),
            'total_fee' => intval(bcmul(100, $order['amount'])),       //单位 转为分
            'trade_type' => $trade_type,
        );
        //是否含有附加参数
        if (isset($order['extra'])){
            //1.先转数组
            $extparam = json_decode($order['extra'],true);
            //2.循环寻找数据
            foreach ($extparam as $k => $v){
                ($k == 'openid' && $v != '' && !is_array($v)) ?$unified[$k] = $v : '';
            }
        }
        //签名
        $unified['sign'] = self::getQpaySign($unified, $this->config['mch_key']);

        $responseXml = self::curlPost('https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi', self::arrayToXml($unified));

        $result = self::xmlToArray($responseXml);

        if (!isset($result['return_code']) || $result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            Log::error('Create QQ API Error:'. $result['retmsg']);
            throw new OrderException([
                'msg'   => 'Create QQ API Error:'. $result['retmsg'],
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
     */
    public function verifyQpayOrderNotify(){
        libxml_disable_entity_loader(true);
        //Object  对象
        $response = json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE),true);
        //读订单对应的支付渠道配置
        $this->config = self::getOrderPayConfig($response['out_trade_no']);
        if (self::getQpaySign($response, $this->config['mch_key']) !== $response['sign']) {
            Log::error('Verify QpayOrder Notify Error');
            throw new OrderException([
                'msg'   => 'Verify QpayOrder Notify Error',
                'errCode'   => 200010
            ]);
        }
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        return obj2arr($response);

    }

    /**
     * 获取QQ签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $params
     * @param $key
     *
     * @return string
     */
    public static function getQpaySign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatWxpayQueryParaMap($params);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }

    /**
     * QQ字符串排序
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $paraMap
     *
     * @return bool|string
     */
    protected static function formatWxpayQueryParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);

        foreach ($paraMap as $k => $v) {
            $buff .= ($k != 'sign' && $v != '' && !is_array($v)) ? $k.'='.$v.'&' : '';
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}