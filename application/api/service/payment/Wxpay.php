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

class Wxpay extends ApiPayment
{

    /**
     * 微信扫码支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function wx_native($order){
        //获取预下单
        $unifiedOrder = self::getWxpayUnifiedOrder($order);
        //数据返回
        return [
            'prepay_id' => $unifiedOrder['prepay_id'],
            'order_qr' => $unifiedOrder['code_url']
        ];
    }

    /**
     * 微信公众号支付【待测】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function wx_jsapi($order){
        //获取预下单
        $unifiedOrder = self::getWxpayUnifiedOrder($order, 'JSAPI');
        //构建微信支付
        $jsBizPackage = array(
            "appId" => $this->config['app_id'],
            "timeStamp" => (string)time(),        //这里是字符串的时间戳
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder['prepay_id'],
            "signType" => 'MD5',
        );
        $jsBizPackage['paySign'] = self::getWxpaySign($jsBizPackage, $this->config['mch_key']);

        //数据返回
        return $jsBizPackage;
    }

    /**
     * 微信APP支付【待测】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function wx_app($order){
        //获取预下单
        $unifiedOrder = self::getWxpayUnifiedOrder($order, 'JSAPI');
        //构建微信支付
        $jsBizPackage = array(
            "appid" => $this->config['app_id'],  //应用号
            "partnerid" => $this->config['mch_id'], //商户号
            "prepayid" => $unifiedOrder['prepay_id'],
            "package" => "Sign=WXPay",
            "timeStamp" => (string)time(),        //这里是字符串的时间戳
            "nonceStr" => self::createNonceStr()
        );
        $jsBizPackage['sign'] = self::getWxpaySign($jsBizPackage, $this->config['mch_key']);

        //数据返回
        return $jsBizPackage;
    }

    /**
     * 小程序支付【待测】
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function wx_mini($order){
        //获取预下单
        $unifiedOrder = self::getWxpayUnifiedOrder($order, 'JSAPI');
        //构建微信支付
        $jsBizPackage = array(
            "appId" => $this->config['app_id'],
            "timeStamp" => (string)time(),        //这里是字符串的时间戳
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder['prepay_id'],
            "signType" => 'MD5',
        );
        $jsBizPackage['paySign'] = self::getWxpaySign($jsBizPackage, $this->config['mch_key']);

        //数据返回
        return $jsBizPackage;
    }

    /**
     * 微信H5支付
     *
     * 常见错误：
     * 1.网络环境未能通过安全验证，请稍后再试（原因：终端IP(spbill_create_ip)与用户实际调起支付时微信侧检测到的终端IP不一致）
     * 2.商家参数格式有误，请联系商家解决（原因：当前调起H5支付的referer为空）
     * 3.商家存在未配置的参数，请联系商家解决（原因：当前调起H5支付的域名与申请H5支付时提交的授权域名不一致）
     * 4.支付请求已失效，请重新发起支付（原因：有效期为5分钟，如超时请重新发起支付）
     * 5.请在微信外打开订单，进行支付（原因：H5支付不能直接在微信客户端内调起）
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     *
     * @return array
     * @throws OrderException
     */
    public function wx_h5($order){
        //获取预下单
        $unifiedOrder = self::getWxpayUnifiedOrder($order, 'MWEB');
        //数据返回
        return [
            'mweb_url' => $unifiedOrder['mweb_url']
        ];
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
        return $this->verifyWxOrderNotify();
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

    /******************微信***********************************/


    /**
     * 微信预下单
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $order
     * @param string $trade_type
     *
     * @return mixed
     * @throws OrderException
     */
    private function getWxpayUnifiedOrder($order, $trade_type = 'NATIVE'){

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
        $unified['sign'] = self::getWxpaySign($unified, $this->config['mch_key']);
        //数据请求
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
        //XML转ARRAY
        $result = self::xmlToArray($responseXml);
        Log::notice('Wxpay API Response : '. json_encode($result));
        //判断成功
        if (!isset($result['return_code']) || $result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            Log::error('Create Wechat API Error:'.($result['return_msg'] ?? $result['retmsg']));
            throw new OrderException([
                'msg'   => 'Create Wechat API Error:'.($result['return_msg'] ?? $result['retmsg']),
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
    private function verifyWxOrderNotify(){
        libxml_disable_entity_loader(true);
        //Object  对象
        $response = json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE),true);
        //读订单对应的支付渠道配置
        $this->config = self::getOrderPayConfig($response['out_trade_no']);
        if (self::getWxpaySign($response, $this->config['mch_key']) !== $response['sign']) {
            Log::error('Verify WxOrder Notify Error');
            throw new OrderException([
                'msg'   => 'Verify WxOrder Notify Error',
                'errCode'   => 200010
            ]);
        }
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        return obj2arr($response);

    }

    /**
     * 获取微信签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $params
     * @param $key
     *
     * @return string
     */
    public static function getWxpaySign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatWxpayQueryParaMap($params);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }

    /**
     * 微信字符串排序
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