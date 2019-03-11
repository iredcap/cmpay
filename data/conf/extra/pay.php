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

/**
 * 支付参数示例
 */
return [

    //微信
    'wxpay' => [
        'app_id' => 'wxb3fxxxxxxxxxxx',
        'mch_id' => '145776xxxx',
        'key' => 'mF2suE9sU6Mk1CxxxxIxxxxx',
        'notify_url' => 'https://api.pay.iredcap.cn/notify/wechat',
        'cert_client' => CRET_PATH . 'wxpay/apiclient_cert.pem', // optional, 退款，红包等情况时需要用到
        'cert_key' => CRET_PATH . 'wxpay/apiclient_key.pem',// optional, 退款，红包等情况时需要用到
    ],

    //支付宝
    'alipay'=>[
        'app_id' => '20180000000000',
        'notify_url' => 'https://api.pay.iredcap.cn/notify/alipay',
        'return_url' => 'https://api.pay.iredcap.cn/notify/alipay',
        'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAix0lmphMY4htd8sw6kLMBGyju6p2y4pQtmiUpk7KxIV2NaUj0Zve2WJvPDptbKB0Lmn3EksPVG8VCrlh97shKjerm0gW314YN1DY/7RFPqxeeYNIFaMiGgf1ecMZUAOwO/v8NKn2nKH5hA0eMFxXNTtAXfSY/UBBnMFWOd765uQsXNn6r0PjhIpC2T9Hk+KfVm2eQ3QqY82/s0SaeebN/xjbkTsAc6yKGPCJxbe2vyE5coQ8iCj4pVvlFX6+SO+lEFvB56r8H+dQlDixPGgEGz+PZkUny7SZjFBZm5amH6XEl40ac9iWuuaW2C28FMoHX6XjJgu95aZMeVa5ZCrqmQIDAQAB',
        'private_key' => 'WFNAWERGNAKWREGVAWKRG',
    ],
];
