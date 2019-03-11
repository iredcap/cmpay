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
namespace IredCap\Pay;

use IredCap\Pay\Http\HttpRequest;
use IredCap\Pay\Constant\HttpMethod;
use IredCap\Pay\Util\HttpUtil;
use IredCap\Pay\Util\LogUtil;
use IredCap\Pay\Util\SignUtil;

class Charge extends HttpRequest
{

    /**
     * 创建支付
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $params
     *
     * @return mixed|string
     * @throws Exception\InvalidParameterException
     * @throws Exception\InvalidRequestException
     */
    public static function create($params = [])
    {
        return self::_request(self::getBaseUrl() . 'pay/unifiedorder',HttpMethod::POST,  $params);
    }

    /**
     * 订单查询
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $params
     *
     * @return mixed|string
     * @throws Exception\InvalidParameterException
     * @throws Exception\InvalidRequestException
     */
    public static function query($params)
    {
        return self::_request(self::getBaseUrl() . 'pay/orderquery', HttpMethod::POST, $params);
    }

    /**
     * 回调验签
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return bool
     * @throws Exception\InvalidResponseException
     */
    public static function verify(){
        //获取通知数据
        $params = file_get_contents("php://input");
        LogUtil::INFO('Notify Data: ' . $params);
        $headers = [];
        //获取通知头部
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
       return SignUtil::to_verify_data($headers, $params);
    }
}
