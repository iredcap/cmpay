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
use Log;

class Hcpay extends ApiPayment
{

    public function unpay($order){
        $result = self::getGenerateHcpayOrder($order, 'alipay.trade.precreate');
        return $result;
    }

    public function getGenerateHcpayOrder($order, $frpId){
        $request = [
            'p0_Cmd'=> "Buy",
            'p1_MerId'=> $this->config['mer_id'],
            'p2_Order'=> $order['trade_no'],
            'p3_Cur'=> "CNY",
            'p4_Amt'=> sprintf("%.2f", $order['amount']),
            'p5_Pid'=> $order['subject'],
            'p8_Url'=> $this->config['notify_url'],
            'p9_MP'=> $order['extra'],
            'pa_FrpId'=> $frpId,
            'pg_BankCode'=> "银行码",
            'ph_Ip'=> request()->ip()
        ];

        //签名
        $request['hmac'] = self::getHcpaySign($request, $this->config['mch_key']);

        $response = self::curlPost('https://5u17.cn/controller.action', $request);
        Log::notice('Hcpay API Response : '. json_encode($response));
        return $request;
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
    public static function getHcpaySign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatHcpayQueryParaMap($params);
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
    protected static function formatHcpayQueryParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);

        foreach ($paraMap as $k => $v) {
            $buff .= ($k != 'hmac' && $v != '' && !is_array($v)) ? $v : '';
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    private function HmacMd5($data,$key)
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)

        //需要配置环境支持iconv，否则中文参数不能正常处理
        $key = iconv("GB2312","UTF-8",$key);
        $data = iconv("GB2312","UTF-8",$data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*",md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }
}