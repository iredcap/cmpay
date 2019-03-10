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
use think\exception\HttpResponseException;
use Log;
use think\Response;

class BuildResponse extends ApiSend
{


    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $chargeRespose
     * @throws \app\common\library\exception\ParameterException
     */
    public function doBuild($chargeRespose)
    {
        http_response_code(200);    //设置返回头部
        $return['result_code'] = 'OK';
        $return['result_msg'] = empty($chargeRespose) ? 'FAIL' : 'SUCCESS';
        $return['charge'] =  self::get('ApiResposeData');

        Log::notice('Response Data :' . json_encode($return));

        //签名及数据返回
        $response = Response::create(json_encode($return))->header(self::get('header'));
        // 销毁请求上下文
        self::destoryContext();
        // 数据响应
        throw new HttpResponseException($response);
    }


}