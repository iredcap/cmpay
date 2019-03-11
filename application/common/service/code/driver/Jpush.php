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
use think\Cache;
use think\Log;

/**
 * Class Jpush
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Jpush extends Driver
{
    protected $options = [
        'app_key'      =>  '',
        'secret_key'   =>  '',
        'options'      => [
            'disable_ssl'  =>  true
        ],
        'temp_id'      =>  '1'
    ];

    /**
     * 构造函数
     * Jpush constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        if ($this->options['options']) {
            $this->handler = new \JiGuang\JSMS($this->options['app_key'],$this->options['secret_key'], $this->options['options']);
        } else {
            $this->handler = new \JiGuang\JSMS($this->options['app_key'],$this->options['secret_key']);
        }
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
            $response = $this->handler->sendCode($whom, $this->options['temp_id']);
            //写入存储
            $this->jpushCache($whom,$response['body']['msg_id'] ?? '' );
        }catch (\Exception $e){
            Log::error("Jpush Sms Send Fail:" . $e->getMessage());
        }
        return $response && $response['http_code'] == 200 ? true : false;

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
        $check = [];
        try{
            //读取存储里的msg_id检验
            $check = $this->handler->checkCode($this->jpushCache($whom), $value);
            Log::notice("Jpush Sms Check:" . json_encode($check));
        }catch (\Exception $e){
            Log::error("Jpush Sms Check Fail:" . $e->getMessage());
        }
        return $check && $check['http_code'] == 200  && $check['body']['is_valid']  ? $check : false;
    }

    /**
     * 通用缓存
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $msg_id 消息推送ID
     * @return bool|mixed
     */
    private function jpushCache( $whom, $msg_id = ''){
        if (empty($msg_id)){
            return Cache::get('code_verify_jpush_' . $whom);
        }else{
            return Cache::set('code_verify_jpush_' . $whom, $msg_id);
        }
    }

}
