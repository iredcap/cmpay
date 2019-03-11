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

class PayAccount extends BaseAdmin
{
    // 验证规则
    protected $rule = [
        'name'  => 'require|length:3,10',
        'rate'  => 'require|number',
        'single'     => 'require|number',
        'daily'     => 'require|number',
        'remarks'    => 'require',
        'param'    => 'require'
    ];

    // 验证提示
    protected $message = [
        'name.require'    => '账户名不能为空',
        'name.length'     => '账户名长度为6-30个字符之间',
        'rate.require'    => '账户费率不能为空',
        'rate.number'     => '账户费率必须为数字',
        'single.require'    => '账户单笔限额不能为空',
        'single.number'    => '账户单笔限额必须为数字',
        'daily.require'     => '账户当日限额不能为空',
        'daily.number'       => '账户当日限额必须为数字',
        'remarks.require'     => '账户备注不能为空',
        'param.require'      => '支付配置不能为空'
    ];
}