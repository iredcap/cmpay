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
use app\api\service\response\BuildCharge;
use app\api\service\response\BuildEncrypt;
use app\api\service\response\BuildHeader;
use app\api\service\response\BuildResponse;
use app\api\service\response\BuildSign;
use Log;

/**
 * API响应
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class ApiRespose extends Rest
{
    /**
     * 响应数据 对象链
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $chargeRespose
     */
    public static function send($chargeRespose){

        // 初始化一个：相应对象Charge
        $buildCharge      =  new BuildCharge();
        // 初始化一个：签名校验的Encrypt
        $buildEncrypt     =  new BuildEncrypt();
        // 初始化一个：签名校验的Sign
        $buildSign        =  new BuildSign();
        // 初始化一个：签名校验的Header
        $buildHeader      =  new BuildHeader();
        // 初始化一个：Response
        $buildResponse    =  new BuildResponse();

        // 构成对象链
        $buildCharge->setNext($buildEncrypt)
                    ->setNext($buildSign)
                    ->setNext($buildHeader)
                    ->setNext($buildResponse);

        // 启动Send
        $buildCharge->start($chargeRespose);
    }
}