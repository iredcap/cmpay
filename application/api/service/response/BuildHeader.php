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
use Log;

class BuildHeader extends ApiSend
{

    /**
     * 构建头信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $chargeRespose
     *
     * @return mixed|void
     * @throws \app\common\library\exception\ParameterException
     */
    public function doBuild($chargeRespose)
    {
        // 构建头信息
        $header = [
            HttpHeader::X_CA_NONCE_STR  =>  self::get( HttpHeader::X_CA_NONCE_STR),
            HttpHeader::X_CA_TIMESTAMP  =>  self::get(HttpHeader::X_CA_TIMESTAMP),
            HttpHeader::X_CA_SIGNATURE  =>  self::get(HttpHeader::X_CA_SIGNATURE)
        ];
        Log::notice('Response Header :' . json_encode($header));
        //记录本次签名
        self::set('header',$header);
    }

}