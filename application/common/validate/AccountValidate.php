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


namespace app\common\validate;

class AccountValidate extends BaseValidate
{
    // 验证规则
    protected $rule = [
        'banker'      => 'require',
        'account'   => 'require',
        'address'   => 'require',
        'vercode'   => 'require|checkCode'
    ];

    // 验证提示
    protected $message = [
        'banker.require'      => '银行不能为空',
        'account.require'   => '账号不能为空',
        'address.require'   => '地址不能为空',
        'vercode.require'   => '验证码不能为空',
        'vercode.checkCode'   => '验证码不正确'
    ];

    protected $scene = [
       'edit' => ['banker','account','address']
    ];
}