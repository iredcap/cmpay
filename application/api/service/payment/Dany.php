<?php


namespace app\api\service\payment;


use app\api\service\ApiPayment;
use app\common\library\exception\OrderException;
use think\Log;

class Dany extends ApiPayment
{

    /**
     * 网关
     * @var string
     */
    protected $gateway = "https://gateway.mzhipay.cn/Pay/Json";

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
    public function alipay($order){

        return $this->request('alipay', $order);
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
    public function request($type = 'alipay', $order){
        $parameter = array(
            "pid" => trim($this->config['app_id']),
            "type" => $type,
            "out_trade_no"	=> $order['out_trade_no'],
            "name"	=> $order['subject'],
            "money"	=> $order['amount'],
            "extra_para" => self::createNonceStr(),
            "notify_url"	=> $this->config['notify_url'],
            "return_url"	=> $this->config['return_url'],
        );
        $paramArr = $this->buildRequestPara($parameter);
        //请求
        $result = json_decode(self::curlPost($this->gateway, $paramArr),true);
        Log::notice('Dany '. $result['code']);
        if ($result['code']  == '500' ){
            throw new OrderException([
                'msg'   => 'Create Alipay API Error:'. $result['msg'].' : '.$result['code'],
                'errCode'   => 200009
            ]);
        }
        return [
            'order_qr'  => $result['payurl']
        ];
    }

    /**
     * 构建参数
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $para
     *
     * @return mixed
     */
    private function buildRequestPara($para) {
        //除去待签名参数数组中的空值和签名参数
        foreach ($para as $key => $val){
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_temp[$key] = $para[$key];
        }
        //对待签名参数数组排序
        ksort($para_temp);
        reset($para_temp);
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_temp);

        //签名结果与签名方式加入请求提交参数组中
        $para_temp['sign'] = $mysign;
        $para_temp['sign_type'] = strtoupper(trim('MD5'));

        return $para_temp;
    }

    /**
     * 参数签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $para_sort
     *
     * @return string
     */
    private function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);

        $mysign = $this->md5Sign($prestr, $this->config['key']);

        return $mysign;
    }

    /**
     * 规则
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $prestr
     * @param $key
     *
     * @return string
     */
    private function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    /**
     * 参数规则
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $para
     *
     * @return bool|string
     */
    private function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }
}