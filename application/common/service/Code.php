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

namespace app\common\service;


use think\App;
use think\Config;
use think\Exception;
use Log;

/**
 * Class Code
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Code
{
    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var string
     */
    public static $method = 'Jpush';

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var array
     */
    public static $instance = [];

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @var
     */
    public static $handler;

    /**
     * 连接Code驱动
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param array $options
     * @param $method
     * @param bool $name
     * @return mixed
     * @throws Exception
     */
    public static function connect(array $options = [], $method , $name = false)
    {

        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[$name])) {
            $class = false === strpos($method, '\\') ?
                __NAMESPACE__.'\\code\\driver\\' . ucwords($method) :
                $method;
            if (!class_exists($class)){
                throw new Exception('This method is not supported.');
            }
            // 记录初始化信息
            config('app_debug') && Log::record('[ Code ] INIT ' . $method, 'info');

            if (true === $name) {
                return new $class($options);
            }

            self::$instance[$name] = new $class($options);
        }

        return self::$instance[$name];
    }

    /**
     * 自动初始化Code
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $method
     * @return mixed|object
     * @throws Exception
     */
    public static function init($method = '')
    {
        if (is_null(self::$handler)) {
            // 默认方法
            if (!empty(config('code.default')) && empty($method)){
                self::$method = config('code.default');
            }else{
                self::$method = $method;
            }

            $options = Config::get("code.". self::$method);

            self::$handler = self::connect($options,self::$method);
        }

        return self::$handler;
    }

    /**
     * 发送验证码
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $whom
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public static function send($whom, $data = [])
    {
        return self::init()->send($whom, $data);
    }

    /**
     * 数据验证
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $whom
     * @param string $value
     * @return mixed
     * @throws Exception
     */
    public static function valid($whom, $value = ''){

        return self::init()->valid($whom, $value);
    }
}