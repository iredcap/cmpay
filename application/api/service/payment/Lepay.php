<?php


namespace app\api\service\payment;


use app\api\service\ApiPayment;
use think\Log;

class Lepay extends ApiPayment
{

    public function pay($order){
        //获取请求数据
        $data= array(
            'account' => $this->config['mch_id'],
            'order' => $order['trade_no'],
            'paytype' => 'wxsmd0',
            'money' => $order['amount'],
            //'channel'=>$_POST['tongdao'],
            'ip' =>  self::getIp(),
            'body' => $order['body'],
            'ext' => getRandChar(32),
            'notify' =>$this->config['notify_url'],
            'callback' =>$this->config['return_url'],
        );

        $str = 'account='.$data["account"].'&callback='.($data["callback"]).'&money='.$data["money"].'&notify='.($data["notify"]).'&order='.$data["order"].'&paytype='.$data["paytype"].'&'.$this->config['key'];

        $data['sign']=md5($str);

        $postUrl = 'https://pay.3v22.com/api/pay?account='.$data["account"].'&callback='.($data["callback"]).'&money='.$data["money"].htmlspecialchars("&not").'ify='.($data["notify"]).'&order='.$data["order"].'&paytype='.$data["paytype"].'&sign='.$data["sign"];

        $order = self::postApi('https://pay.3v22.com/api/pay',$data,$this->config['key']);
        $orderArr = json_decode($order,true);
        return $orderArr;
//        return ['sign'  => $data['sign'],'uri'=> $postUrl];
    }


    public function getIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }


    public function postApi($url,$param='',$key){
        /*$postch = curl_init();
        curl_setopt($postch, CURLOPT_POST, 1);
        curl_setopt($postch, CURLOPT_URL,$url);
        curl_setopt($postch, CURLOPT_POSTFIELDS,$data);
        ob_start();
        curl_exec($postch);
        $con = ob_get_contents() ;
        ob_end_clean();
        return json_decode($con, true);
        */

        $headers=['api-key:'.$key];
        $curlPost = $param;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址
        curl_setopt($ch,CURLOPT_HEADER,0);            //是否显示头部信息
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);           //设置超时
        //curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);   //用户访问代理 User-Agent
        //curl_setopt($ch, CURLOPT_REFERER,_REFERER_);        //设置 referer
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}