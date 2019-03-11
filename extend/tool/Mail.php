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

namespace tool;

use mailer\PHPMailer;
use think\Exception;
use think\Log;

class Mail
{

    const LOG_TPL   = "Mail:";

    /**
     * 静态变量保存全局的实例
     * @var null
     */
    private static $_instance = null;

    /**
     * 配置参数
     * @var null
     */
    private static $config = null;

    /**
     * 私有的构造方法
     */
    private function __construct() {

    }

    /**
     * 静态方法 单例模式统一入口
     */
    public static function getInstance($config) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        self::$config = $config;
        return self::$_instance;
    }

    /**
     * 系统邮件发送函数
     * @param $tomail
     * @param $name
     * @param string $subject
     * @param string $body
     * @param null $attachment
     * @return bool|string
     * @throws
     */
    public function send($tomail, $name, $subject = '', $body = '', $attachment = null) {

        try{
            $mail = new PHPMailer();           //实例化PHPMailer对象
            $replyEmail         = '';          //留空则为发件人EMAIL
            $replyName          = '';          //回复名称（留空则为发件人名称）
            $mail->CharSet      = 'UTF-8';     //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                   // 设定使用SMTP服务
            $mail->SMTPAuth     = true;        // 启用 SMTP 验证功能
            $mail->SMTPSecure   = 'ssl';       // 使用安全协议
            $mail->SMTPDebug    = self::$config['debug'];// SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->Host         = self::$config['host']; // SMTP 服务器
            $mail->Port         = self::$config['port']; // SMTP服务器的端口号
            $mail->Username     = self::$config['username'];    // SMTP服务器用户名
            $mail->Password     = self::$config['password'];     // SMTP服务器密码
            $mail->SetFrom(self::$config['address'], self::$config['name']);
            $mail->AddReplyTo($replyEmail, $replyName);
            $mail->Subject = $subject;
            $mail->MsgHTML($body);
            $mail->AddAddress($tomail, $name);
            if (is_array($attachment)) { // 添加附件
                foreach ($attachment as $file) {
                    is_file($file) && $mail->AddAttachment($file);
                }
            }
        }catch (Exception $e){
            Log::record(self::LOG_TPL . $mail->ErrorInfo);
            return false;
        }

        return $mail->Send() ? true : $mail->ErrorInfo;

    }
}