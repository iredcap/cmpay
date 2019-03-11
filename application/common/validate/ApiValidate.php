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


class ApiValidate extends BaseValidate
{
    // 验证规则
    protected $rule = [
        'sitename'      => 'require',
        'domain'        => 'require',
        'secretkey'     => 'require',
        'time'          => 'require',
        'daily'         => 'require'
    ];

    // 验证提示
    protected $message = [
        'sitename.require'      => '站点不能为空',
        'domain.require'        => '域名不能为空',
        'secretkey.require'     => '公钥不能为空',
        'time.require'          => '限制不能为空',
        'daily.require'         => '日限不能为空',
    ];

    // 应用场景
    protected $scene = [
        'edit'      =>  ['secretkey']
    ];

}