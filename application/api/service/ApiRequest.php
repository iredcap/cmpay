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

use app\api\service\request\CheckAllowed;
use app\api\service\request\CheckAppkey;
use app\api\service\request\CheckArguments;
use app\api\service\request\CheckFrequent;
use app\api\service\request\CheckSign;
use Log;
use think\Request;
/**
 * 网关入口实体
 *
 * 初始化网关
 *
 * 责任链模式实现的网关
 */
class ApiRequest extends Rest
{
    /**
     * 我觉得还是提到Gateway比较好  而不是AppInit
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public static function check(){
        // 初始化一个：访问频次校验的check
        $checkFrequent    =  new CheckFrequent();
        // 初始化一个：访问IP校验的check
        $CheckAllowed    =  new CheckAllowed();
        // 初始化一个：签名校验的check
        $checkSign        =  new CheckSign();
        // 初始化一个：必传参数校验的check
        $checkArguments   =  new CheckArguments();
        // 初始化一个：令牌校验的check
        $checkAppkey      =  new CheckAppkey();

        // 构成对象链
        $checkFrequent->setNext($CheckAllowed)
            ->setNext($checkArguments)
            ->setNext($checkSign)
            ->setNext($checkAppkey);

        // 启动网关
        $checkFrequent->start(Request::instance());
    }
}
