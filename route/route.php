<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

//Route::miss('api/Miss/index');//强制路由错误返回

// 支付路由
Route::group('pay',function (){
    Route::rule('/cashier','api/Pay/cashier');//收银台
    Route::post('/unifiedorder','api/Pay/unifiedorder');//统一下单
    Route::post('/orderquery','api/Pay/orderquery');//查询订单
    Route::post('notify/:channel','api/Notify/notify');//官方异步通知
    Route::get('callback/:channel','api/Notify/callback');//官方同步通知
});

// 首页
Route::group('/',function (){
    Route::get('products', 'index/Index/products');  //支付产品
    Route::get('doc', 'index/Index/document');  //支付API文档
    Route::get('demo', 'index/Index/demo');  //支付API演示
    Route::get('introduce', 'index/Index/introduce');  //接入指南
    Route::get('sdk', 'index/Index/sdk'); //sdk下载
    Route::get('protocol', 'index/Index/protocol'); //服务条款
    Route::get('help/:id', 'index/Index/help');
    Route::get('about', 'index/index/about'); //关于我们
});

// 授权
Route::group('auth',function (){
    Route::rule('/login','index/Auth/login','GET|POST');  //商户登陆
    Route::rule('/register','index/Auth/register','GET|POST'); //商户注册
    Route::rule('/logout','index/Auth/logout'); //商户注册
    Route::post('/uploads','index/Auth/uploads'); //身份上传
    // 验证
    Route::group('valid',function (){
        Route::post('/can-use-user','index/Auth/checkUser');
        Route::post('/can-use-phone','index/Auth/checkPhone');
        Route::post('/send-sms-code','index/Auth/sendSmsCode');
        Route::rule('/active/sendActive','index/Auth/sendActiveCode');
        Route::get('/active/:code','index/Auth/checkActiveCode');
        // 极验
        Route::get('/gt-start','index/Auth/startGeetest');
        Route::post('/gt-verify','index/Auth/checkGeetest');
    });
});

// 商户后台
Route::group('dashboard',function (){
    Route::get('/main','index/User/index'); // 中心
    Route::get('/notice','index/User/notice'); //通知
    Route::get('/years','index/User/getYearsStat'); //年
    Route::get('/faq','index/User/faq'); //FAQ

    // 交易管理
    Route::group('orders',function (){
        Route::get('/list','index/Order/index');
        Route::get('/refund','index/Order/refund');
        Route::get('/submit','index/Order/submit');
    });

    // 商户管理
    Route::group('/member',function (){
        Route::rule('/person','index/User/person','GET|POST'); // 个人资料
        Route::rule('/authorize','index/User/authorize','GET|POST'); // 认证资料
        Route::rule('/password','index/User/password','GET|POST'); // 密码管理
        Route::get('/log','index/User/log'); // 个人日志
    });

     // 财务管理
    Route::group('/finance',function (){
        Route::get('/details','index/Balance/index');  // 资金明细
        Route::get('/account','index/Balance/account');  // 收款账号列表
        Route::rule('account/add','index/Balance/addAccount','GET|POST');  // 收款账号添加
        Route::rule('account/edit','index/Balance/editAccount','GET|POST'); // 收款账号编辑
        Route::get('/paid','index/Balance/paid'); // 提现列表
        Route::rule('/apply','index/Balance/apply','GET|POST'); // 提现申请
    });

     // 代理管理 (·······停更·············)
    Route::group('/agent',function (){
        Route::get('/order','index/Agent/orders');
        Route::rule('/add_user','index/Agent/addUser','GET|POST');
        Route::rule('/edit_user','index/Agent/editUser','GET|POST');
        Route::rule('/profit','index/Agent/profit','GET|POST');
    });

    // APP
    Route::group('/app',function (){
        Route::get('/channel','index/Api/channel'); // 可用渠道
        Route::get('/document','index/Api/document'); // 开发文档
    });

    // 帮助支持
    Route::group('/support',function (){
        Route::get('/list','index/Support/index');
        Route::rule('/ticket','index/Support/ticket','GET|POST');
        Route::rule('/detail','index/Support/detail','GET|POST');
    });

});