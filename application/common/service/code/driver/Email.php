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
use Log;

/**
 * Class Mail
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Email extends Driver
{
    protected $options = [
        'debug'       => '0',// SMTP调试功能 0=>关闭 1 => 错误和消息 2 => 消息
        'host'        => '',
        'port'        => '',
        'username'    => '',
        'password'    => '',
        'address'     => '',
        'name'        => '',
    ];

    /**
    * 构造函数
    * @param array $options 参数
    * @access public
    */
    public function __construct($options = [])
    {

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->handler = new \PHPMailer\PHPMailer();
    }

    /**
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $whom
     * @param array $data
     * @return bool|string
     * @throws \PHPMailer\Exception
     */
    public function send($whom,$data = [])
    {
        try{
            $mail = $this->handler;           //实例化PHPMailer对象
            $replyEmail         = $this->options['address'];          //留空则为发件人EMAIL
            $replyName          = $this->options['name'];          //回复名称（留空则为发件人名称）
            $mail->CharSet      = 'UTF-8';     //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP();                   // 设定使用SMTP服务
            $mail->SMTPAuth     = true;        // 启用 SMTP 验证功能
            $mail->SMTPSecure   = 'ssl';       // 使用安全协议
            $mail->SMTPDebug    = $this->options['debug'];// SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->Host         = $this->options['host']; // SMTP 服务器
            $mail->Port         = $this->options['port']; // SMTP服务器的端口号
            $mail->Username     = $this->options['username'];    // SMTP服务器用户名
            $mail->Password     = $this->options['password'];     // SMTP服务器密码
            $mail->SetFrom($this->options['address'], $this->options['name']);
            $mail->AddReplyTo($replyEmail, $replyName);
            $mail->Subject = $data['subject'];
            $mail->MsgHTML(self::getMailCentent($whom,self::buildCache($whom)));
            $mail->AddAddress($whom, $whom);
            if (is_array(isset($data['attachment']) ? $data['attachment'] :'')) { // 添加附件
                foreach (isset($data['attachment']) ? $data['attachment'] :'' as $file) {
                    is_file($file) && $mail->AddAttachment($file);
                }
            }
        }catch (\Exception $e){
            Log::error("Email Fail:" . $e->getMessage());
            return false;
        }

        //判断返回
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    /**
     * 验证码验证
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $whom
     * @param bool $value
     * @return bool|mixed
     */
    public function valid($whom, $value = false)
    {
        return self::checkCache($whom,$value);
    }

    /**
     * 获取发送邮件内容
     * @param $Towhom
     * @param $code
     * @return string
     */
    private function getMailCentent($Towhom,$code){
        $centent = "<!DOCTYPE html>
                    <html lang=\"en\">
                    <body style=\"background-color: #f7f9fa;\">
                    <div style=\"margin: -15px; padding: 8vh 0 2vh;color: #a6aeb3; background-color: #f7f9fa; text-align: center; font-family:NotoSansHans-Regular,'Microsoft YaHei',Arial,sans-serif; -webkit-font-smoothing: antialiased;\">
                        <div style=\"width: 750px; max-width: 85%; margin: 0 auto; background-color: #fff; -webkit-box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);-moz-box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);box-shadow: 0 2px 16px 0 rgba(118,133,140,0.22);\">
                            <div style=\"padding: 20px 10%; text-align: center; font-size: 16px;line-height: 16px;\">
                                <a href=\"/\" style=\"vertical-align: top;\"> <img style=\"margin:32px auto; max-width: 95%; color: #0e2026;\" src=\"https://www.iredcap.cn/static/logo-black.png\" /> </a>
                            </div>
                            <table width=\"600\" style=\"background-color:#fff;margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
                                <tbody><tr>
                                    <td>
                                        <table width=\"600\" style=\"background-color:#fff;margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
                                            <tbody>
                                            <tr>
                                                <td width=\"40\">&nbsp;</td>
                                                <td width=\"520\" style=\"line-height:20px;\">
                                                    <p style=\"text-align:center;margin:0;padding:0;\">
                                                        <span style=\"font-size:24px;line-height:32px;\">本次操作验证码为：</span>
                                                    </p>
                                                    <p style=\"color:#7d7d7d;margin:10px 0px 24px 0px;font-size:14px;line-height:22px;padding:0 40px;text-align:center\">
                                                    </p>
                                                    <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:44px; width:200px\">
                                                        <tbody>
                                                        <tr>
                                                            <td style=\"background-color:#00c1de; height:44px; line-height:44px;text-align:center; width:200px\">
                                                                 <a style=\"display:block;text-decoration: none;color: #ffffff;font-size:16px;\">{$code}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td width=\"40\">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width=\"40\">&nbsp;</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody></table>
                            <div style=\"padding-bottom: 40px;font-size: 14px;\">
                                <div style=\"padding-bottom: 40px;font-size: 14px;\">
                                    <div style=\"width: 420px; max-width: 90%;margin: 10px auto;\">
                                        彻底告别繁琐的支付接入流程 一次接入所有主流支付渠道和分期渠道，99.99% 系统可用性，满足你丰富的交易场景需求,为你的用户提供完美支付体验。
                                    </div>
                                    <div style=\"margin: 16px 0 32px;\">
                                        <img src=\"https://www.iredcap.cn/static/qr-pub.jpg\" width=\"125\" height=\"125\" />
                                    </div>
                                    <div>
                                        <span style=\"color: #76858c;\">服务咨询请联系：</span>
                                        <a href=\"mailto:yubei@iredcap.cn\" style=\"color:#35c8e6; text-decoration: none;\" target=\"_blank\"> me@iredcap.cn </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </body>
                    </html>";
        return $centent;

    }

}
