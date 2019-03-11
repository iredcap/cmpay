<?php

/**
 * +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *  | Copyright (c) 2019 知行信息科技. All rights reserved.
 * +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

namespace app\api\service\response;

use tool\HttpHeader;

class BuildSign extends ApiSend
{

    /**
     * 数据签名
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $payload
     *
     * @return mixed|void
     * @throws \app\common\exception\ParameterException
     * @throws \app\common\exception\SignatureException
     */
    public function doBuild($payload)
    {
        $_to_sign_data = utf8_encode(self::get(HttpHeader::X_CA_NONCE_STR))
            ."\n" . utf8_encode(self::get(HttpHeader::X_CA_TIMESTAMP))
            ."\n" . utf8_encode(json_encode(static::get('ApiResposeData')));

        //生成签名并记录本次签名上下文
        self::set(HttpHeader::X_CA_SIGNATURE, $this->sign(base64_encode($_to_sign_data)));
    }

}