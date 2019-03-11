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


namespace app\common\service\code\driver;

use app\common\service\code\Driver;
use think\Log;

/**
 * Class Qsms
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Qsms extends Driver
{
    protected $options = [
        'app_id'         =>      '',
        'app_key'        =>      '',
        'sign_id'        =>      '',
        'sign_name'      =>      '',
        'template_id'    =>      ''
    ];

    /**
     * 构造函数
     * Qsms constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->handler = new \Qcloud\Sms\SmsSingleSender($this->options['app_id'],$this->options['app_key']);

    }

    /**
     * 发送短信
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $whom 手机号
     * @param array $data 附加数据
     * @return bool|string
     */
    public function send($whom,$data = [])
    {
        $response = [];
        try{
            $result = $this->handler->sendWithParam("86", $whom, $this->options['template_id'],
                [self::buildCache($whom)], $this->options['sign_name'], "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $response = json_decode($result,true);
        }catch (\Exception $e){
            Log::error("Qsms Send Fail:" . $e->getMessage());
        }
        return $response && $response['result'] == 0 && $response['errmsg'] == 'OK' ? true : false;

    }

    /**
     * 验证码检测
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $whom 手机号
     * @param bool $value 验证码值
     * @return bool|mixed
     */
    public function valid($whom, $value = false)
    {
        return self::checkCache($whom,$value);
    }


}
