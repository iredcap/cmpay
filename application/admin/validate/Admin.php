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

namespace app\admin\validate;


class Admin extends BaseAdmin
{

    // 验证规则
    protected $rule =   [

        'username'      => 'require|unique:admin',
        'nickname'      => 'require',
        'password'      => 'require|length:6,20',
        'email'         => 'require|email|unique:admin'
    ];

    // 验证提示
    protected $message  =   [

        'username.require'      => '用户名不能为空',
        'username.unique'       => '用户名已存在',
        'nickname.require'      => '昵称不能为空',
        'password.require'      => '密码不能为空',
        'password.length'       => '密码长度为6-20字符',
        'email.require'         => '邮箱不能为空',
        'email.email'           => '邮箱格式不正确',
        'email.unique'          => '邮箱已存在'
    ];
    // 应用场景
    protected $scene = [
        'add'       =>  ['username','nickname','password','email'],
        'edit'      =>  ['nickname','email']
    ];
}