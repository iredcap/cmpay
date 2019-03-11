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

use Aliyun\Sms\Api\SendSmsRequest;
use Aliyun\Sms\Core\Config;
use Aliyun\Sms\Core\DefaultAcsClient;
use Aliyun\Sms\Core\Profile\DefaultProfile;
use app\common\service\code\Driver;
use think\Log;

// 加载区域结点配置
Config::load();

class Alisms extends Driver
{
    protected $options = [
        'app_id'         =>      '',
        'app_key'        =>      '',
        'sign_name'      =>      '',
        'template_code'  =>      '',
        'product'        =>      'Dysmsapi',
        'region'         =>      'cn-hangzhou',
        'domain'         =>      'dysmsapi.aliyuncs.com',
        'point_name'     =>      'cn-hangzhou',
    ];

    /**
     * Aliacs全局的实例
     * @var null
     */
    private static $acsClient = null;

    /**
     * Alisms constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        if(static::$acsClient == null) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($this->options['region'], $this->options['app_id'], $this->options['app_key']);
            // 增加服务结点
            DefaultProfile::addEndpoint($this->options['point_name'], $this->options['region'], $this->options['product'], $this->options['domain']);

        }
        // 初始化AcsClient用于发起请求
        $this->handler = new DefaultAcsClient($profile);
    }

    /**
     * 发送短信
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $whom
     * @param array $data
     *
     * @return array|bool|mixed|\SimpleXMLElement
     */
    public function send($whom, $data)
    {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($whom);
        // 必填，设置签名名称
        $request->setSignName($this->options['sign_name']);
        // 必填，设置模板CODE
        $request->setTemplateCode($this->options['template_code']);
        // 可选，设置模板参数
        if(self::buildCache($whom)) {
            //参数格式  按照Alisms后台
            $SmsParam = ['code'  => self::buildCache($whom)];

            $request->setTemplateParam(json_encode($SmsParam));
        }
        // 可选，设置流水号
        $request->setOutId(getRandChar(32));

        $response = [];
        try{
        // 发起访问请求
            $response = $this->handler->getAcsResponse($request);
        }catch (\Exception $e){
            Log::error("Alisms Send Fail:" . $e->getMessage());
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