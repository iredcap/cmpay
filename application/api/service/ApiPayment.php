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

namespace app\api\service;

use app\common\library\exception\ParameterException;
use app\common\logic\Orders;

class ApiPayment
{
    /**
     * 支付配置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $config;

    /**
     * constructor.
     *
     * @param array $config
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 魔术方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $method
     * @param array $params
     *
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($method, $params)
    {
        $app = new self(...$params);

        return $app->create($method);
    }

    /**
     * 构建
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $method
     * @return mixed
     * @throws \Exception
     */
    public function create($method){

        $payment = __NAMESPACE__ . '\\payment\\' . ucwords($method);

        if (class_exists($payment)) {
            return new $payment($this->config);
        }

        throw new ParameterException(['msg' => 'Payment [{$method}] Not Exists']);
    }


    /**
     * curl get
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * curl post
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $url
     * @param string $postData
     * @param array $options
     * @return mixed
     */
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 随机字符串
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $length
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 数组转Xml
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $arr
     * @return string
     */
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * Xml转数组
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml){

        libxml_disable_entity_loader(true);

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);

    }

    /**
     * 依据订单号获取配置
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $out_trade_no
     *
     * @return mixed
     */
    protected function getOrderPayConfig($out_trade_no = ''){

        //获取配置
        $orderConfig = (new Orders())->getOrderPayConfig($out_trade_no);

        return json_decode($orderConfig['param'], true);
    }

    /**
     * 依据订单号获取订单同步地址
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $out_trade_no
     *
     * @return mixed
     */
    protected function getOrder($out_trade_no = ''){

        return (new Orders())->getOrderInfo(['trade_no' => $out_trade_no], 'return_url');
    }
}