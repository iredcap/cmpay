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
 * HTTP头常量
 */
class HttpHeader {
    //请求Header Accept
    const HTTP_HEADER_ACCEPT = "Accept";
    //请求Body内容MD5 Header
    const HTTP_HEADER_CONTENT_MD5 = "Content-MD5";
    //请求Header Content-Type
    const HTTP_HEADER_CONTENT_TYPE = "Content-Type";
    //请求Header UserAgent
    const HTTP_HEADER_USER_AGENT = "User-Agent";
    //请求Header UserAgent
    const X_CA_REST_URL = "X-Ca-Resturl";
    //签名Header
    const X_CA_SIGNATURE = "X-Ca-Signature";
    //请求时间戳
    const X_CA_TIMESTAMP = "X-Ca-Timestamp";
    //请求放重放Nonce,15分钟内保持唯一,建议使用UUID
    const X_CA_NONCE_STR = "X-Ca-Noncestr";
    //请求 KEY
    const X_CA_AUTH = "X-Ca-Auth";
}
