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

namespace app\common\library;

use think\Cache;
use think\Log;

/**
 * Class Activation
 *
 * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
 *
 */
class Activation
{
    /**
     * 发送激活码链接   用户激活后在发送商户信息
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param object $user
     * @return bool
     */
    public function sendActiveCode($user){

        //收件人邮箱
        $toemail    =   $user->account;
        //发件人昵称
        $name       =   !empty($user->nickname)? $user->nickname:'Cmpay';
        //邮件标题
        $subject    =   "【聚合支付】用户注册 - 注册邮箱验证";
        //邮件主体  也可以使用邮件模板文件
        $content =  self::getRegActiveContent($user);
        //读数据库配置
        $config = config();
        //发送激活邮件
        try{
            return Mail::getInstance(config('code.Email'))->send($toemail,$name,$subject,$content);
        }catch (\Exception $exception){
            Log::error("Active Code Error:".$exception->getMessage());
            return false;
        }
    }

    /**
     * 激活成功返回
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $user
     *
     * @return bool
     */
    public function sendRegCallback($user){
        //收件人邮箱
        $toemail    =   $user->account;
        //发件人昵称
        $name       =   !empty($user->nickname)? $user->nickname:'Cmpay';
        //邮件标题
        $subject    =   "【聚合支付】用户注册 - 注册成功通知";
        //邮件主体  也可以使用邮件模板文件
        $content = self::getRegCallbackContent($user);
        //发送激活邮件
        try{
           return Mail::getInstance(config('code.Email'))->send($toemail,$name,$subject,$content);
        }catch (\Exception $exception){
            Log::error("Active Code Error:".$exception->getMessage());
            return false;
        }
    }

    /**
     * 生成用户激活码
     * @param $userInfo
     * @return string
     */
    public function createActiveCode($userInfo){
        // 机密数据
        $data = [
            'user' =>  obj2arr($userInfo),
            'noncestr' => getRandChar('32')
        ];
        //生成Code
        $code = data_md5(json_encode($data));
        //加密
        //1.用户激活的时候返回 code
        //2.用code解密取到ActiveCode的数据
        //3.比对cache
        //4.完成激活
        $encryptData = (new CryptAes($code))->encrypt(json_encode(obj2arr($userInfo))); //用户信息
        //存入cache 2H内验证时间 2*60*60 7200秒
        Cache::set('avtive_code_'.$code,$encryptData,7200);
        return $code;
    }

    /**
     * 用户激活码验证
     * @param $code
     * @return object|bool
     */
    public function VerificationActiveCode($code){

        //获取缓存的值
        $build = 'avtive_code_'.$code;
        $activeCode = Cache::get($build);
        //判断缓存是否过期
        if(empty( $activeCode )){
            return false;
        }else{
            //删除Cache
            //Cache::rm($build);
            $user = (new CryptAes($code))->decrypt($activeCode);
            return json_decode($user);
        }
    }

    /**
     * 注册邮件发送
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $user
     *
     * @return string
     */
    private function getRegActiveContent($user){

        //生成code
        $activecode = urlencode($this->createActiveCode($user));
        //激活地址
        $activeUrl = "https://pay.iredcap.cn/active/{$activecode}";

        return "<div style=\"margin: -15px; padding: 8vh 0 2vh;color: #a6aeb3; background-color: #f7f9fa; text-align: center; font-family:NotoSansHans-Regular,'Microsoft YaHei',Arial,sans-serif; -webkit-font-smoothing: antialiased;\">
            <div style=\"width: 750px; margin: 0 auto; background-color: #fff;\">
                <div style=\"padding: 20px 10%; text-align: center; font-size: 16px;line-height: 16px;\">
                    <a href=\"https://www.caomao.com\" style=\"vertical-align: top;\" target=\"_blank\" rel=\"noopener\"> 
                    <img style=\"margin:32px auto; max-width: 95%; color: #0e2026;\" src=\"https://pay.iredcap.cn/static/logo-color.png\">
                     </a>
                </div>
                <table width=\"600\" style=\"background-color:#fff;margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
                    <tbody><tr>
                        <td>
                            <table width=\"600\" style=\"background-color:#fff;margin:0 auto;\" cellpadding=\"0\" cellspacing=\"0\">
                                <tbody>
                                <tr>
                                    <td colspan=\"3\" style=\"height:40px;\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"40\">&nbsp;</td>
                                    <td width=\"520\" style=\"line-height:20px;\">
                                        <p style=\"text-align:center;margin:0;padding:0;\">
                                            
                                            <span style=\"font-size:24px;line-height:32px;color:#35B34A;\">注册申请成功！</span>
                                        </p>
                                        <p style=\"color:#7d7d7d;margin:10px 0px 24px 0px;font-size:14px;line-height:22px;padding:0 40px;text-align:center\">欢迎{$user->username}加入,在开始使用之前，请确认你的邮箱账号
                                        </p>
                                      
                                        <p style=\"margin:0;padding:0;\">&nbsp;</p>
                                        <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:44px; width:200px\">
                                            <tbody>
                                            <tr>
                                                <td style=\"background-color:#00c1de; height:44px; line-height:44px;text-align:center; width:200px\">
                                                    <a href=\"{$activeUrl}\" style=\"display:block;text-decoration: none;color: #ffffff;font-size:16px;\" target=\"_blank\" rel=\"noopener\">激活账户
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width=\"40\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan=\"3\" style=\"height:40px;\">
                                        <div style=\"width: 500px; max-width: 90%;margin: 10px auto; font-size: 14px;\">
                                            <div style=\"color:#7d7d7d;margin: 8px 0;\">
                                                如果按钮无效，请将以下链接复制到浏览器地址栏完成激活。
                                            </div>
                                            <div>
                                                <a href=\"{$activeUrl}\" style=\"color: #35c8e6; word-break: break-all\" target=\"_blank\" rel=\"noopener\">{$activeUrl}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
                </table>
                <div style=\"padding-bottom: 40px;font-size: 14px;\">
                    <div style=\"padding-bottom: 40px;font-size: 14px;\">
                        <div style=\"width: 430px; max-width: 90%;margin: 10px auto;\">
                            彻底告别繁琐的支付接入流程 一次接入所有主流支付渠道，99% 系统可用性，满足你丰富的交易场景需求,为你的用户提供完美支付体验。
                        </div>
                        <div>
                            <span style=\"color: #76858c;\">服务咨询请联系：</span>
                            <a href=\"mailto:me@iredcap.cn\" style=\"color:#35c8e6; text-decoration: none;\" target=\"_blank\" rel=\"noopener\"> me@iredcap.cn </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    }
    /**
     * 注册成功内容
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     *
     * @return string
     */
    private function getRegCallbackContent($user){
        return '<div id="mailContentContainer" class="qmbox qm_con_body_content qqmail_webmail_only" style="">

<style type="text/css">
       .qmbox .ExternalClass,.qmbox .ExternalClass div,.qmbox .ExternalClass font,.qmbox .ExternalClass p,.qmbox .ExternalClass span,.qmbox .ExternalClass td,.qmbox h1,.qmbox img{line-height:100%;}.qmbox h1,.qmbox h2{display:block;font-family:Helvetica;font-style:normal;font-weight:700;}.qmbox #outlook a{padding:0;}.qmbox .ExternalClass,.qmbox .ReadMsgBody{width:100%;}.qmbox a,.qmbox blockquote,.qmbox body,.qmbox li,.qmbox p,.qmbox table,.qmbox td{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}.qmbox table,.qmbox td{mso-table-lspace:0;mso-table-rspace:0;}.qmbox img{-ms-interpolation-mode:bicubic;border:0;height:auto;outline:0;text-decoration:none;}.qmbox table{border-collapse:collapse!important;}.qmbox #bodyCell,.qmbox #bodyTable,.qmbox body{height:100%!important;margin:0;padding:0;width:100%!important;}.qmbox #bodyCell{padding:20px;}.qmbox #templateContainer{width:600px;border:1px solid #ddd;background-color:#fff;}.qmbox #bodyTable,.qmbox body{background-color:#FAFAFA;}.qmbox h1{color:#202020!important;font-size:26px;letter-spacing:normal;text-align:left;margin:0 0 10px;}.qmbox h2{color:#404040!important;font-size:20px;line-height:100%;letter-spacing:normal;text-align:left;margin:0 0 10px;}.qmbox h3,.qmbox h4{display:block;font-style:italic;font-weight:400;letter-spacing:normal;text-align:left;margin:0 0 10px;font-family:Helvetica;line-height:100%;}.qmbox h3{color:#606060!important;font-size:16px;}.qmbox h4{color:grey!important;font-size:14px;}.qmbox .headerContent{background-color:#f8f8f8;border-bottom:1px solid #ddd;color:#505050;font-family:Helvetica;font-size:20px;font-weight:700;line-height:100%;text-align:left;vertical-align:middle;padding:0;}.qmbox .bodyContent,.qmbox .footerContent{font-family:Helvetica;line-height:150%;text-align:left;}.qmbox .footerContent{text-align:center;}.qmbox .bodyContent pre{padding:15px;background-color:#444;color:#f8f8f8;border:0;}.qmbox .bodyContent pre code{white-space:pre;word-break:normal;word-wrap:normal;}.qmbox .bodyContent table{margin:10px 0;background-color:#fff;border:1px solid #ddd;}.qmbox .bodyContent table th{padding:4px 10px;background-color:#f8f8f8;border:1px solid #ddd;font-weight:700;text-align:center;}.qmbox .bodyContent table td{padding:3px 8px;border:1px solid #ddd;}.qmbox .table-responsive{border:0;}.qmbox .bodyContent a{word-break:break-all;}.qmbox .headerContent a .yshortcuts,.qmbox .headerContent a:link,.qmbox .headerContent a:visited{color:#1f5d8c;font-weight:400;text-decoration:underline;}.qmbox #headerImage{height:auto;max-width:600px;padding:20px;}.qmbox #templateBody{background-color:#fff;}.qmbox .bodyContent{color:#505050;font-size:14px;padding:20px;}.qmbox .bodyContent a .yshortcuts,.qmbox .bodyContent a:link,.qmbox .bodyContent a:visited{color:#1f5d8c;font-weight:400;text-decoration:underline;}.qmbox .bodyContent a:hover{text-decoration:none;}.qmbox .bodyContent img{display:inline;height:auto;max-width:560px;}.qmbox .footerContent{color:grey;font-size:12px;padding:20px;}.qmbox .footerContent a .yshortcuts,.qmbox .footerContent a span,.qmbox .footerContent a:link,.qmbox .footerContent a:visited{color:#606060;font-weight:400;text-decoration:underline;}@media only screen and (max-width:640px){.qmbox h1,.qmbox h2,.qmbox h3,.qmbox h4{line-height:100%!important;}.qmbox #templateContainer{max-width:600px!important;width:100%!important;}.qmbox #templateContainer,.qmbox body{width:100%!important;}.qmbox a,.qmbox blockquote,.qmbox body,.qmbox li,.qmbox p,.qmbox table,.qmbox td{-webkit-text-size-adjust:none!important;}.qmbox body{min-width:100%!important;}.qmbox #bodyCell{padding:10px!important;}.qmbox h1{font-size:24px!important;}.qmbox h2{font-size:20px!important;}.qmbox h3{font-size:18px!important;}.qmbox h4{font-size:16px!important;}.qmbox #templatePreheader{display:none!important;}.qmbox .headerContent{font-size:20px!important;line-height:125%!important;}.qmbox .footerContent{font-size:14px!important;line-height:115%!important;}.qmbox .footerContent a{display:block!important;}.qmbox .hide-mobile{display:none;}}
</style>

<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tbody>
    <tr>
        <td align="center" valign="top" id="bodyCell">
            <table border="0" cellpadding="0" cellspacing="0" id="templateContainer">
                <tbody>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
                            <tbody>
                            <tr>
                                <td valign="top" class="headerContent">
                                    <a href="https://pay.iredcap.cn" rel="noopener" target="_blank">
                                    </a>
                                    <img src="https://pay.iredcap.cn/static/logo-color.png" style="max-width:600px;padding:20px"
                                         id="headerImage">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
                            <tbody>
                            <tr>
                                <td valign="top" class="bodyContent">
                                    <p>
                                        Dear '. $user->username . ',
                                    </p>
                                    <p>
                                        欢迎加入,在开始使用之前，请确认你的账号
                                    </p>
                                    <p>
                                        操作安全码:
                                        <b>
                                            <span style="border-bottom:1px dashed #ccc;z-index:1" t="7" onclick="return false;"
                                                  data="'. $user->auth_code . '">
                                                '. $user->auth_code . '
                                            </span>
                                        </b>
                                    </p>
                                    <p>
                                        商户UID： '. $user->uid . '
                                        <br>
                                        注册邮箱： '. $user->account . '
                                        <br>
                                        商户名称： '. $user->username . '
                                   
                                    </p>
                                    <p>
                                        彻底告别繁琐的支付接入流程，一次接入所有主流支付渠道和分期渠道，99.99% 系统可用性，满足你丰富的交易场景需求，为你的用户提供完美支付体验。
                                    </p>
                                    <p>
                                        <br>
                                        小红帽科技
                                        <br>
                                        咨询邮箱: <a href="mailto:me@iredcap.cn" style="color:#35c8e6; text-decoration: none;" target="_blank" rel="noopener"> me@iredcap.cn </a>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter">
                            <tbody>
                            <tr>
                                <td valign="top" class="footerContent">
                                    <a href="https://pay.iredcap.cn/" rel="noopener" target="_blank">访问首页</a>
                                    <span class="hide-mobile">|</span>
                                    <a href="https://pay.iredcap.cn/login.html" rel="noopener" target="_blank">登录账户</a>
                                    <span class="hide-mobile">|</span>
                                    <a href="https://pay.iredcap.cn/cashier" rel="noopener" target="_blank">Demo测试</a>
                                    <br>
                                    Copyright &copy; Cmpay . All rights reserved.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<style type="text/css">.qmbox style, .qmbox script, .qmbox head, .qmbox link, .qmbox meta {display: none !important;}</style>
</div>';
    }
}