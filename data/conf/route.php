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

use think\Route;



//Route::miss('api/Miss/index');//强制路由错误返回

/**
 * Pay
 */
Route::rule('pay/cashier','api/Pay/cashier');//收银台
Route::post('pay/unifiedorder','api/Pay/unifiedorder');//统一下单
Route::post('pay/orderquery','api/Pay/orderquery');//查询订单
/**
 * Notify
 */
Route::post('notify/:channel','api/Notify/notify');//官方异步通知
Route::get('callback/:channel','api/Notify/callback');//官方同步通知
Route::rule('notify/person/:channel','api/Notify/person');//个人码异步通知


/**
 * 首页
 */
Route::get('products', 'index/Index/products');  //支付产品
Route::get('doc', 'index/Index/document');  //支付API文档
Route::get('demo', 'index/Index/demo');  //支付API演示
Route::get('introduce', 'index/Index/introduce');  //接入指南
Route::get('sdk', 'index/Index/sdk'); //sdk下载
Route::get('protocol', 'index/Index/protocol'); //服务条款
Route::get('help/:id', 'index/Index/help');
Route::get('about', 'index/index/about');
Route::post('vercode','index/Index/sendVerCode'); //【测试】
Route::get('user/get_order_stat','index/User/getOrderStat');
/**
 * 商户
 */
Route::get('user$','index/User/index');
Route::rule('user/info','index/User/info','GET|POST');
Route::rule('user/auth','index/User/auth','GET|POST');
Route::rule('user/password','index/User/password','GET|POST');
Route::get('user/log','index/User/log');
Route::get('user/faq','index/User/faq');
Route::get('notice/:id','index/User/notice');
/**
 * 代理
 */
Route::get('agent$','index/Agent/index');
Route::get('agent/order','index/Agent/orders');
Route::rule('agent/add_user','index/Agent/addUser','GET|POST');
Route::rule('agent/edit_user','index/Agent/editUser','GET|POST');
Route::rule('agent/profit','index/Agent/profit','GET|POST');
/**
 * 资金
 */
Route::get('balance$','index/Balance/index');
Route::get('balance/account','index/Balance/account');
Route::rule('account/add','index/Balance/addAccount','GET|POST');
Route::rule('account/edit','index/Balance/editAccount','GET|POST');
Route::get('balance/paid','index/Balance/paid');
Route::rule('balance/apply','index/Balance/apply','GET|POST');
/**
 * 订单
 */
Route::get('orders$','index/Order/index');
Route::get('orders/refund','index/Order/refund');
Route::get('orders/submit','index/Order/submit');
/**
 * API
 */
Route::rule('api$','index/Api/index','GET|POST');
Route::get('api/channel','index/Api/channel');
Route::get('api/doc','index/Api/document');

/**
 * 登录注册
 */
Route::rule('login','index/Login/login');  //商户登陆
Route::rule('register','index/Login/register'); //商户注册
Route::rule('logout','index/Login/logout'); //商户注册
/**
 * 验证
 */
Route::post('validate/can-use-user','index/Login/checkUser');
Route::post('validate/can-use-phone','index/Login/checkPhone');
Route::post('validate/sms','index/Login/sendSmsCode');
Route::rule('active/sendActive','index/Login/sendActiveCode');
Route::get('active/:code','index/Login/checkActiveCode');
/**
 * 极验
 */
Route::get('validate/gt-start','index/Login/startGeetest');
Route::post('validate/gt-verify','index/Login/checkGeetest');