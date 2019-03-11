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

return [
    // 验证码默认驱动
    'default'   => 'Qsms',

    //邮件
    'Email'  => [
        'debug'       => '0',// SMTP调试功能 0=>关闭 1 => 错误和消息 2 => 消息
        'host'        => 'smtp.mxhichina.com',
        'port'        => '465',
        'username'    => 'service@iredcap.cn',
        'password'    => 'xzx595...',
        'address'     => 'service@iredcap.cn',
        'name'        => '小红帽提醒'
    ],

    //极光
    'Jpush' => [
        'app_key'     =>  'f00e9e4a7834fa42e2f7ce0d',
        'secret_key'    =>  '3fdfe1e6b46b47051b34a6b6',
        'options'   => [
            'disable_ssl'    =>  true
        ],
        'temp_id'    =>  '1',
    ],

    //腾讯云
    'Qsms' => [
        'app_id'     =>      '1400144497',
        'app_key'    =>      '420742792ff85f5ba9b5c2814a3e8026',
        'sign_id'    =>      '170875',
        'sign_name'  =>      '润轩商务',
        'template_id'    =>  '205099'
    ],

    //阿里云
    'Alisms' => [
        'app_id'         =>      '',
        'app_key'        =>      '',
        'sign_name'      =>      '',
        'template_code'  =>      ''
    ]
];