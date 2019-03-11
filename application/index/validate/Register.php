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

namespace app\index\validate;


class Register extends Base
{
    /**
     * 验证规则
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $rule =   [

        'account'  => 'require|email',
        'password'  => 'require|length:6,12',
        'phone'     => 'require|number|length:11',
        'vercode'      => 'require|length:4,6|checkCode'
    ];

    /**
     * 验证消息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $message  =   [

        'account.require'     => '邮箱不能为空',
        'account.email'       => '邮箱不正确',
        'password.require'    => '登录密码不能为空',
        'password.length'     => '登陆密码长度6-12',
        'phone.require'       => '手机号不能为空',
        'phone.length'        => '手机号长度不足',
        'vercode.checkCode'   => '验证码不正确',
        'vercode.require'     => '验证码不能为空',
        'vercode.length'      => '验证码位数不正确'
    ];

}