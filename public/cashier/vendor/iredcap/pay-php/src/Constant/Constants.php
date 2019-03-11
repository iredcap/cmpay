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
namespace IredCap\Pay\Constant;

/**
 * 通用常量
 */
class Constants
{
	//签名算法HmacSha256
    const HMAC_SHA256 = "HmacSHA256";
	//签名算法RSA2
    const RSA2 = "RSA2";
    //编码UTF-8
    const ENCODING = "UTF-8";
    //UserAgent
    const USER_AGENT = "Mozilla/4.0 (compatible; MSIE 7.0; Cmpay SDK SV1; Trident/4.0; SV1; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0) ";
    //换行符
    const LF = "\n";
	//分隔符1
    const SPE1 = ",";
    //分隔符2
    const SPE2 = ":";
    //默认请求超时时间,单位毫秒
    const DEFAULT_TIMEOUT = 1000;
    //参与签名的系统Header前缀,只有指定前缀的Header才会参与到签名中
	const CA_HEADER_TO_SIGN_PREFIX_SYSTEM = "X-Ca-";
}