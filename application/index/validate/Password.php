<?php

/**
 * +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 * +----------------------------------------------------------------------
 *  | Copyright (c) 2019 知行信息科技. All rights reserved.
 * +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 * +----------------------------------------------------------------------
 */

namespace app\index\validate;

use app\common\logic\BaseLogic;

class Password extends BaseLogic
{
    /**
     * 验证规则
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $rule =   [
        'oldpassword'  => 'require',
        'password'   => 'require',
        'repassword' => 'require|confirm:password',
        'vercode'   => 'require|length:4,6|checkCode'
    ];

    /**
     * 验证消息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    protected $message  =   [

        'oldpassword.require' => '旧密码不能为空',
        'password.require'    => '密码不能为空',
        'repassword.require'  => '重复密码不能为空',
        'repassword.confirm'  => '重复密码不正确',
        'vercode.checkCode'   => '验证码不正确',
        'vercode.require'     => '验证码不能为空',
        'vercode.length'      => '验证码位数不正确'
    ];

}