<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if(version_compare(PHP_VERSION,'7.0','<'))  die('PHP版本过低，需要PHP7.0+，请升级您的PHP版本！');
// 加载自定义常量
require __DIR__ . '/../constants.php';
// 加载框架引导文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

//检测安装
if(!file_exists(RES_PATH . 'install.lock')){
    // 绑定安装模块
    Container::get('app')->bind('install')->run()->send();
    exit;
}

// 执行应用并响应
Container::get('app')->run()->send();
