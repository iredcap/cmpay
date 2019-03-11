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

/**
 * 权限组验证器
 */
class AuthGroup extends BaseAdmin
{
    
    // 验证规则
    protected $rule =   [
        
        'name' => 'require',
    ];

    // 验证提示
    protected $message  =   [
        
        'name.require' => '权限组名称不能为空',
    ];

    // 应用场景
    protected $scene = [
        
        'add'  => ['name'],
        'edit' => ['name'],
    ];
    
}
