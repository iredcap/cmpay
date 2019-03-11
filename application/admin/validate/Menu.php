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


class Menu extends BaseAdmin
{
    //name:控制台
    //url:/
    //module:admin
    //icon:console
    // 验证规则
    protected $rule =   [

        'name'      => 'require|unique:menu',
        'url'      => 'require',
        'module'      => 'require'
    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '名称不能为空',
        'url.require'       => 'URL不能为空',
        'module.require'    => '模块不能为空'
    ];
    // 应用场景
    protected $scene = [
        'add'       =>  ['name','url','module'],
        'edit'      =>  ['url','module']
    ];
}