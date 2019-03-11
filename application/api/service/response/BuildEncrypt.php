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

namespace app\api\service\response;


use app\common\library\HttpHeader;

class BuildEncrypt extends ApiSend
{

    /**
     * 构建签名时间戳与随机字符串
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $chargeRespose
     * @return mixed|void
     */
    public function doBuild($chargeRespose)
    {
        //记录本次上下文数据
        self::set(HttpHeader::X_CA_NONCE_STR,  self::createUniqid());
        self::set(HttpHeader::X_CA_TIMESTAMP,  self::getMicroTime());
    }

}