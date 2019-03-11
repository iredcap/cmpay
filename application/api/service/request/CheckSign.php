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

namespace app\api\service\request;
use app\common\library\exception\SignatureException;
use app\common\library\HttpHeader;
use think\Request;

/**
 * 检验网关签名
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class CheckSign extends ApiCheck
{
    /**
     * 签名校验
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param Request $request
     *
     * @return mixed|void
     * @throws SignatureException
     * @throws \app\common\library\exception\ParameterException
     */
    public function doCheck(Request $request)
    {
        $header = [];
        $header = !is_null($request->header())?$request->header():$header;
        $_cur_uri = $_cur_uri_query_string = stristr($header['x-ca-resturl'],'/pay/');
        $_query_string = $_query_string_index = strpos($_cur_uri_query_string,'?');

        self::set(HttpHeader::X_CA_REST_URL,$_cur_uri);

        if (!empty($_query_string_index)){
            $_cur_uri = substr($_cur_uri_query_string,0,$_query_string_index);//uri
            $_query_string = substr($_cur_uri_query_string,$_query_string_index+1);//query string
        }

        $_to_verify_data = utf8_encode($_cur_uri)
            ."\n".utf8_encode($_query_string)
            ."\n".utf8_encode($header['x-ca-noncestr'])
            ."\n".utf8_encode($header['x-ca-timestamp'])
            ."\n".utf8_encode($request->getContent());

        //商户提交支付数据验签
        $verify_result = self::verify(base64_encode($_to_verify_data), $header['x-ca-signature'],self::get(HttpHeader::X_CA_AUTH));

        if(empty($verify_result) || intval($verify_result) != 1){
            throw new SignatureException([
                'msg'=>'Invalid Request.[ Request Data And Sign Verify Failure.]',
                'errorCode'=> 400003,
            ]);
        }
        return;
    }
}
